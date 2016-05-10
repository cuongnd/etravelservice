<?php
/**
*
* Currency controller
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
* @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Currency Controller
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class VirtuemartControllerpromotion extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct();


	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = vRequest::getRequest();

		parent::save($data);
	}
    public function ajax_remove()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $price_id=$input->get('price_id',0,'int');
        $tour_methor=$input->get('tour_methor','','string');
        $model_price = VmModel::getModel('promotion');
        if(!$model_price->remove(array($price_id)))
        {
            echo 'cannot delete item';
            die;
        }
        echo 1;
        die;

    }
    public function ajax_publish()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $price_id=$input->get('price_id',0,'int');
        $tour_methor=$input->get('tour_methor','','string');
        $model_price = VmModel::getModel('promotion');
        if(!$model_price->toggle('published',null,'price_id','promotion'))
        {
            echo 'cannot published item';
            die;
        }
        echo 1;
        die;

    }
    public function ajax_save_promotion_price()
    {
        $app=JFactory::getApplication();
        $post = file_get_contents('php://input');
        $post = json_decode($post);
        $input=$app->input;
        $virtuemart_promotion_price_id=$input->get('virtuemart_promotion_price_id',0,'int');
        $model_promotion_price = VmModel::getModel('promotion');
        $post->virtuemart_product_id=$post->select_virtuemart_product_id;
        $post=(array)$post;
        $return_ajax=new stdClass();
        $return_ajax->e=0;
        $virtuemart_promotion_price_id=$model_promotion_price->store($post);
        if(!$virtuemart_promotion_price_id)
        {
            $return_ajax->e=1;
            $return_ajax->m='cannot save item';
            echo json_encode($return_ajax);
            die;
        }
        $promotion_item=$model_promotion_price->get_promotion_price($virtuemart_promotion_price_id);
        $promotion_item->sale_period=JHtml::_('date', $promotion_item->sale_period_from, 'd M. Y').'-'.JHtml::_('date', $promotion_item->sale_period_to, 'd M. Y');
        $promotion_item->modified_on=JHtml::_('date', $promotion_item->modified_on, 'd M. Y');
        $return_ajax->r=$promotion_item;
        echo json_encode($return_ajax);
        die;

    }

    function ajax_get_change_tour()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $tour_id=$input->get('tour_id',0,'int');
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmserviceclass.php';
        $virtuemart_service_class_ids=vmServiceclass::get_list_service_class_ids_by_tour_id($tour_id);
        $return_item=new stdClass();
        $return_item->virtuemart_service_class_ids=$virtuemart_service_class_ids;
        $view = &$this->getView('promotion', 'html', 'VirtuemartView');
        $modal_promotion=$this->getModel('promotion');
        $view->setModel($modal_promotion,true);
        $model_product=$this->getModel('product');
        $product=$model_product->getItem($tour_id);
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmpromotion.php';
        if($product->price_type!='flat_price')
        {
            $list_tour_price_by_tour_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_promotion_price_id($tour_id);
            $view->assignRef('list_tour_price_by_tour_price_id',$list_tour_price_by_tour_price_id);
            $list_group_size_by_tour_id=vmpromotion::get_list_group_size_by_tour_id($tour_id);
            $view->assignRef('list_group_size_by_tour_id',$list_group_size_by_tour_id);
        }else{
            $tour_private_price_by_tour_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_price_id_for_promotion_price($tour_id);
            $view->assignRef('tour_private_price_by_tour_price_id',$tour_private_price_by_tour_price_id);
        }

        ob_start();
        $input->set('tpl','price');
        $view->assignRef('product',$product);
        $view->display_price();
        $price_content = ob_get_contents();
        ob_end_clean(); // get the callback function
        $return_item->price_content= $price_content;
        $return_item->tour= $product;
        echo json_encode($return_item);
        die;

    }
    public function ajax_get_list_base_price_by_service_class_id_and_tour_id(){
        $app=JFactory::getApplication();
        $input=$app->input;
        $tour_id=$input->getInt('tour_id',0);
        $virtuemart_service_class_id=$input->getInt('virtuemart_service_class_id',0);
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
        $list_base_price_by_service_class_id_and_tour_id=vmprice::get_list_base_price_by_service_class_id_and_tour_id($tour_id,$virtuemart_service_class_id);
        echo json_encode($list_base_price_by_service_class_id_and_tour_id);
        die;

    }
    public function get_list_price()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $view = &$this->getView('promotion', 'html', 'VirtuemartView');
        $price_id=$input->get('price_id',0,'int');
        $model_promotion_price = VmModel::getModel('promotion');
        $model_promotion_price->setId($price_id);
        $promotion_price = $model_promotion_price->get_promotion_price();

        $tour_id=$app->input->get('tour_id',0,'int');


        $return_item=new stdClass();

        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmserviceclass.php';
        $virtuemart_service_class_ids=vmServiceclass::get_list_service_class_ids_by_tour_id($tour_id);
        $return_item->virtuemart_service_class_ids=$virtuemart_service_class_ids;

        $return_item->promotion_price=$promotion_price;
        $view->assignRef('promotion_price',$return_item->promotion_price);
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmpromotion.php';

        $tour_id=$input->get('tour_id',0,'int');
        $this->virtuemart_product_id=$tour_id;

        $model_product = VmModel::getModel('product');
        $product=$model_product->getItem($tour_id);
        $return_item->tour=$product;
        if($product->tour_methor=='tour_group')
        {
            $return_item->list_tour_promotion_price_by_tour_promotion_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_promotion_price_id($price_id);
            $view->assignRef('list_tour_promotion_price_by_tour_promotion_price_id',$return_item->list_tour_promotion_price_by_tour_promotion_price_id);
        }else{
            $return_item->tour_private_price_by_tour_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_price_id_for_promotion_price($price_id);
            $view->assignRef('tour_private_price_by_tour_price_id',$return_item->tour_private_price_by_tour_price_id);
        }

        //get markup
        $return_item->list_promotion_mark_up=vmpromotion::get_list_mark_up_by_tour_promotion_price_id($price_id);
        $return_item->list_promotion_mark_up=is_array($return_item->list_promotion_mark_up)?$return_item->list_promotion_mark_up:array($return_item->list_promotion_mark_up);
        $return_item->list_promotion_mark_up=JArrayHelper::pivot($return_item->list_promotion_mark_up,'type');
        $view->assignRef('list_promotion_mark_up',$return_item->list_promotion_mark_up);
        //end get markup
        //get markup
        $return_item->list_promotion=vmpromotion::get_list_promotion_by_tour_promotion_price_id($price_id);


        $return_item->list_promotion=is_array($return_item->list_promotion)?$return_item->list_promotion:array($return_item->list_promotion);
        $return_item->list_promotion=JArrayHelper::pivot($return_item->list_promotion,'type');
        $view->assignRef('list_promotion',$return_item->list_promotion);
        //end get markup
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
        $return_item->list_group_size_by_tour_id=vmpromotion::get_list_group_size_by_tour_id($tour_id);
        $view->assignRef('list_group_size_by_tour_id',$return_item->list_group_size_by_tour_id);
        ob_start();
        $input->set('tpl','price');
        $view->assignRef('product',$product);
        $view->display_price();
        $price_content = ob_get_contents();
        $return_item->price_content=$price_content;
        ob_end_clean(); // get the callback function
        echo json_encode($return_item);
        die;






    }
    public function save_price()
    {
    }
}
// pure php no closing tag
