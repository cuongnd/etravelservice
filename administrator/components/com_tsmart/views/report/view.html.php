<?php
if( !defined( '_JEXEC' ) ) die('Restricted access');

/**
*
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
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

if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * Report View class
 *
 * @package	tsmart
 * @subpackage Report
 * @author Wicksj
 */
class TsmartViewReport extends tsmViewAdmin {

	/**
	 * Render the view
	 */
	function display($tpl = null){

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');
		if (!class_exists('CurrencyDisplay'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');


		$model		= tmsModel::getModel();

		vRequest::setvar('task','');

		$this->SetViewTitle('REPORT');

		$myCurrencyDisplay = CurrencyDisplay::getInstance();

		//update order items button
		/*$q = 'SELECT * FROM #__tsmart_order_items WHERE `product_discountedPriceWithoutTax` IS NULL ';
		$db = JFactory::getDBO();
		$db->setQuery($q);
		$res = $db->loadRow();
		if($res) {
			JToolBarHelper::custom('updateOrderItems', 'new', 'new', vmText::_('com_tsmart_REPORT_UPDATEORDERITEMS'),false);
			vmError('com_tsmart_REPORT_UPDATEORDERITEMS_WARN');
		}*/

		$this->addStandardDefaultViewLists($model);
		$revenueBasic = $model->getRevenue();

		if($revenueBasic){
			$totalReport['revenueTotal_brutto']= $totalReport['revenueTotal_netto']= $totalReport['number_of_ordersTotal'] = $totalReport['itemsSoldTotal'] = 0 ;
			foreach($revenueBasic as &$j){
				vmdebug('TsmartViewReport revenue',$j);
				$totalReport['revenueTotal_netto'] += $j['order_subtotal_netto'];
				$totalReport['revenueTotal_brutto'] += $j['order_subtotal_brutto'];
				$totalReport['number_of_ordersTotal'] += $j['count_order_id'];
				$j['order_subtotal_netto'] = $myCurrencyDisplay->priceDisplay($j['order_subtotal_netto']);
				$j['order_subtotal_brutto'] = $myCurrencyDisplay->priceDisplay($j['order_subtotal_brutto']);
				//$j['product_quantity'] = $model->getItemsByRevenue($j);
				$totalReport['itemsSoldTotal'] +=$j['product_quantity'];
			}
			$totalReport['revenueTotal_netto'] = $myCurrencyDisplay->priceDisplay($totalReport['revenueTotal_netto']);
			$totalReport['revenueTotal_brutto'] = $myCurrencyDisplay->priceDisplay($totalReport['revenueTotal_brutto']);
			// if ( 'product_quantity'==vRequest::getCmd('filter_order')) {
				// foreach ($revenueBasic as $key => $row) {
					// $created_on[] =$row['created_on'];
					// $intervals[] =$row['intervals'];
					// $itemsSold[] =$row['product_quantity'];
					// $number_of_orders[] =$row['count_order_id'];
					// $revenue[] =$row['revenue'];

				// }
				// if (vRequest::getCmd('filter_order_Dir') == 'desc') array_multisort($itemsSold, SORT_DESC,$revenueBasic);
				// else array_multisort($itemsSold, SORT_ASC,$revenueBasic);
			// }
		}
		$this->assignRef('report', $revenueBasic);
		$this->assignRef('totalReport', $totalReport);


		$orderstatusM =tmsModel::getModel('orderstatus');
		$this->lists['select_date'] = $model->renderDateSelectList();
		$orderstates = vRequest::getVar ('order_status_code', array('C','S'));
		$this->lists['state_list'] = $orderstatusM->renderOSList($orderstates,'order_status_code',TRUE);
		$this->lists['intervals'] = $model->renderIntervalsList();
		$this->assignRef('from_period', $model->from_period);
		$this->assignRef('until_period', $model->until_period);

		$this->pagination = $model->getPagination();

		parent::display($tpl);
	}
}
