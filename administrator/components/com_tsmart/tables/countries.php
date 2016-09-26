<?php
/**
*
* Country table
*
* @package	tsmart
* @subpackage Country
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: countries.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Country table class
 * The class is is used to manage the countries in the shop.
 *
 * @package		tsmart
 * @author RickG
 */
class TableCountries extends tsmTable {

	/** @var int Primary key */
	var $tsmart_country_id				= 0;
	/** @var integer Zone id */
	var $tsmart_worldzone_id           		= 0;
	var $image           		= 0;
	/** @var string Country name */
	var $country_name           = '';
	/** @var char 3 character country code */
	var $country_3_code         = '';
    /** @var char 2 character country code */
	var $country_2_code         = '';
	var $code         = '';
	var $phone_code         = '';
	var $state_number         = '';
	var $flag         = '';
	var $ordering				= '';
    /** @var int published or unpublished */
	var $published 		        = 1;


	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_countries', 'tsmart_country_id', $db);


		$this->setLoggable();
		$this->setOrderable('ordering',false);
	}

}
// pure php no closing tag
