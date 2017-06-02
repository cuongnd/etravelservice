<?php/** * * Currency View * * @package    tsmart * @subpackage Currency * @author RickG * @link http://www.tsmart.net * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved. * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php * tsmart is free software. This version may have been modified pursuant * to the GNU General Public License, and as distributed it includes or * is derivative of works licensed under the GNU General Public License or * other free or open source software licenses. * @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $ */// Check to ensure this file is included in Joomla!defined('_JEXEC') or die('Restricted access');// Load the view frameworkif (!class_exists('tsmViewAdmin')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmviewadmin.php');/** * HTML View class for maintaining the list of currencies * * @package    tsmart * @subpackage Currency * @author RickG, Max Milbers */class TsmartVieworders extends tsmViewAdmin{    function display($tpl = null)    {        // Load the helper(s)        if (!class_exists('VmHTML'))            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');        $model = tmsModel::getModel();        $this->state=$model->getState();        $input = JFactory::getApplication()->input;        $task = $input->get('task');        $config = JFactory::getConfig();        $layoutName = vRequest::getCmd('layout', 'default');        $this->excursionaddon_helper=TSMHelper::getHepler('excursionaddon');        $this->hoteladdon_helper=TSMHelper::getHepler('hoteladdon');        $this->cities_helper=TSMHelper::getHepler('cities');        $this->orders_helper=TSMHelper::getHepler('orders');        $this->transferaddon_helper=TSMHelper::getHepler('transferaddon');        $this->passenger_helper=TSMHelper::getHepler('passenger');        $this->currency_helper=TSMHelper::getHepler('currency');        $this->payment_helper=TSMHelper::getHepler('paymenet');        $this->paymentmethod_helper=TSMHelper::getHepler('paymentmethod');        $this->list_gender=$this->passenger_helper->get_list_gender();        if ($layoutName == 'edit') {            $cid = vRequest::getInt('cid');            $task = vRequest::getCmd('task', 'add');            if ($task != 'add' && !empty($cid) && !empty($cid[0])) {                $cid = (int)$cid[0];            } else {                $cid = 0;            }            $model->setId($cid);            $this->item = $model->getItem();            //get list tour            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmorders.php';            $this->item->list_tour_id = tsmorders::get_list_tour_id_by_transfer_addon_id($this->item->tsmart_transfer_addon_id);            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmproduct.php';            $list_tour = tsmproduct::get_list_product();            $this->tsm_config=tsmConfig::get_config();            $this->assignRef('list_tour', $list_tour);            //end get list tour            $this->SetViewTitle('', $this->item->title);            $this->addStandardEditViewCommandsPopup();        } else {            $this->SetViewTitle();            JToolBarHelper::publishList();            JToolBarHelper::unpublishList();            $this->tsm_config=tsmConfig::get_config();            JToolBarHelper::editList();            JToolBarHelper::addNew('add_new_item');            JToolBarHelper::deleteList();            $this->addStandardDefaultViewLists($model, 0, 'ASC');            $this->items = $model->getItemList();            $this->pagination = $model->getPagination();            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcities.php';            $this->list_cityarea = tsmcities::get_city_state_country();            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmorders.php';            $this->list_transfer_type = tsmorders::get_list_transfer_type();            $this->list_transfer_payment_type = tsmorders::get_list_transfer_payment_type();            if ($task == 'edit_item') {                $this->setLayout('edit');                $cid = vRequest::getInt('cid');                $task = vRequest::getCmd('task', 'add');                if ($task != 'add' && !empty($cid) && !empty($cid[0])) {                    $cid = (int)$cid[0];                } else {                    $cid = 0;                }                $model->setId($cid);                $this->item = $model->getItem();                //get list tour                //end get list tour            }            $this->list_passenger=$this->passenger_helper->get_list_passenger_by_order_id($this->item->tsmart_order_id);            $this->list_passenger_in_pre_night_hotel=$this->passenger_helper->get_list_passenger_of_night_hotel_by_order_id("pre",$this->item->tsmart_order_id);            $this->list_passenger_in_post_night_hotel=$this->passenger_helper->get_list_passenger_of_night_hotel_by_order_id("post",$this->item->tsmart_order_id);            $this->list_passenger_in_pre_transfer=$this->passenger_helper->get_list_passenger_of_transfer_by_order_id("pre",$this->item->tsmart_order_id);            $this->list_passenger_in_post_transfer=$this->passenger_helper->get_list_passenger_of_transfer_by_order_id("post",$this->item->tsmart_order_id);            $this->list_passenger_in_room=$this->passenger_helper->get_list_passenger_in_room_by_order_id($this->item->tsmart_order_id);            $this->list_passenger_not_in_room=$this->passenger_helper->get_list_passenger_not_in_room_by_order_id($this->item->tsmart_order_id);            $this->list_pre_night_hotel=$this->passenger_helper->get_list_night_hotel_by_order_id("pre",$this->item->tsmart_order_id);            $this->list_passenger_in_excursion=$this->passenger_helper->get_list_passenger_in_excursion_by_order_id($this->item->tsmart_order_id);            $this->list_excursion=$this->passenger_helper->get_list_excursion($this->item->tsmart_order_id);            $this->list_post_night_hotel=$this->passenger_helper->get_list_night_hotel_by_order_id("post",$this->item->tsmart_order_id);            $this->list_pre_transfer=$this->passenger_helper->get_list_transfer_by_order_id("pre",$this->item->tsmart_order_id);            $this->list_post_transfer=$this->passenger_helper->get_list_transfer_by_order_id("post",$this->item->tsmart_order_id);        }        parent::display($tpl);    }}// pure php no closing tag