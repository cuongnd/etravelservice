<?php
/**
*
* Currency View
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class TsmartViewpromotion extends tsmViewAdmin {

	function display_price()
	{
		parent::display('price');
	}
	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model_promotion_price = tmsModel::getModel();
		$app=JFactory::getApplication();
		$input=$app->input;


		$config = JFactory::getConfig();

        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
        $this->list_service_class = tsmserviceclass::get_list_service_class();



        $layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			$tsmart_product_id=$input->get('tsmart_product_id',0,'int');
			$this->tsmart_product_id=$tsmart_product_id;
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';
			$this->list_group_size_by_tour_id=tsmprice::get_list_group_size_by_tour_id($tsmart_product_id);




			$cid	= vRequest::getInt( 'cid' );
			$model_product = tmsModel::getModel('product');
			$this->product=$model_product->getProduct($this->tsmart_product_id,false,false,false);
			if($this->product->tour_methor=='tour_group')
			{
				$this->list_tour_price_by_tour_price_id=tsmprice::get_list_tour_price_by_tour_price_id($cid[0]);
			}else{
				$this->tour_private_price_by_tour_price_id=tsmprice::get_list_tour_price_by_tour_price_id_for_price($cid[0]);
			}

			$task = vRequest::getCmd('task', 'add');

			if($task!='add' && !empty($cid) && !empty($cid[0])){
				$cid = (int)$cid[0];
			} else {
				$cid = 0;
			}

			$model_promotion_price->setId($cid);
			$this->price = $model_promotion_price->get_promotion_price();
			$model_product = tmsModel::getModel('product');
			$this->list_tour = $model_product->getProductListing();
			$this->SetViewTitle('',$this->promotion->promotion_name);
			$this->addStandardEditViewCommands();

		} else {
            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';

            $tsmart_product_id=$input->get('tsmart_product_id',0,'int');
            $this->tsmart_product_id=$tsmart_product_id;

            $model_product = tmsModel::getModel('product');
			$this->list_tour = $model_product->getItems();

            $this->product=$model_product->getItem($this->tsmart_product_id);
            $tsmart_price_id=$input->get('tsmart_price_id',0,'int');
			$model_promotion_price->setId($tsmart_price_id);
            $this->price = $model_promotion_price->get_promotion_price();

            //get markup
            $this->list_mark_up=tsmprice::get_list_mark_up_by_tour_price_id($tsmart_price_id);
            $this->list_mark_up=JArrayHelper::pivot($this->list_mark_up,'type');
            //end get markup
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';
			$this->list_group_size_by_tour_id=tsmprice::get_list_group_size_by_tour_id($tsmart_product_id);
			$this->SetViewTitle();
			$this->addStandardDefaultViewLists($model_promotion_price,0,'ASC');
            $this->addStandardDefaultViewCommandspromotion();
            $model_tourclass = tmsModel::getModel('tourclass');
            $this->list_service_class_by_tour_id=$model_tourclass->getItems();

			$this->promotion_prices = $model_promotion_price->get_list_promotion_price($tsmart_product_id);
            //$this->prices=JArrayHelper::pivot($this->prices,'service_class_name');

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
