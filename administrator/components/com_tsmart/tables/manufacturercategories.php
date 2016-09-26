<?php
/**
*
* Manufacturer Category table
*
* @package	tsmart
* @subpackage Manufacturer category
* @author Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: manufacturercategories.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Manufacturer category table class
 * The class is used to manage the manufacturer category in the shop.
 *
 * @package		tsmart
 * @author Patrick Kohl
 */
class TableManufacturercategories extends tsmTable {

	/** @var int Primary key */
	var $tsmart_manufacturercategories_id = 0;
	/** @var string manufacturer category name */
	var $mf_category_name = '';
	/** @var string manufacturer category description */
	var $mf_category_desc = '';
	/** @var int published or unpublished */
	var $published = 1;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_manufacturercategories', 'tsmart_manufacturercategories_id', $db);

		$this->setUniqueName('mf_category_name');

		$this->setLoggable();
		$this->setTranslatable(array('mf_category_name','mf_category_desc'));
		$this->setSlug('mf_category_name');
	}


	/*
	 * Verify that user have to delete all manufacturers of a particular category before that category can be removed
	 *
	 * @return boolean True if category is ready to be removed, otherwise False
	 */
	function checkManufacturer($categoryId = 0)
	{
		if($categoryId > 0) {
			$db = JFactory::getDBO();

			$q = 'SELECT count(*)'
				.' FROM #__tsmart_manufacturers'
				.' WHERE tsmart_manufacturercategories_id = '.$categoryId;
			$db->setQuery($q);
			$mCount = $db->loadResult();

			if($mCount > 0) {
				vmInfo('com_tsmart_REMOVE_IN_USE');
				return false;
			}

		}
		return true;
	}

}
// pure php no closing tag
