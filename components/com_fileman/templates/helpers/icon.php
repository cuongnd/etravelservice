<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanTemplateHelperIcon extends KTemplateHelperAbstract
{
    /**
     * Used for giving class names to the icons
     * @var int
     */
    protected static $_count = 0;

    protected static $_extension_map = array(
        'archive'     => array('7z','gz','rar','tar','zip'),
        'audio'       => array('aif','aiff','alac','amr','flac','ogg','m3u','m4a','mid','mp3','mpa','wav','wma'),
        'document'    => array('doc','docx','rtf','txt','ppt','pptx','pps','xml'),
        'image'       => array('bmp','gif','jpg','jpeg','png','psd','tif','tiff'),
        'pdf'         => array('pdf'),
        'spreadsheet' => array('xls', 'xlsx', 'ods'),
        'video'       => array('3gp','avi','flv','mkv','mov','mp4','mpg','mpeg','rm','swf','vob','wmv')
    );

    public function path($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'extension' => null,
            'icon' => null
        ));

        $icon = '';

        if ($config->extension) {
            $config->icon = $this->getIconForExtension($config->extension);
        }

        if ($config->icon) {
            $icon = 'media://com_fileman/images/icons/'.$config->icon.'.png';
        }

        return $icon;
    }

    public function css($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'icon' => null
        ));

        $icon = $this->path($config);

        $class = ++self::$_count;

        $html = sprintf('
        <style>
            .category-icon-%d {
                background-image: url(%s);
            }
        </style>', $class, $icon);

        return $html;
    }

    public function getCount($config = array())
    {
        return self::$_count;
    }

    public function getIconForExtension($extension)
    {
        $extension = strtolower($extension);

        foreach (self::$_extension_map as $type => $extensions)
        {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }

        return false;
    }
}
