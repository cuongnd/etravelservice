<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved by the author.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: media.php 9058 2015-11-10 18:30:54Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model for tsmart Product Files
 *
 * @package		tsmart
 */
class tsmartModelMedia extends tmsModel {

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('tsmart_media_id');
		$this->setMainTable('medias');
		$this->addvalidOrderingFieldName(array('ordering'));
		$this->_selectedOrdering = 'created_on';

	}

	/**
	 * Gets a single media by tsmart_media_id
	 * @Todo must be adjusted to new pattern, using first param as id to get
	 * @param string $type
	 * @param string $mime mime type of file, use for exampel image
	 * @return mediaobject
	 */
	function getFile($type=0,$mime=0){

		if (empty($this->_data)) {

			$data = $this->getTable('medias');
			$data->load((int)$this->_id);

			if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');

			$this->_data = VmMediaHandler::createMedia($data,$type,$mime);
		}

		return $this->_data;

	}

	/**
	 * Kind of getFiles, it creates a bunch of image objects by an array of tsmart_media_id
	 *
	 * @author Max Milbers
	 * @param int $tsmart_media_id
	 * @param string $type
	 * @param string $mime
	 */
	function createMediaByIds($tsmart_media_ids,$type='',$mime='',$limit =0){

		if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');

		$app = JFactory::getApplication();

		$medias = array();

		static $_medias = array();

		if(!empty($tsmart_media_ids)){
			if(!is_array($tsmart_media_ids)) $tsmart_media_ids = explode(',',$tsmart_media_ids);

			$data = $this->getTable('medias');
			foreach($tsmart_media_ids as $k => $tsmart_media_id){
				if($limit!==0 and $k==$limit and !empty($medias)) break; // never break if $limit = 0
				if(is_object($tsmart_media_id)){
					$id = $tsmart_media_id->tsmart_media_id;
				} else {
					$id = $tsmart_media_id;
				}
				if(!empty($id)){
					if (!isset($_medias[$id])) {
						$data->load((int)$id);
						if($app->isSite()){
							if($data->published==0){
								$_medias[$id] = $this->createVoidMedia($type,$mime);
								continue;
							}
						}
						$file_type 	= empty($data->file_type)? $type:$data->file_type;
						$mime		= empty($data->file_mimetype)? $mime:$data->file_mimetype;
						if($app->isSite()){
							$selectedLangue = explode(",", $data->file_lang);
							$lang =  JFactory::getLanguage();
							if(in_array($lang->getTag(), $selectedLangue) || $data->file_lang == '') {
								$_medias[$id] = VmMediaHandler::createMedia($data,$file_type,$mime);
								if(is_object($tsmart_media_id) && !empty($tsmart_media_id->product_name)) $_medias[$id]->product_name = $tsmart_media_id->product_name;
							}
						} else {
							$_medias[$id] = VmMediaHandler::createMedia($data,$file_type,$mime);
							if(is_object($tsmart_media_id) && !empty($tsmart_media_id->product_name)) $_medias[$id]->product_name = $tsmart_media_id->product_name;
						}
					}
					if (!empty($_medias[$id])) {
						$medias[] = $_medias[$id];
					}
				}
			}
		}

		if(empty($medias)){
			$medias[] = $this->createVoidMedia($type,$mime);
		}

		return $medias;
	}

	function createVoidMedia($type,$mime){

		static $voidMedia = null;
		if(empty($voidMedia)){
			$data = $this->getTable('medias');

			//Create empty data
			$data->tsmart_media_id = 0;
			$data->tsmart_vendor_id = 0;
			$data->file_title = '';
			$data->file_description = '';
			$data->file_meta = '';
			$data->file_class = '';
			$data->file_mimetype = '';
			$data->file_type = '';
			$data->file_url = '';
			$data->file_url_thumb = '';
			$data->published = 0;
			$data->file_is_downloadable = 0;
			$data->file_is_forSale = 0;
			$data->file_is_product_image = 0;
			$data->shared = 0;
			$data->file_params = 0;
			$data->file_lang = '';

			$voidMedia = VmMediaHandler::createMedia($data,$type,$mime);
		}
		return $voidMedia;
	}

	/**
	* Retrieve a list of files from the database. This is meant only for backend use
	*
	* @author Max Milbers
	* @param boolean $onlyPublished True to only retrieve the published files, false otherwise
	* @param boolean $noLimit True if no record count limit is used, false otherwise
	* @return object List of media objects
	*/

	function getFiles($onlyPublished=false, $noLimit=false, $tsmart_product_id=null, $cat_id=null, $where=array(),$nbr=false){

		$this->_noLimit = $noLimit;

		if(empty($db)) $db = JFactory::getDBO();

		$query = '';

		$selectFields = array();

		$joinTables = array();
		$joinedTables = '';
		$whereItems= array();
		$groupBy ='';
		$orderByTable = '';

		if(!empty($tsmart_product_id)){
			$mainTable = '`#__tsmart_product_medias`';
			$selectFields[] = ' `#__tsmart_medias`.`tsmart_media_id` as tsmart_media_id ';
			$joinTables[] = ' LEFT JOIN `#__tsmart_medias` ON `#__tsmart_medias`.`tsmart_media_id`=`#__tsmart_product_medias`.`tsmart_media_id` and `tsmart_product_id` = "'.$tsmart_product_id.'"';
			$whereItems[] = '`tsmart_product_id` = "'.$tsmart_product_id.'"';

			if($this->_selectedOrdering=='ordering'){
				$orderByTable = '`#__tsmart_product_medias`.';
			} else{
				$orderByTable = '`#__tsmart_medias`.';
			}
		}

		else if(!empty($cat_id)){
			$mainTable = '`#__tsmart_category_medias`';
			$selectFields[] = ' `#__tsmart_medias`.`tsmart_media_id` as tsmart_media_id';
			$joinTables[] = ' LEFT JOIN `#__tsmart_medias` ON `#__tsmart_medias`.`tsmart_media_id`=`#__tsmart_category_medias`.`tsmart_media_id` and `tsmart_category_id` = "'.$cat_id.'"';
			$whereItems[] = '`tsmart_category_id` = "'.$cat_id.'"';
			if($this->_selectedOrdering=='ordering'){
				$orderByTable = '`#__tsmart_category_medias`.';
			} else{
				$orderByTable = '`#__tsmart_medias`.';
			}
		}

		else {
			$mainTable = '`#__tsmart_medias`';
			$selectFields[] = ' `tsmart_media_id` ';


			if(!vmAccess::manager('managevendors')){
				$vendorId = vmAccess::isSuperVendor();
				$whereItems[] = '(`tsmart_vendor_id` = "'.$vendorId.'" OR `shared`="1")';
			}

		}

		if ($onlyPublished) {
			$whereItems[] = '`#__tsmart_medias`.`published` = 1';
		}

		if ($search = vRequest::getString('searchMedia', false)){
			$search = '"%' . $db->escape( $search, true ) . '%"' ;
			$where[] = ' (`file_title` LIKE '.$search.'
								OR `file_description` LIKE '.$search.'
								OR `file_meta` LIKE '.$search.'
								OR `file_url` LIKE '.$search.'
								OR `file_url_thumb` LIKE '.$search.'
							) ';
		}
		if ($type = vRequest::getCmd('search_type')) {
			$where[] = 'file_type = "'.$type.'" ' ;
		}

		if ($role = vRequest::getCmd('search_role')) {
			if ($role == "file_is_downloadable") {
				$where[] = '`file_is_downloadable` = 1';
				$where[] = '`file_is_forSale` = 0';
			} elseif ($role == "file_is_forSale") {
				$where[] = '`file_is_downloadable` = 0';
				$where[] = '`file_is_forSale` = 1';
			} else {
				$where[] = '`file_is_downloadable` = 0';
				$where[] = '`file_is_forSale` = 0';
			}
		}
		
		if (!empty($where)) $whereItems = array_merge($whereItems,$where);


		if(count($whereItems)>0){
			$whereString = ' WHERE '.implode(' AND ', $whereItems );
		} else {
			$whereString = ' ';
		}


		$orderBy = $this->_getOrdering($orderByTable);#

		if(count($selectFields)>0){

			$select = implode(', ', $selectFields ).' FROM '.$mainTable;
			//$selectFindRows = 'SELECT COUNT(*) FROM '.$mainTable;
			if(count($joinTables)>0){
				foreach($joinTables as $table){
					$joinedTables .= $table;
				}
			}

		} else {
			vmError('No select fields given in getFiles','No select fields given');
			return false;
		}

		$this->_data = $this->exeSortSearchListQuery(2, $select, $joinedTables, $whereString, $groupBy, $orderBy,'',$nbr);
		if(empty($this->_data)){
			return array();
		}

		if( !is_array($this->_data)){
			$this->_data = explode(',',$this->_data);
		}

		$this->_data = $this->createMediaByIds($this->_data);
		return $this->_data;

	}

	/**
	 * This function stores a media and updates then the refered table
	 *
	 * @author Max Milbers
	 * @author Patrick Kohl
	 * @param array $data Data from a from
	 * @param string $type type of the media  category,product,manufacturer,shop, ...
	 */
	function storeMedia($dataI,$type){

		vRequest::vmCheckToken('Invalid Token, while trying to save media '.$type);

		$data = $dataI;
		if(isset($dataI['media'])){
			$data = array_merge($data,$dataI['media']);
		}

		if(empty($data['media_action'])){
			$data['media_action'] = 'none';
		}

		//the active media id is not empty, so there should be something done with it
		if( (!empty($data['active_media_id']) and isset($data['tsmart_media_id']) ) || $data['media_action']=='upload'){

			$oldIds = $data['tsmart_media_id'];

			$data['file_type'] = $type;

			$this -> setId($data['active_media_id']);

			$tsmart_media_id = $this->store($data);

			if($tsmart_media_id){
				//added by Mike
				$this->setId($tsmart_media_id);

				if(!empty($oldIds)){
					if(!is_array($oldIds)) $oldIds = array($oldIds);

					if(!empty($data['mediaordering']) && $data['media_action']=='upload'){
						$data['mediaordering'][$tsmart_media_id] = count($data['mediaordering']);
					}
					$tsmart_media_ids = array_merge( (array)$tsmart_media_id,$oldIds);
					$data['tsmart_media_id'] = array_unique($tsmart_media_ids);
				} else {
					$data['tsmart_media_id'] = $tsmart_media_id;
				}
			}

		}

		if(!empty($data['mediaordering'])){
			asort($data['mediaordering']);
			$sortedMediaIds = array();
			foreach($data['mediaordering'] as $k=>$v){
				$sortedMediaIds[] = $k;
			}
			$data['tsmart_media_id'] = $sortedMediaIds;
		}

		//set the relations
		$table = $this->getTable($type.'_medias');
		//vmdebug('my data before storing media',$data);
		// Bind the form fields to the country table
		$table->bindChecknStore($data);

		return $table->tsmart_media_id;

	}

	/**
	 * Store an entry of a mediaItem, this means in end effect every media file in the shop
	 * images, videos, pdf, zips, exe, ...
	 *
	 * @author Max Milbers
	 */
	public function store(&$data) {

		$data['tsmart_media_id'] = $this->getId();
		if(!vmAccess::manager('media.edit')){
			vmWarn('Insufficient permission to store media');
			return false;
		} else if( empty($data['tsmart_media_id']) and !vmAccess::manager('media.create')){
			vmWarn('Insufficient permission to create media');
			return false;
		}

		tsmConfig::loadJLang('com_tsmart_media');
		if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');

		$table = $this->getTable('medias');

		$table->bind($data);
		$data = VmMediaHandler::prepareStoreMedia($table,$data,$data['file_type']); //this does not store the media, it process the actions and prepares data
		if($data===false) return $table->tsmart_media_id;
		// workarround for media published and product published two fields in one form.
		$tmpPublished = false;
		if (isset($data['media_published'])){
			$tmpPublished = $data['published'];
			$data['published'] = $data['media_published'];
		}

		$table->bindChecknStore($data);

		if($tmpPublished){
			$data['published'] = $tmpPublished;
		}
		return $table->tsmart_media_id;
	}

	public function attachImages($objects,$type,$mime='',$limit=0){
		if(!empty($objects)){
			if(!is_array($objects)) $objects = array($objects);
			foreach($objects as $k => $object){
				if(!is_object($object)){
					$object = $this->createVoidMedia($type,$mime);
				}

				if(empty($object->tsmart_media_id)) $tsmart_media_id = null; else $tsmart_media_id = $object->tsmart_media_id;
				$object->images = $this->createMediaByIds($tsmart_media_id,$type,$mime,$limit);

				//This should not be used in fact. It is for legacy reasons there.
				if(isset($object->images[0]->file_url_thumb)){
					$object->file_url_thumb = $object->images[0]->file_url_thumb;
					$object->file_url = $object->images[0]->file_url;
				}
			}
		}
	}

	function remove($ids){

		if(!vmAccess::manager('media.delete')){
			vmWarn('Insufficient permissions to delete media');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
