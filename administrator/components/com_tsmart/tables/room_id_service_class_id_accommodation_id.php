<?php
/**
*
* Currency table
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currencies.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');

/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		tsmart
 * @author RickG, Max Milbers
 */
class Tableroom_id_service_class_id_accommodation_id extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_accommodation_id			= 0;
	var $tsmart_room_id				= 0;
	var $tsmart_service_class_id				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
        $keys=(object)array(
           'tsmart_accommodation_id','tsmart_room_id','tsmart_service_class_id'
        );
		parent::__construct('#__tsmart_room_id_service_class_id_accommodation_id', 'id', $db);

	}

	function check(){

		//$this->checkCurrencySymbol();
		return parent::check();
	}

	/**
	 * ATM Unused !
	 * Checks a departure symbol wether it is a HTML entity.
	 * When not and $convertToEntity is true, it converts the symbol
	 * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
	 *
	 */
}
// pure php no closing tag
