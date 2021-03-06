<?php
/**
*
* Media controller
*
* @package	tsmart
* @subpackage
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: media.php 9026 2015-10-26 14:36:23Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Product Controller
 *
 * @package    tsmart
 * @author Max Milbers
 */
class TsmartControllerMedia extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		tsmConfig::loadJLang('com_tsmart_media');
		parent::__construct('tsmart_media_id');

	}


	/**
	 * for ajax call media
	 */
	function viewJson() {
		$view = $this->getView('media', 'json');
		$view->display(null);
	}

	function save($data = 0){

		$fileModel = tmsModel::getModel('media');

		//Now we try to determine to which this media should be long to
		$data = array_merge(vRequest::getRequest(),vRequest::get('media'));

		//$data['file_title'] = vRequest::getVar('file_title','','post','STRING',JREQUEST_ALLOWHTML);
		if(!empty($data['file_description'])){
			$data['file_description'] = JComponentHelper::filterText($data['file_description']); //vRequest::filter(); vRequest::getHtml('file_description','');
		}

		/*$data['media_action'] = vRequest::getCmd('media[media_action]');
		$data['media_attributes'] = vRequest::getCmd('media[media_attributes]');
		$data['file_type'] = vRequest::getCmd('media[file_type]');*/
		if(empty($data['file_type'])){
			$data['file_type'] = $data['media_attributes'];
		}

		$msg = '';
		if ($id = $fileModel->store($data)) {
			$msg = tsmText::_('com_tsmart_FILE_SAVED_SUCCESS');
		}

		$cmd = vRequest::getCmd('task');
		if($cmd == 'apply'){
			$redirection = 'index.php?option=com_tsmart&view=media&task=edit&tsmart_media_id='.$id;
		} else {
			$redirection = 'index.php?option=com_tsmart&view=media';
		}

		$this->setRedirect($redirection, $msg);
	}

	function synchronizeMedia(){

		if(vmAccess::manager('media')){

			$configPaths = array('assets_general_path','media_category_path','media_product_path','media_manufacturer_path','media_vendor_path');
			foreach($configPaths as $path){
				$this -> renameFileExtension(VMPATH_ROOT.DS.tsmConfig::get($path) );
			}

			if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
			$migrator = new Migrator();
			$result = $migrator->portMedia();

			$this->setRedirect($this->redirectPath, $result);
		} else {
			$msg = 'Forget IT';
			$this->setRedirect('index.php?option=com_tsmart', $msg);
		}

	}

	function renameFileExtension($path){

		$results = array();
		$handler = opendir($path);

		// open directory and walk through the filenames
		while ($file = readdir($handler)) {
			// if file isn't this directory or its parent, add it to the results
			if ($file != "." && $file != "..") {
				if(preg_match('/JPEG$/', $file)) {
					$results['jpeg'][] = $file;
				} else if(preg_match('/JPG$/', $file)) {
					$results['jpg'][] = $file;
				} else if(preg_match('/PNG$/', $file)) {
					$results['png'][] = $file;
				} else if(preg_match('/GIF$/', $file)) {
					$results['gif'][] = $file;
				}
			}
		}

		foreach($results as $filetype => $files){
			foreach($files as $file){
				$new = JFile::stripExt($file);
				if(!JFile::exists($file)){
					$succ = rename ($path.$file,$path.$new.'.'.$filetype);
				}
			}
		}

	}
}
// pure php no closing tag
