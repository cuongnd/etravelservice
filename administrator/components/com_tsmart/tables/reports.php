<?php
if( !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

/**
*
* @version
* @package tsmart
* @subpackage Report
* @copyright Copyright (C) tsmart Team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
*
* http://tsmart.org
*/

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Report table class
 *
 * @package	tsmart
 * @subpackage Report
 * @author Wicksj
 */
class TableReports extends tsmTable {

	/**
	 * Constructor for report table class
	 * @param $db Database connector object
	 */
	function __construct(&$db){
		parent::__construct('#__tsmart_orders', 'tsmart_order_id', $db);
	}
}
// pure php no closing tag