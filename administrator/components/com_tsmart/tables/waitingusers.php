<?php
/**
*
* Currency table
*
* @package	tsmart
* @subpackage Currency
* @author Seyi Awofadeju
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currencies.php 3256 2011-05-15 20:04:08Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable')) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * WaitingUsers table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		tsmart
 * @author Seyi Awofadeju
 */
class TableWaitingUsers extends tsmTable {

	var $tsmart_waitinguser_id	= 0;
	var $tsmart_product_id		= 0;
	var $tsmart_user_id        	= 0;
	var $notify_email				= '';
	var $notified         			= 0;
	var $notify_date 				= '';
    var $ordering					= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db) {
		parent::__construct('#__tsmart_waitingusers', 'tsmart_waitinguser_id', $db);
		$this->setLoggable();

	}

	function check() {
		if(empty($this->notify_email) || !filter_var($this->notify_email, FILTER_VALIDATE_EMAIL)) {
			vmError(tsmText::_('com_tsmart_ENTER_A_VALID_EMAIL_ADDRESS'),tsmText::_('com_tsmart_ENTER_A_VALID_EMAIL_ADDRESS'));
			return false;
		}
		return parent::check();
	}

}
// pure php no closing tag
