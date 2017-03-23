<?php

/**
 *
 * Product details view
 *
 * @package tsmart
 * @subpackage
 * @author RolandD
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9031 2015-10-29 20:20:33Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Product details
 *
 * @property  depatrure
 * @package tsmart
 * @author Max Milbers
 */
class TsmartViewbookprivategroupsumary extends VmView
{
    public $trip;
    public $product;
    public $privategrouptrip;

    /**
     * Collect all data to show on the template
     *
     * @author RolandD, Max Milbers
     */
    function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $session=JFactory::getSession();
        $tsmart_price_id = $session->get('tsmart_price_id', 0);
        $tsmart_departure_id = $input->getInt('tsmart_departure_id', 0);
        $booking_date = $input->getString('booking_date', '');
        $privategrouptrip_model = tmsModel::getModel('privategrouptrip');
        $item_private_group_trip = $privategrouptrip_model->getItem($tsmart_price_id,$booking_date,'multi_price');
        if($tsmart_departure_id){
            $item_private_group_trip = $this->privategrouptrip_model->getItem($tsmart_departure_id,$booking_date,'flat_price');
        }
        $tsmart_product_id = $item_private_group_trip->tsmart_product_id;
        $input->set('tsmart_product_id', $tsmart_product_id);
        $this->privategrouptrip = $item_private_group_trip;
        $this->privategrouptrip->allow_passenger='infant,child_1,child_2,teen,adult,senior';
        $this->privategrouptrip->departure_date=$booking_date;

        $product_model = tmsModel::getModel('product');
        $this->product = $product_model->getItem($this->privategrouptrip->tsmart_product_id);

        require_once JPATH_ROOT . '/components/com_tsmart/helpers/vmjointgroup.php';
        $this->rooming_select_type = Vmjointgroup::get_list_rooming_type();
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $this->lipsum = new joshtronic\LoremIpsum();

        $payment_helper=tsmHelper::getHepler('payment');
        $this->payment_rule=$payment_helper->get_payment_rule_by_product($this->product->tsmart_product_id);

        parent::display($tpl);
    }


}

// pure php no closing tag