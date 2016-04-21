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
class VirtuemartControllerdeparture extends VmController {

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
        $model_price = VmModel::getModel('departure');
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
        $model_price = VmModel::getModel('departure');
        if(!$model_price->toggle('published',null,'price_id','departure'))
        {
            echo 'cannot published item';
            die;
        }
        echo 1;
        die;

    }
    public function ajax_save_departure_price()
    {
        $app=JFactory::getApplication();
        $post = file_get_contents('php://input');
        $post = json_decode($post);
        $input=$app->input;
        $virtuemart_departure_price_id=$input->get('virtuemart_departure_price_id',0,'int');
        $model_departure_price = VmModel::getModel('departure');
        $post->virtuemart_product_id=$post->select_virtuemart_product_id;
        $post=(array)$post;
        $return_ajax=new stdClass();
        $return_ajax->e=0;
        $virtuemart_departure_price_id=$model_departure_price->store($post);
        if(!$virtuemart_departure_price_id)
        {
            $return_ajax->e=1;
            $return_ajax->m='cannot save item';
            echo json_encode($return_ajax);
            die;
        }
        $departure_item=$model_departure_price->get_departure_price($virtuemart_departure_price_id);
        $departure_item->sale_period=JHtml::_('date', $departure_item->sale_period_from, 'd M. Y').'-'.JHtml::_('date', $departure_item->sale_period_to, 'd M. Y');
        $departure_item->modified_on=JHtml::_('date', $departure_item->modified_on, 'd M. Y');
        $return_ajax->r=$departure_item;
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
        $view = &$this->getView('departure', 'html', 'VirtuemartView');
        $modal_departure=$this->getModel('departure');
        $view->setModel($modal_departure,true);
        $model_product=$this->getModel('product');
        $product=$model_product->getProduct($tour_id,false,false,false);
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
        if($product->tour_methor=='tour_group')
        {
            $list_tour_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id($tour_id);
            $view->assignRef('list_tour_price_by_tour_price_id',$list_tour_price_by_tour_price_id);
            $list_group_size_by_tour_id=vmprice::get_list_group_size_by_tour_id($tour_id);
            $view->assignRef('list_group_size_by_tour_id',$list_group_size_by_tour_id);
        }else{
            $tour_private_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id_for_price($tour_id);
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

    public function get_list_price()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $view = &$this->getView('departure', 'html', 'VirtuemartView');
        $price_id=$input->get('price_id',0,'int');
        $model_departure_price = VmModel::getModel('departure');
        $model_departure_price->setId($price_id);
        $departure_price = $model_departure_price->get_departure_price();

        $tour_id=$app->input->get('tour_id',0,'int');


        $return_item=new stdClass();

        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmserviceclass.php';
        $virtuemart_service_class_ids=vmServiceclass::get_list_service_class_ids_by_tour_id($tour_id);
        $return_item->virtuemart_service_class_ids=$virtuemart_service_class_ids;

        $return_item->departure_price=$departure_price;
        $view->assignRef('departure_price',$return_item->departure_price);
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmdeparture.php';

        $tour_id=$input->get('tour_id',0,'int');
        $this->virtuemart_product_id=$tour_id;

        $model_product = VmModel::getModel('product');
        $product=$model_product->getProduct($tour_id,false,false,false);
        $return_item->tour=$product;
        if($product->tour_methor=='tour_group')
        {
            $return_item->list_tour_departure_price_by_tour_departure_price_id=vmdeparture::get_list_tour_departure_price_by_tour_departure_price_id($price_id);
            $view->assignRef('list_tour_departure_price_by_tour_departure_price_id',$return_item->list_tour_departure_price_by_tour_departure_price_id);
        }else{
            $return_item->tour_private_price_by_tour_price_id=vmdeparture::get_list_tour_departure_price_by_tour_price_id_for_departure_price($price_id);
            $view->assignRef('tour_private_price_by_tour_price_id',$return_item->tour_private_price_by_tour_price_id);
        }

        //get markup
        $return_item->list_departure_mark_up=vmdeparture::get_list_mark_up_by_tour_departure_price_id($price_id);
        $return_item->list_departure_mark_up=is_array($return_item->list_departure_mark_up)?$return_item->list_departure_mark_up:array($return_item->list_departure_mark_up);
        $return_item->list_departure_mark_up=JArrayHelper::pivot($return_item->list_departure_mark_up,'type');
        $view->assignRef('list_departure_mark_up',$return_item->list_departure_mark_up);
        //end get markup
        //get markup
        $return_item->list_departure=vmdeparture::get_list_departure_by_tour_departure_price_id($price_id);


        $return_item->list_departure=is_array($return_item->list_departure)?$return_item->list_departure:array($return_item->list_departure);
        $return_item->list_departure=JArrayHelper::pivot($return_item->list_departure,'type');
        $view->assignRef('list_departure',$return_item->list_departure);
        //end get markup
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
        $return_item->list_group_size_by_tour_id=vmdeparture::get_list_group_size_by_tour_id($tour_id);
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
