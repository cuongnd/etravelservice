<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage
* @author
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8850 2015-05-13 14:06:11Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');


/**
 * HTML View class for the tsmart Component
 *
 * @package		tsmart
 * @author
 */
class TsmartViewTsmart extends tsmViewAdmin {

	function display($tpl = null) {
		if (!class_exists('VmImage'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');
		tsmConfig::loadJLang('com_tsmart_orders',TRUE);


		if(JFactory::getApplication()->isSite()){
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
		}

		$layout = $this->getLayout();

		if($this->manager('report')){
			vmSetStartTime('report');

			$model = tmsModel::getModel('tsmart');

			$nbrCustomers = $model->getTotalCustomers();
			$this->nbrCustomers=$nbrCustomers;

			$nbrActiveProducts = $model->getTotalActiveProducts();
			$this->nbrActiveProducts= $nbrActiveProducts;
			$nbrInActiveProducts = $model->getTotalInActiveProducts();
			$this->nbrInActiveProducts= $nbrInActiveProducts;
			$nbrFeaturedProducts = $model->getTotalFeaturedProducts();
			$this->nbrFeaturedProducts= $nbrFeaturedProducts;

			$ordersByStatus = $model->getTotalOrdersByStatus();
			$this->ordersByStatus= $ordersByStatus;

			$recentOrders = $model->getRecentOrders();
			if(!class_exists('CurrencyDisplay'))require(VMPATH_ADMIN.DS.'helpers'.DS.'currencydisplay.php');

			/* Apply currency This must be done per order since it's vendor specific */
			$_currencies = array(); // Save the currency data during this loop for performance reasons
			foreach ($recentOrders as $tsmart_order_id => $order) {

				//This is really interesting for multi-X, but I avoid to support it now already, lets stay it in the code
				if (!array_key_exists('v'.$order->tsmart_vendor_id, $_currencies)) {
					$_currencies['v'.$order->tsmart_vendor_id] = CurrencyDisplay::getInstance('',$order->tsmart_vendor_id);
				}
				$order->order_total = $_currencies['v'.$order->tsmart_vendor_id]->priceDisplay($order->order_total);
			}
			$this->recentOrders= $recentOrders;
			$recentCustomers = $model->getRecentCustomers();
			$this->recentCustomers=$recentCustomers;

			$reportModel		= tmsModel::getModel('report');
			vRequest::setvar('task','');
			$myCurrencyDisplay = CurrencyDisplay::getInstance();
			$revenueBasic = $reportModel->getRevenue(60,true);
			$this->report = $revenueBasic['report'];

			vmJsApi::addJScript( "jsapi","//google.com/jsapi",false,false,'' );
			vmJsApi::addJScript('vm.stats_chart',$revenueBasic['js'],false,true);
			vmTime('Created report','report');
		}

		//if($layout=='default'){
			$j = 'jQuery("#feed").ready(function(){
				var datas = "";
				vmSiteurl = "'. JURI::root( ) .'"
				jQuery.ajax({
						type: "GET",
						async: true,
						cache: false,
						dataType: "json",
						url: vmSiteurl + "index.php?option=com_tsmart&view=tsmart&task=feed",
						data: datas,
						dataType: "html"
					})
					.done(function( data ) {
						jQuery("#feed").append(data);
					});
				})';
			vmJsApi::addJScript('getFeed',$j, false, true);
		//}

		self::showACLPref($this);

		parent::display($tpl);
	}


}

//pure php no tag