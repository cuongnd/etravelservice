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
class TsmartViewPrice extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();
		$app=JFactory::getApplication();
		$model_product=tmsModel::getModel('product');
		$input=$app->input;
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
		$tsmart_product_id=$app->input->get('tsmart_product_id',0,'int');
		$this->product=$model_product->getItem($tsmart_product_id);
		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';
			$this->list_group_size_by_tour_id=vmprice::get_list_group_size_by_tour_id($tsmart_product_id);

			if($this->product->tour_methor=='tour_group')
			{
				$this->list_tour_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id($tsmart_product_id);

			}else{
				$this->tour_private_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id_for_price($tsmart_product_id);
			}

			$task = vRequest::getCmd('task', 'add');

			if($task!='add' && !empty($cid) && !empty($cid[0])){
				$cid = (int)$cid[0];
			} else {
				$cid = 0;
			}

			$model->setId($cid);
			$this->price = $model->getPrice();
			$model_product = tmsModel::getModel('product');
			$this->list_tour = $model_product->getProductListing();
			$this->SetViewTitle('',$this->departure->departure_name);
			$this->addStandardEditViewCommands();

		} else {
            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';

            $tour_id=$input->get('tsmart_product_id',0,'int');
            $this->tsmart_product_id=$tour_id;

            $this->product=$model_product->getItem($tsmart_product_id);
            $this->price = $model->getPrice();

/*            //get markup
            $this->list_mark_up=vmprice::get_list_mark_up_by_tour_price_id($tsmart_price_id);
            $this->list_mark_up=JArrayHelper::pivot($this->list_mark_up,'type');
            //end get markup*/
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';
			$this->list_group_size_by_tour_id=vmprice::get_list_group_size_by_tour_id($tour_id);
            if(!count($this->list_group_size_by_tour_id))
            {
				$app->enqueueMessage('there are no group size setup, please setup group size first');
				$app->redirect('index.php?option=com_tsmart&view=product&task=edit&tsmart_product_id='.$tsmart_product_id);
				return false;
                throw new Exception('there are no group size setup');
                return false;
            }
			$this->SetViewTitle();
			$this->addStandardDefaultViewCommandsPrice();
			$this->addStandardDefaultViewLists($model,0,'ASC');


            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
            $this->list_service_class_by_tour_id=tsmserviceclass::get_list_service_class_by_tour_id($tour_id);

			$this->prices = $model->getPricesListByTourid($tour_id);
            //$this->prices=JArrayHelper::pivot($this->prices,'service_class_name');

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
