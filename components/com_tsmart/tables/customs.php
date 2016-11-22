<?php
/**
*
* Media table
*
* @package	tsmart
* @subpackage Media
* @author  Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: media.php 3057 2011-04-19 12:59:22Z Electrocity $
*/

// Check to ensure this custom is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Custom table class
 * The class is to manage description of custom fields in the shop.
 *
 * @author Patrick Kohl
 * @package		tsmart
 */
class TableCustoms extends tsmTable {

	/** @var int Primary key */
	var $tsmart_custom_id		= 0;
	/** @var int parent */
	var $custom_parent_id		= 0;

	var $tsmart_vendor_id		= 0;

	/** @var int(1)  1= only back-end display*/
	var $admin_only		= 0;

	var $custom_jplugin_id = 0;
	var $custom_element = 0;
    /** @var string custom field value */
	var $custom_title	= '';
    /** @var string custom Meta or alt  */
	var $custom_tip		= '';
    /** @var string custom Meta or alt  */
	var $custom_value	= '';
    /** @var string custom Meta or alt  */
	var $custom_desc	= '';

	/** @var string parameter of the customplugin*/
	var $custom_params				= 0;
	/**
	 *@var varchar(1)
	 * Type = S:string,I:int,P:parent, B:bool,D:date,T:time,H:hidden
	 */
	var $field_type= '';

	/** @var int(1)  1= Is this a list of value ? */
	var $is_list		= 0;
	var $is_input		= 0;
	/** @var int(1)  1= hidden field info */
	var $is_hidden		= 0;

	/** @var int(1)  1= cart attributes and price added to cart */
	var $is_cart_attribute		= 0;

	var $layout_pos = '';

	/** @var int custom published or not */
	var $published		= 1;
	/** @var int listed Order */
	var $ordering	= 0;
	/** @var int show title or not */
	var $show_title		= 1;


	/**
	 * @author  Patrick Kohl
	 * @param JDataBase $db
	 */
	function __construct(&$db) {
		parent::__construct('#__tsmart_customs', 'tsmart_custom_id', $db);

		$this->_cidName = 'tsmart_custom_id';

		$this->setUniqueName('custom_title');
		$this->setObligatoryKeys('field_type');

		$this->setLoggable();
		$this->setOrderable('ordering',false);
		$this->setParameterable('custom_params',array());
	}

	/*
	* field from 3 table have to be checked at delete
	* #__vm_custom_field,#__tsmart_customs,#__tsmart_product_customfields
	*/
	function delete( $id=null , $where = 0 ){

		$this->_db->setQuery('DELETE X,C FROM `#__tsmart_customs` AS C
			LEFT JOIN  `#__tsmart_product_customfields` AS X ON  X.`tsmart_custom_id` = C.`tsmart_custom_id`
			WHERE C.`tsmart_custom_id`=' . $id);
		if ($this->_db->execute() === false) {
			vmError($this->_db->getError());
			return false;
		}
		return true;
	}

}
// pure php no closing tag
