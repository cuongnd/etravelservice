<?php
/**
 *
 * Data module for shop general
 *
 * @package	VirtueMart
 * @subpackage general
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: general.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\Registry\Registry;
if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop general
 *
 * @package	VirtueMart
 * @subpackage general
 */
class VirtueMartModelGeneral extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('general');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('*')
			->from('#__virtuemart_general')
			;
		$item=$db->setQuery($query)->loadObject();
		$params = new Registry;
		$params->loadString($item->params);
		$item->params=$params;

		return $item;
	}


	/**
	 * Retireve a list of general from the database.
	 *
	 * This is written to get a list for selecting general. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of general objects
	 */

	function store(&$data){
		if(!vmAccess::manager('general')){
			vmWarn('Insufficient permissions to store general');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('general')){
			vmWarn('Insufficient permissions to remove general');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
