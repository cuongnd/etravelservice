<?php
/**
*
* vendor_user_xref table 
*
* @package	tsmart
* @subpackage vendor
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2015 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: vendor_users.php
*/

defined('_JEXEC') or die();

if(!class_exists('tsmTableXarray'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtablexarray.php');

class TableVendor_users extends tsmTableXarray {

	/**
	 * @param JDataBase $db database connector object
	 */
	function __construct(&$db){
		parent::__construct('#__tsmart_vendor_users', 'id', $db);

		$this->setPrimaryKey('tsmart_vendor_id');
		$this->setSecondaryKey('tsmart_user_id');
	}

}
