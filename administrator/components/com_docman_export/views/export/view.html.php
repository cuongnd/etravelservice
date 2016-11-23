<?php
/**
 * @package     DOCman Export
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

jimport('joomla.application.component.view');

class DocmanExportViewExport extends JView
{
    public function display($tpl = null)
    {
        JToolBarHelper::title('DOCman Export');

        JHtml::_('script', 'media/com_docman_export/js/jquery.min.js', false);
        JHtml::_('script', 'media/com_docman_export/js/export.js', false);
        JHtml::_('stylesheet', 'media/com_docman_export/css/bootstrap.min.css', false);
        JHtml::_('stylesheet', 'media/com_docman_export/css/migrator.css', false);

        parent::display($tpl);
    }

    public function getMissingDependencies()
    {
        $requirements = array(
            'zip' => array(
                class_exists('ZipArchive'),
                'ZipArchive class is needed for the export process.'
            ),
            'pdo' => array(
                extension_loaded('pdo') && extension_loaded('pdo_mysql'),
                'PDO driver for MySQL is missing.'
            ),
            'tmp' => array(
                is_writable(JPATH_ROOT.'/tmp'),
                'Please make sure tmp directory in your site root is writable'
            )
        );

        $return = array();
        foreach ($requirements as $key => $value) {
            if ($value[0] === false) {
                $return[$key] = $value[1];
            }
        }

        return $return;
    }
}
