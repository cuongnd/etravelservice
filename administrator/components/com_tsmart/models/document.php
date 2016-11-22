<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package    tsmart
 * @subpackage Currency
 */
class tsmartModelDocument extends tmsModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('document');
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getItem($id = 0)
    {
        return $this->getData($id);
    }


    /**
     * Retireve a list of currencies from the database.
     * This function is used in the backend for the currency listing, therefore no asking if enabled or not
     * @author Max Milbers
     * @return object List of currency objects
     */
    function getItemList($search = '')
    {

        //echo $this->getListQuery()->dump();
        $data = parent::getItems();
        return $data;

    }

    function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $app=JFactory::getApplication();
        $tsmart_product_id=$app->input->get('tsmart_product_id',0,'int');
        $query->select('document.*')
            ->from('#__tsmart_document AS document')
            ->where('document.tsmart_product_id='.(int)$tsmart_product_id)
        ;
        $user = JFactory::getUser();
        $shared = '';
        if (vmAccess::manager()) {
            //$query->where('transferaddon.shared=1','OR');
        }
        $search = vRequest::getCmd('search', false);
        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $query->where('document.title LIKE ' . $search);
        }
        if (empty($this->_selectedOrdering)) vmTrace('empty _getOrdering');
        if (empty($this->_selectedOrderingDir)) vmTrace('empty _selectedOrderingDir');
        $query->order($this->_selectedOrdering . ' ' . $this->_selectedOrderingDir);
        return $query;
    }


    /**
     * Retireve a list of currencies from the database.
     *
     * This is written to get a list for selecting currencies. Therefore it asks for enabled
     * @author Max Milbers
     * @return object List of currency objects
     */

    function store(&$data)
    {
        $path_upload_file_document = tsmConfig::$_path_upload_file_document;
        if (!vmAccess::manager('document')) {
            vmWarn('Insufficient permissions to store document');
            return false;
        }
        $file = JRequest::getVar('file_upload', null, 'files', 'array');
        if ($file['name'] != "") {
            //Import filesystem libraries. Perhaps not necessary, but does not hurt
            jimport('joomla.filesystem.file');

            //Clean up filename to get rid of strange characters like spaces etc
            $filename = JFile::makeSafe($file['name']);
            $new_file_upload_path = $path_upload_file_document . $filename;
            $table_document = tsmTable::getInstance('document', 'Table');
            $table_document->jload(array(file_upload => $new_file_upload_path));
            if ($table_document->tsmart_document_id) {
                $this->setError('Duplicate file');
                return false;
            }
        }
        $input = JFactory::getApplication()->input;
        $ok = parent::store($data);
        if ($ok) {
            $file = JRequest::getVar('file_upload', null, 'files', 'array');

            if ($file['name'] != "") {
                //Import filesystem libraries. Perhaps not necessary, but does not hurt
                jimport('joomla.filesystem.file');

                //Clean up filename to get rid of strange characters like spaces etc
                $filename = JFile::makeSafe($file['name']);

                //Set up the source and destination of the file
                $src = $file['tmp_name'];
                $file_upload_path = $path_upload_file_document . $filename;
                $dest = JPATH_ROOT . DS . $file_upload_path;

                $table_document = tsmTable::getInstance('document', 'Table');
                $table_document->load($data['tsmart_document_id']);
                $old_file_path = $table_document->file_upload;
                if ($old_file_path != "" && JFile::exists(JPATH_ROOT . DS . $old_file_path)) {
                    JFile::delete(JPATH_ROOT . DS . $old_file_path);
                }
                $data['file_upload'] = $file_upload_path;
                $ok1 = parent::store($data);
                if (!$ok1) {
                    $this->setError('duplicate file');
                    return false;
                }

                //First check if the file has the right extension, we need jpg only
                $ext_file = strtolower(JFile::getExt($filename));
                $list_allow_file=tsmConfig::$_list_allow_file;

                if (in_array($ext_file,$list_allow_file )) {
                    if (JFile::upload($src, $dest)) {
                        //Redirect to a page of your choice
                    } else {
                        //Redirect and throw an error message
                    }
                } else {
                    //Redirect and notify user file is not right extension
                }

            }

        }
        return $ok;
    }

    function remove($ids)
    {
        if (!vmAccess::manager('document')) {
            vmWarn('Insufficient permissions to remove document');
            return false;
        }
        return parent::remove($ids);
    }

}
// pure php no closing tag