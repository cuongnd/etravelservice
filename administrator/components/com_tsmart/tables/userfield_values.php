<?php
/**
*
* Userfield Values table
*
* @package	tsmart
* @subpackage Userfields
* @author Oscar van Eijk
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: userfield_values.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Userfields table class
 * The class is used to manage the values for select-type userfields in the shop.
 *
 * @package	tsmart
 * @author Oscar van Eijk
 */
class TableUserfield_values extends tsmTable {

	/** @var int Primary key */
	var $tsmart_userfield_value_id	= 0;
	/** @var int Reference to the userfield */
	var $tsmart_userfield_id		= 0;
	/** @var string Label of the value */
	var $fieldtitle		= null;
	/** @var string Selectable value */
	var $fieldvalue		= null;
	/** @var int Value ordering */
	var $ordering		= 0;
	/** @var boolean True if part of the tsmart installation; False for User specified*/
	var $sys			= 0;
         /** @var boolean */
	var $locked_on	= 0;
	/** @var time */
	var $locked_by	= 0;
	/**
	 * @param $db Class constructor; connect to the database
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_userfield_values', 'tsmart_userfield_value_id', $db);
		$this->setPrimaryKey('tsmart_userfield_id');

	}

	/**
	 * Validates the userfields record fields, and checks if the given value already exists.
	 * If so, the primary key is set.
	 *
	 * @return boolean True if the table buffer is contains valid data, false otherwise.
	 */
	function check()
	{
		if (preg_match('/[^a-z0-9\._\-]/i', $this->fieldvalue) > 0) {
			vmError(tsmText::_('com_tsmart_TITLE_IN_FIELDVALUES_CONTAINS_INVALID_CHARACTERS'));
			return false;
		}

		$db = JFactory::getDBO();
		$q = 'SELECT `tsmart_userfield_value_id` FROM `#__tsmart_userfield_values` '
			. 'WHERE `fieldvalue`="' . $this->fieldvalue . '" '
			. 'AND   `tsmart_userfield_id`=' . $this->tsmart_userfield_id;
		$db->setQuery($q);
		$_id = $db->loadResult();
		if ($_id === null) {
			$this->tsmart_userfield_value_id = null;
		} else {
			$this->tsmart_userfield_value_id = $_id;
		}
		return true;
	}

	/**
	 * Reimplement delete() to get a list if value IDs based on the field id
	 * @var Field id
	 * @return boolean True on success
	 */
	function delete( $tsmart_userfield_id=null , $where = 0 ){

		$db = JFactory::getDBO();
		$db->setQuery('DELETE from `#__tsmart_userfield_values` WHERE `tsmart_userfield_id` = ' . $tsmart_userfield_id);
		if ($db->execute() === false) {
			vmError($db->getError());
			return false;
		}
		return true;
	}
}

//No CLosing Tag
