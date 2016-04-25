<?php
/**
*
* Currency View
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class VirtuemartViewpromotion extends VmViewAdmin {

	function display_price()
	{
		parent::display('price');
	}
	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model_promotion_price = VmModel::getModel();
		$app=JFactory::getApplication();
		$input=$app->input;


		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			$tour_id=$input->get('virtuemart_product_id',0,'int');
			$this->virtuemart_product_id=$tour_id;
			require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
			$this->list_group_size_by_tour_id=vmprice::get_list_group_size_by_tour_id($tour_id);




			$cid	= vRequest::getInt( 'cid' );
			$model_product = VmModel::getModel('product');
			$this->product=$model_product->getProduct($this->virtuemart_product_id,false,false,false);
			if($this->product->tour_methor=='tour_group')
			{
				$this->list_tour_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id($cid[0]);
			}else{
				$this->tour_private_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id_for_price($cid[0]);
			}

			$task = vRequest::getCmd('task', 'add');

			if($task!='add' && !empty($cid) && !empty($cid[0])){
				$cid = (int)$cid[0];
			} else {
				$cid = 0;
			}

			$model_promotion_price->setId($cid);
			$this->price = $model_promotion_price->get_promotion_price();
			$model_product = VmModel::getModel('product');
			$this->list_tour = $model_product->getProductListing();
			$this->SetViewTitle('',$this->promotion->promotion_name);
			$this->addStandardEditViewCommands();

		} else {
            require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';

            $tour_id=$input->get('virtuemart_product_id',0,'int');
            $this->virtuemart_product_id=$tour_id;

            $model_product = VmModel::getModel('product');
			$this->list_tour = $model_product->getItems();

            $this->product=$model_product->getItem($this->virtuemart_product_id);
            $virtuemart_price_id=$input->get('virtuemart_price_id',0,'int');
			$model_promotion_price->setId($virtuemart_price_id);
            $this->price = $model_promotion_price->get_promotion_price();

            //get markup
            $this->list_mark_up=vmprice::get_list_mark_up_by_tour_price_id($virtuemart_price_id);
            $this->list_mark_up=JArrayHelper::pivot($this->list_mark_up,'type');
            //end get markup
			require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
			$this->list_group_size_by_tour_id=vmprice::get_list_group_size_by_tour_id($tour_id);
			$this->SetViewTitle();
			$this->addStandardDefaultViewLists($model_promotion_price,0,'ASC');
            $this->addStandardDefaultViewCommandspromotion();
            $model_tourclass = VmModel::getModel('tourclass');
            $this->list_service_class_by_tour_id=$model_tourclass->getItems();

			$this->promotion_prices = $model_promotion_price->getpromotionPricesList($tour_id);
            //$this->prices=JArrayHelper::pivot($this->prices,'service_class_name');

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
