<?php
/**
 *
 * Data module for shop paymentsetting
 *
 * @package	VirtueMart
 * @subpackage paymentsetting
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: paymentsetting.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop paymentsetting
 *
 * @package	VirtueMart
 * @subpackage paymentsetting
 */
class VirtueMartModelpaymentsetting extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('paymentsetting');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		return $this->getData($id);
	}


	/**
	 * Retireve a list of paymentsetting from the database.
	 * This function is used in the backend for the paymentsetting listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of paymentsetting objects
	 */


	/**
	 * Retireve a list of paymentsetting from the database.
	 *
	 * This is written to get a list for selecting paymentsetting. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of paymentsetting objects
	 */

	function store(&$data){
		if(!vmAccess::manager('paymentsetting')){
			vmWarn('Insufficient permissions to store paymentsetting');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('paymentsetting')){
			vmWarn('Insufficient permissions to remove paymentsetting');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
