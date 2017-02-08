<?php
/**
*
* Currency controller
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
* @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerpromotion extends TsmController {

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
        $model_price = tmsModel::getModel('promotion');
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
        $model_price = tmsModel::getModel('promotion');
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
        $tsmart_promotion_price_id=$input->get('tsmart_promotion_price_id',0,'int');
        $model_promotion_price = tmsModel::getModel('promotion');
        $post->tsmart_product_id=$post->tsmart_product_id;
        $post=(array)$post;
        $return_ajax=new stdClass();
        $return_ajax->e=0;
        $tsmart_promotion_price_id=$model_promotion_price->store($post);
        if(!$tsmart_promotion_price_id)
        {
            $return_ajax->e=1;
            $return_ajax->m=$model_promotion_price->getErrors();
            echo json_encode($return_ajax);
            die;
        }
        $promotion_item=$model_promotion_price->get_promotion_price($tsmart_promotion_price_id);
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
        $tsmart_product_id=$input->get('tsmart_product_id',0,'int');
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
        $tsmart_service_class_ids=tsmserviceclass::get_list_service_class_ids_by_tour_id($tsmart_product_id);
        $return_item=new stdClass();
        $return_item->tsmart_service_class_ids=$tsmart_service_class_ids;
        $view = &$this->getView('promotion', 'html', 'tsmartView');
        $modal_promotion=$this->getModel('promotion');
        $view->setModel($modal_promotion,true);
        $model_product=$this->getModel('product');
        $product=$model_product->getItem($tsmart_product_id);
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpromotion.php';
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
        if($product->price_type!=tsmGroupSize::FLAT_PRICE)
        {
            $list_tour_promotion_price_by_tour_promotion_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_promotion_price_id($tsmart_product_id);
            $return_item->list_tour_promotion_price_by_tour_promotion_price_id=$list_tour_promotion_price_by_tour_promotion_price_id;
            $view->assignRef('list_tour_price_by_tour_promotion_price_id',$list_tour_promotion_price_by_tour_promotion_price_id);
            $list_group_size_by_tour_id=vmpromotion::get_list_group_size_by_tour_id($tsmart_product_id);
            $view->assignRef('list_group_size_by_tour_id',$list_group_size_by_tour_id);
            $return_item->list_group_size_by_tour_id=$list_group_size_by_tour_id;
        }else{
            $tour_private_price_by_tour_promotion_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_price_id_for_promotion_price($tsmart_product_id);
            $return_item->tour_private_price_by_tour_promotion_price_id=$tour_private_price_by_tour_promotion_price_id;
            $view->assignRef('tour_private_price_by_tour_promotion_price_id',$tour_private_price_by_tour_promotion_price_id);


            $list_group_size_by_tour_id=vmpromotion::get_list_group_size_by_tour_id($tsmart_product_id);
            $view->assignRef('list_group_size_by_tour_id',$list_group_size_by_tour_id);
            $return_item->list_group_size_by_tour_id=$list_group_size_by_tour_id;


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
        $tsmart_product_id=$input->getInt('tsmart_product_id',0);
        $tsmart_service_class_id=$input->getInt('tsmart_service_class_id',0);
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';
        $list_base_price_by_service_class_id_and_tour_id=tsmprice::get_list_base_price_by_service_class_id_and_tour_id($tsmart_product_id,$tsmart_service_class_id);
        echo json_encode($list_base_price_by_service_class_id_and_tour_id);
        die;

    }
    public function get_list_promotion_price()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $view = &$this->getView('promotion', 'html', 'tsmartView');
        $tsmart_promotion_price_id=$input->get('tsmart_promotion_price_id',0,'int');
        $model_promotion_price = tmsModel::getModel('promotion');
        $model_promotion_price->setId($tsmart_promotion_price_id);
        $promotion_price = $model_promotion_price->get_promotion_price();
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpromotion.php';
        $product=vmpromotion::get_product_by_promotion_price_id($tsmart_promotion_price_id);
        $tsmart_product_id=$product->tsmart_product_id;


        $return_item=new stdClass();

        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
        $list_service_class=tsmserviceclass::get_list_service_class_by_tour_id($tsmart_product_id);
        $return_item->list_service_class=$list_service_class;

        $return_item->promotion_price=$promotion_price;
        $view->assignRef('promotion_price',$return_item->promotion_price);
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpromotion.php';


        $model_product = tmsModel::getModel('product');
        $product=$model_product->getItem($tsmart_product_id);
        $return_item->tour=$product;
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpromotion.php';

        $product=vmpromotion::get_product_by_promotion_price_id($tsmart_promotion_price_id);
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
        if($product->price_type!=tsmGroupSize::FLAT_PRICE)
        {
            $return_item->list_tour_promotion_price_by_tour_promotion_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_promotion_price_id($tsmart_promotion_price_id);
            $view->assignRef('list_tour_promotion_price_by_tour_promotion_price_id',$return_item->list_tour_promotion_price_by_tour_promotion_price_id);
        }else{
            $return_item->tour_private_price_by_tour_promotion_price_id=vmpromotion::get_list_tour_promotion_price_by_tour_price_id_for_promotion_price($tsmart_promotion_price_id);
            $view->assignRef('tour_private_price_by_tour_promotion_price_id',$return_item->tour_private_price_by_tour_promotion_price_id);
        }

        //get markup
        $return_item->list_promotion_mark_up=vmpromotion::get_list_mark_up_by_tour_promotion_price_id($tsmart_promotion_price_id);
        $return_item->list_promotion_mark_up=is_array($return_item->list_promotion_mark_up)?$return_item->list_promotion_mark_up:array($return_item->list_promotion_mark_up);
        $return_item->list_promotion_mark_up=JArrayHelper::pivot($return_item->list_promotion_mark_up,'type');
        $view->assignRef('list_promotion_mark_up',$return_item->list_promotion_mark_up);
        //end get markup
        //get markup
        $return_item->list_promotion=vmpromotion::get_list_promotion_by_tour_promotion_price_id($tsmart_promotion_price_id);


        $return_item->list_promotion=is_array($return_item->list_promotion)?$return_item->list_promotion:array($return_item->list_promotion);
        $return_item->list_promotion=JArrayHelper::pivot($return_item->list_promotion,'type');
        $view->assignRef('list_promotion',$return_item->list_promotion);
        //end get markup
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmprice.php';
        $return_item->list_group_size_by_tour_id=vmpromotion::get_list_group_size_by_tour_id($tsmart_product_id);
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
