<?php
/**
*
* Userfield table
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
* @version $Id: userfields.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Userfields table class
 * The class is used to manage the userfields in the shop.
 *
 * @package	tsmart
 * @author Oscar van Eijk
 */
class TableUserfields extends tsmTable {

// 	/** @var var Primary Key*/
	var $tsmart_userfield_id		= 0;

	/**
	 * @param $db Class constructor; connect to the database
	 */
	function __construct(&$db)
	{

		parent::__construct('#__tsmart_userfields', 'tsmart_userfield_id', $db);
		parent::showFullColumns();
		$this->setPrimaryKey('tsmart_userfield_id');
		$this->setUniqueName('name');
		$this->setObligatoryKeys('title');

		$this->setLoggable();

		$this->setOrderable('ordering',false);
		$this->_xParams = 'userfield_params';

	}

	/**
	 * Validates the userfields record fields.
	 *
	 * @return boolean True if the table buffer is contains valid data, false otherwise.
	 */
	function check()
	{

		if (preg_match('/[^a-z0-9\._\-]/i', $this->name) > 0) {
			vmError(tsmText::_('com_tsmart_NAME_OF_USERFIELD_CONTAINS_INVALID_CHARACTERS'));
			return false;
		}
		if($this->name !='tsmart_country_id' and $this->name !='tsmart_state_id'){
			$reqValues = array('select', 'multiselect', 'radio', 'multicheckbox');
			if (in_array($this->type, $reqValues) and $this->_nrOfValues == 0 ) {
				vmError(tsmText::_('com_tsmart_VALUES_ARE_REQUIRED_FOR_THIS_TYPE'));
				return false;
			}
		}


		return parent::check();
	}

	/**
	 * Format the field type
	 * @param $_data array array with additional data written to other tables
	 * @return string Field type in SQL syntax
	 */
	function formatFieldType(&$_data = array())
	{
		$_fieldType = $this->type;
		switch($this->type) {
			case 'date':
				$_fieldType = 'DATE';
				break;
			case 'editorta':
			case 'textarea':
			case 'multiselect':
			case 'multicheckbox':
				$_fieldType = 'MEDIUMTEXT';
				break;
			case 'checkbox':
				$_fieldType = 'TINYINT';
				break;

			case 'age_verification':
				//$this->params = 'minimum_age='.(int)$_data['minimum_age']."\n";
			default:
				$_fieldType = 'VARCHAR(255)';
				break;
		}

		return $_fieldType;
	}

	/**
	 * Reimplement the store method to return the last inserted ID
	 *
	 * @return mixed When a new record was succesfully inserted, return the ID, otherwise the status
	 */
	public function store($updateNulls = false)
	{
		$isNew = ($this->tsmart_userfield_id == 0);
		if (!parent::store($updateNulls)) { // Write data to the DB
			vmError($this->_db->getError());
			return false;
		} else {
			return $this->tsmart_userfield_id;
		}
	}
	
	function checkAndDelete($table, $whereField = 0, $andWhere = ''){
		$ok = 1;
		$k = $this->_tbl_key;

		if($whereField!==0){
			$whereKey = $whereField;
		} else {
			$whereKey = $this->_pkey;
		}
		
		$query = 'SELECT `'.$this->_tbl_key.'` FROM `'.$table.'` WHERE '.$whereKey.' = "' .$this->$k . '"';
		
		// stAn - it should be better to add this directly to the controller of the shopper fields
		// only additionally, controllers are not considered as safe.
		if (isset($this->name))
		 {
		    
			$umodel = tmsModel::getModel('userfields');
			$arr = $umodel->getCoreFields();
			if (in_array($this->name, $arr))
			 {
			  vmError('Cannot delete core field! Use unpublish');
			  return false; 
			 }
		 }

		$this->_db->setQuery( $query );
		$list = $this->_db->loadColumn();

		if($list){

			foreach($list as $row){
				$ok = $row;
				$query = 'DELETE FROM `'.$table.'` WHERE '.$this->_tbl_key.' = "'.$row.'"';
				$this->_db->setQuery( $query );

				if (!$this->_db->execute()){
					vmError('checkAndDelete '.$this->_db->getErrorMsg());
					$ok = 0;
				}
			}

		}
		return $ok;
	}
}

//No CLosing Tag