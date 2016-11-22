<?php
/**
*
* State table
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
* @version $Id: states.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * State table class
 * The class is is used to manage the states in a country
 *
 * @package		tsmart
 * @author RickG
 */
class TableStates extends tsmTable {

	/** @var int Primary key */
	var $tsmart_state_id				= 0;
	/** @var integer Country id */
	var $tsmart_country_id           	= 0;
	/** @var integer Zone id */
	/** @var string State name */
	var $state_name           	= '';
	var $phone_code           	= '';
	var $iso_alpha2           	= '';
	var $zip_code           	= '';
	var $flag 	          	= '';
	var $state_abbr 	          	= '';
	/** @var char 3 character state code */
	var $state_3_code         	= '';
    /** @var char 2 character state code */
	var $state_2_code         	= '';
	/** @var int published or unpublished */
	var $published         		= 1;


	/**
	 * @author RickG
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_states', 'tsmart_state_id', $db);

		$this->setUniqueName('state_name');

		$this->setLoggable();
	}

}
// pure php no closing tag
