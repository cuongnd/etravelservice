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
class VirtuemartControllerPrice extends VmController {

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
        $model_price = VmModel::getModel('price');
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
        $model_price = VmModel::getModel('price');
        if(!$model_price->toggle('published',null,'price_id','price'))
        {
            echo 'cannot published item';
            die;
        }
        echo 1;
        die;

    }
    public function ajax_save_price()
    {
        $app=JFactory::getApplication();
        $post = file_get_contents('php://input');
        $post = json_decode($post);
        $input=$app->input;
        $price_id=$input->get('price_id',0,'int');
        $model_price = VmModel::getModel('price');
        $post=(array)$post;
        $return_ajax=new stdClass();
        $return_ajax->e=0;
        if(!$model_price->store($post))
        {
            $return_ajax->e=1;
            $return_ajax->m='cannot save item';
            echo json_encode($return_ajax);
            die;
        }
        $item=$model_price->getPrice();
        $item->sale_period=JHtml::_('date', $item->sale_period_from, 'd M. Y').'-'.JHtml::_('date', $item->sale_period_to, 'd M. Y');
        $item->modified_on=JHtml::_('date', $item->modified_on, 'd M. Y');
        $return_ajax->r=$item;
        echo json_encode($return_ajax);
        die;

    }


    public function get_list_price()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $price_id=$input->get('price_id',0,'int');
        $tour_methor=$input->get('tour_methor','','string');
        $model_price = VmModel::getModel('price');
        $model_price->setId($price_id);
        $price = $model_price->getPrice();

        $return_item=new stdClass();
        $return_item->price=$price;
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';

        $tour_id=$input->get('virtuemart_product_id',0,'int');
        $this->virtuemart_product_id=$tour_id;

        $model_product = VmModel::getModel('product');
        $product=$model_product->getProduct($this->virtuemart_product_id,false,false,false);

        if($tour_methor=='tour_group')
        {
            $return_item->list_tour_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id($price_id);
        }else{
            $return_item->tour_private_price_by_tour_price_id=vmprice::get_list_tour_price_by_tour_price_id_for_price($price_id);
        }

        //get markup
        $return_item->list_mark_up=vmprice::get_list_mark_up_by_tour_price_id($price_id);
        $return_item->list_mark_up=JArrayHelper::pivot($return_item->list_mark_up,'type');
        //end get markup
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmprice.php';
        $return_item->list_group_size_by_tour_id=vmprice::get_list_group_size_by_tour_id($tour_id);

        echo json_encode($return_item);
        die;






    }
    public function save_price()
    {
    }
}
// pure php no closing tag
