<?php
/**
 * @package    DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComDocman_importViewImportHtml extends ComDefaultViewHtml
{
    public function hasBackupTables()
    {
        $db = JFactory::getDbo();

        $tables     = $db->getTableList();
        $categories = $db->replacePrefix('#__docman_categories_bkp');
        $documents  = $db->replacePrefix('#__docman_bkp');

        return (in_array($categories, $tables) && in_array($documents, $tables));
    }

    public function getMissingDependencies()
    {
        $category_count = $this->getService('com://admin/docman.model.categories')->getTotal();
        $document_count = $this->getService('com://admin/docman.model.documents')->getTotal();

        $requirements = array(
            'zip' => array(
                class_exists('ZipArchive'),
                'ZipArchive class is needed for the export process.'
            ),
            'tmp' => array(
                is_writable(JPATH_ROOT.'/tmp'),
                'Please make sure tmp directory in your site root is writable'
            ),
            'categories' => array(
                $category_count === 0,
                'You need to delete all existing categories before you start the migration process'
            ),
            'documents' => array(
                $document_count === 0,
                'You need to delete all existing documents before you start the migration process'
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
