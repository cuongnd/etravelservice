<?php
/**
 * @package     DOCman Export
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/../controller.php';

class DocmanExportControllerExport extends DocmanExportController
{
    /**
     * The view content
     *
     * @var string
     */
    protected $_content;

    /**
     * The file path
     *
     * @var string
     */
    protected $path = '';

    /**
     * The file name
     *
     * @var string
     */
    protected $filename = '';

    /**
     * The file disposition
     *
     * @var string
     */
    protected $disposition = 'attachment';

    /**
     * Transport method
     *
     * @var string
     */
    protected $transport = 'php';

    /**
     * File path
     *
     * @var string
     */
    protected $file;

    /**
     * File size in bytes
     *
     * @var int
     */
    protected $filesize;

    /**
     * Start point when serving a file in chunks as bytes
     *
     * @var int
     */
    protected $start_point;

    /**
     * End point when serving a file in chunks as bytes
     *
     * @var int
     */
    protected $end_point;

    /**
     * End the request by exiting in the display method if true
     *
     * @var boolean
     */
    protected $end_request = true;

    public function download()
    {
        $path = JPATH_ROOT.'/tmp/docman_export.zip';

        if (!is_file($path)) {
            throw new RuntimeException('Unable to create archive file');
        }

        $this->path = $path;
        $this->filename = pathinfo($path, PATHINFO_BASENAME);
        $this->mimetype = 'application/octet-stream';
        $this->disposition = 'attachment';
        $this->_serve();
    }

    /**
     * Return the views output
     *
     * @throws RuntimeException
     * @return void
     */
    protected function _serve()
    {
        if (empty($this->path) && empty($this->_content)) {
            throw new RuntimeException('No content or path supplied');
        }

        // For a certain unmentionable browser
        if(ini_get('zlib.output_compression')) {
            @ini_set('zlib.output_compression', 'Off');
        }

        // fix for IE7/8
        if(function_exists('apache_setenv')) {
            apache_setenv('no-gzip', '1');
        }

        // Remove PHP time limit
        if(!ini_get('safe_mode')) {
            @set_time_limit(0);
        }

        // Clear buffer
        while (@ob_end_clean());

        $this->filename = basename($this->filename);

        if (!empty($this->_content)) { // File body is passed as string
            if (empty($this->filename)) {
                throw new RuntimeException('No filename supplied');
            }
        } elseif (!empty($this->path)) { // File is read from disk
            if (empty($this->filename)) {
                $this->filename = basename($this->path);
            }
        }

        $transport = '_transport'.ucfirst(strtolower($this->transport));
        if (!method_exists($this, $transport)) {
            throw new RuntimeException('Transport method is missing');
        }

        $this->$transport();

        if ($this->end_request) {
            exit;
        }
    }

    /**
     * Reads the file by chunks and serves it
     *
     * Supports HTTP_RANGE headers
     *
     * @throws RuntimeException
     * @throws OutOfRangeException
     * @return int Number of bytes served
     */
    protected function _transportPhp()
    {
        if (empty($this->filesize)) {
            $this->filesize = $this->path ? filesize($this->path) : strlen($this->_content);
        }

        $this->start_point = 0;
        $this->end_point = max($this->filesize-1, 0);

        $this->_setHeaders();

        $range = @$_SERVER['HTTP_RANGE'];
        if ($range && preg_match('/^bytes=((\d*-\d*,? ?)+)$/', $range))
        {
            // Partial download
            $parts = explode('-', substr($range, strlen('bytes=')));
            $this->start_point = $parts[0];
            if (!empty($parts[0])) {
                $this->start_point = $parts[0];
            }

            if (!empty($parts[1]) && $parts[1] <= $this->end_point) {
                $this->end_point = $parts[1];
            }

            if ($this->start_point > $this->filesize) {
                throw new OutOfRangeException('Invalid start point given in range header');
            }

            header('HTTP/1.0 206 Partial Content');
            header('Status: 206 Partial Content');
            header('Accept-Ranges: bytes');
            header('Content-Range: bytes '.$this->start_point.'-'.$this->end_point.'/'.$this->filesize);
            header('Content-Length: '.($this->end_point - $this->start_point + 1), true);
        }

        flush();

        if ($this->_content)
        {
            $this->file = tmpfile();
            fwrite($this->file, $this->_content);
            fseek($this->file, 0);
        }
        else {
            $this->file = fopen($this->path, 'rb');
        }

        if ($this->file === false) {
            throw new RuntimeException('Cannot open file');
        }

        if ($this->start_point > 0) {
            fseek($this->file, $this->start_point);
        }

        //serve data chunk and update download progress log
        $count = 0;
        $start = $this->start_point;
        while (!feof($this->file) && $start <= $this->end_point)
        {
            //calculate next chunk size
            $chunk_size = 1*(1024*1024);
            if ($start + $chunk_size > $this->end_point + 1) {
                $chunk_size = $this->end_point - $start + 1;
            }

            //get data chunk
            $buffer = fread($this->file, $chunk_size);
            if ($buffer === false) {
                throw new RuntimeException('Could not read file');
            }

            echo $buffer;
            flush();
            $count += strlen($buffer);
        }

        if (!empty($this->file) && is_resource($this->file)) {
            fclose($this->file);
        }

        return $count;
    }

    /**
     * Set the appropriate headers for serving the file
     *
     * @return void
     */
    protected function _setHeaders()
    {
        if ($this->mimetype) {
            header('Content-type: '.$this->mimetype);
        }

        $this->_setDisposition();

        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        // Prevent caching
        // Pragma and cache-control needs to be empty for IE on SSL.
        // See: http://support.microsoft.com/default.aspx?scid=KB;EN-US;q316431
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : null;
        if ($agent && preg_match('#(?:MSIE |Internet Explorer/)(?:[0-9.]+)#', $agent)
            && (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ) {
            header('Pragma: ');
            header('Cache-Control: ');
        }
        else
        {
            header('Pragma: no-store,no-cache');
            header('Cache-Control: no-cache, no-store, must-revalidate, max-age=-1');
            header('Cache-Control: post-check=0, pre-check=0', false);

        }
        header('Expires: Mon, 14 Jul 1789 12:30:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        if (!empty($this->filesize)) {
            header('Content-Length: '.$this->filesize);
        }
    }

    /**
     * Set the disposition headers
     *
     * @return void
     */
    protected function _setDisposition()
    {
        if(isset($this->disposition) && $this->disposition == 'inline') {
            header('Content-Disposition: inline; filename="'.$this->filename.'"');
        } else {
            header('Content-Description: File Transfer');
            //header('Content-type: application/force-download');
            header('Content-Disposition: attachment; filename="'.$this->filename.'"');
        }
    }
}