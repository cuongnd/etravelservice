<?php
/**
*
* Manufacturer Model
*
* @package	tsmart
* @subpackage Manufacturer
* @author Patrick Kohl, Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: manufacturer.php 8971 2015-09-07 09:35:42Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for tsmart Manufacturers
 *
 * @package tsmart
 * @subpackage Manufacturer
 * @author Max Milbers
 * @todo Replace getOrderUp and getOrderDown with JTable move function. This requires the tsmart_product_category_xref table to replace the ordering with the ordering column
 */
class tsmartModelManufacturer extends VmModel {

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('tsmart_manufacturer_id');
		$this->setMainTable('manufacturers');
		$this->addvalidOrderingFieldName(array('m.tsmart_manufacturer_id','mf_name','mf_desc','mf_category_name','mf_url'));
		$this->removevalidOrderingFieldName('tsmart_manufacturer_id');
		$this->_selectedOrdering = 'mf_name';
		$this->_selectedOrderingDir = 'ASC';
	}


	/**
	* Load a single manufacturer
	*/
	public function getManufacturer($id = 0) {

		if(!empty($id)) $this->_id = (int)$id;

		if(empty($this->_cache[$this->_id])){
			$this->_cache[$this->_id] = $this->getTable('manufacturers');
			$this->_cache[$this->_id]->load($this->_id);

			$xrefTable = $this->getTable('manufacturer_medias');
			$this->_cache[$this->_id]->tsmart_media_id = $xrefTable->load($this->_id);
		}

		return $this->_cache[$this->_id];
	}

     /**
	 * Bind the post data to the manufacturer table and save it
     *
     * @author Max Milbers
     * @return boolean True is the save was successful, false otherwise.
	 */
	public function store(&$data) {

		if(!vmAccess::manager('manufacturer.edit')){
			vmWarn('Insufficient permission to store manufacturer');
			return false;
		} else if( empty($data['tsmart_manufacturer_id']) and !vmAccess::manager('manufacturer.create')){
			vmWarn('Insufficient permission to create manufacturer');
			return false;
		}
		// Setup some place holders
		$table = $this->getTable('manufacturers');

		$table->bindChecknStore($data);

		// Process the images
		$mediaModel = VmModel::getModel('Media');
		$mediaModel->storeMedia($data,'manufacturer');

		$cache = JFactory::getCache('com_tsmart_cat_manus','callback');
		$cache->clean();
		return $table->tsmart_manufacturer_id;
	}

	function remove($ids){

		if(!vmAccess::manager('manufacturer.delete')){
			vmWarn('Insufficient permissions to delete manufacturer');
			return false;
		}
		return parent::remove($ids);
	}

    /**
     * Returns a dropdown menu with manufacturers
     * @author Max Milbers
	 * @return object List of manufacturer to build filter select box
	 */
	function getManufacturerDropdown() {
		$db = JFactory::getDBO();
		$query = "SELECT `tsmart_manufacturer_id` AS `value`, `mf_name` AS text, '' AS disable
						FROM `#__tsmart_manufacturers_".VmConfig::$vmlang."` ORDER BY `mf_name` ASC";
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHtml::_('select.option',  '0', '- '. tsmText::_('com_tsmart_SELECT_MANUFACTURER') .' -' ));
		return $options;
	}


    /**
	 * Retireve a list of countries from the database.
	 *
     * @param string $onlyPuiblished True to only retreive the publish countries, false otherwise
     * @param string $noLimit True if no record count limit is used, false otherwise
	 * @return object List of manufacturer objects
	 */
	public function getManufacturers($onlyPublished=false, $noLimit=false, $getMedia=false) {

		$this->_noLimit = $noLimit;
		$app = JFactory::getApplication();
		$option	= 'com_tsmart';

		$view = vRequest::getCmd('view','');
		$tsmart_manufacturercategories_id	= $app->getUserStateFromRequest( $option.'.'.$view.'.tsmart_manufacturercategories_id', 'tsmart_manufacturercategories_id', 0, 'int' );
		$search = $app->getUserStateFromRequest( $option.'.'.$view.'.search', 'search', '', 'string' );

		static $_manufacturers = array();

		$hash = $search.json_encode($tsmart_manufacturercategories_id).VmConfig::$vmlang.(int)$onlyPublished.(int)$this->_noLimit.(int)$getMedia;

		if (array_key_exists ($hash, $_manufacturers)) {
			vmdebug('Return cached getManufacturers');
			return $_manufacturers[$hash];
		}

		$where = array();
		if ($tsmart_manufacturercategories_id > 0) {
			$where[] .= ' `m`.`tsmart_manufacturercategories_id` = '. $tsmart_manufacturercategories_id;
		}

		$joinedTables = ' FROM `#__tsmart_manufacturers` as m';
		$select = ' `m`.*';
		if ( $search && $search != 'true') {
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape( $search, true ) . '%"' ;
			//$search = $db->Quote($search, false);
			$where[] .= ' LOWER( `mf_name` ) LIKE '.$search;
		}

		$ordering = $this->_getOrdering();
		//if ( $search && $search != 'true' or strpos($ordering,'mf_')!==FALSE or $ordering == 'm.tsmart_manufacturer_id' ) {
			$select .= ',`#__tsmart_manufacturers_'.VmConfig::$vmlang.'`.*, mc.`mf_category_name` ';
			$joinedTables .= ' INNER JOIN `#__tsmart_manufacturers_'.VmConfig::$vmlang.'` USING (`tsmart_manufacturer_id`) ';
			$joinedTables .= ' LEFT JOIN `#__tsmart_manufacturercategories_'.VmConfig::$vmlang.'` AS mc on  mc.`tsmart_manufacturercategories_id`= `m`.`tsmart_manufacturercategories_id` ';
		//}

		if ($onlyPublished) {
			$where[] .= ' `m`.`published` = 1';
		}

		$groupBy=' ';
		if($getMedia){
			$select .= ',mmex.tsmart_media_id ';
			$joinedTables .= 'LEFT JOIN `#__tsmart_manufacturer_medias` as mmex ON `m`.`tsmart_manufacturer_id`= mmex.`tsmart_manufacturer_id` ';
			$groupBy=' GROUP BY `m`.`tsmart_manufacturer_id` ';

		}
		$whereString = ' ';
		if (count($where) > 0) $whereString = ' WHERE '.implode(' AND ', $where).' ' ;

		$_manufacturers[$hash] = $this->_data = $this->exeSortSearchListQuery(0,$select,$joinedTables,$whereString,$groupBy,$ordering );

		return $_manufacturers[$hash];
	}

	static function getManufacturersOfProductsInCategory($tsmart_category_id,$vmlang,$mlang = false){

		if($mlang){
			$query = 'SELECT DISTINCT IFNULL(l.`mf_name`,ld.mf_name) as mf_name,IFNULL(l.`tsmart_manufacturer_id`,ld.`tsmart_manufacturer_id`) as tsmart_manufacturer_id
FROM `#__tsmart_manufacturers_'.VmConfig::$defaultLang.'` as ld
LEFT JOIN `#__tsmart_manufacturers_'.$vmlang.'` as l using (`tsmart_manufacturer_id`)';
			vmdebug('getManufacturersOfProductsInCategory use language fallback');
		} else {
			$query = 'SELECT DISTINCT l.`mf_name`,l.`tsmart_manufacturer_id` FROM `#__tsmart_manufacturers_' . $vmlang . '` as l';
		}
		// if ($mf_tsmart_product_ids) {

		$query .= ' INNER JOIN `#__tsmart_product_manufacturers` AS pm using (`tsmart_manufacturer_id`)';
		$query .= ' INNER JOIN `#__tsmart_products` as p ON p.`tsmart_product_id` = pm.`tsmart_product_id` ';
		if ($tsmart_category_id) {
			$query .= ' INNER JOIN `#__tsmart_product_categories` as c ON c.`tsmart_product_id` = pm.`tsmart_product_id` ';
		}
		$query .= ' WHERE p.`published` =1';
		if ($tsmart_category_id) {
			$query .= ' AND c.`tsmart_category_id` =' . (int)$tsmart_category_id;
		}
		$query .= ' ORDER BY `mf_name`';
		$db = JFactory::getDBO();
		$db->setQuery ($query);
		return $db->loadObjectList ();
	}
}
// pure php no closing tag