<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanViewFileHtml extends KViewFile
{
    public function display()
    {
    	$state = $this->getModel()->getState();

		$controller = $this->getService('com://admin/files.controller.file', array(
			'request' => $state->getData()
		));

		$file = $controller->read();

        $this->path = $file->fullpath;
        $this->filename = $file->name;
        $this->mimetype = $file->mimetype ? $file->mimetype : 'application/octet-stream';
        if ($file->isImage() || $file->extension === 'pdf') {
        	$this->disposition = 'inline';
        }

        if (!file_exists($this->path)) {
            $translator = $this->getService('translator')->getTranslator($this->getIdentifier());
            throw new KViewException($translator->translate('File not found'));
        }
        
        return parent::display();
    }
}