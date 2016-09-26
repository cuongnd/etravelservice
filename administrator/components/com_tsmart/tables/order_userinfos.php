<?php
/**
 *
 * Order table holding user info
 *
 * @package	tsmart
 * @subpackage Orders
 * @author 	Oscar van Eijk
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: order_userinfos.php 8310 2014-09-21 17:51:47Z Milbo $
 */

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class TableOrder_userinfos extends tsmTableData {

    /**
     * @author Max Milbers
     * @param string $_db
     */
    function __construct(&$_db){
		parent::__construct('#__tsmart_order_userinfos', 'tsmart_order_userinfo_id', $_db);
		parent::showFullColumns();
		$this->setLoggable();
	}

}
// No closing tag