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

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Report Controller
 *
 * @package	tsmart
 * @subpackage Report
 * @author Wicksj
 */
class TsmartControllerReport extends TsmController {

	/**
	 * Report Controller Constructor
	 */
	function __constuct(){
		parent::__construct();
	}
	
	function updateOrderItems(){

		vRequest::vmCheckToken();

		$model = tmsModel::getModel('report');
		$model->updateOrderItems();
		$this->setRedirect($this->redirectPath, 'Order Items updated');
	}

}
// pure php no closing tag