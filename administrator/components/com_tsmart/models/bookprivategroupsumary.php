<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');
/**
 * Model class for shop Currencies
 *
 * @package    tsmart
 * @subpackage Currency
 */
class tsmartModelbookprivategroupsumary extends tmsModel
{
    public function save_order($booking_summary, $payment_type = 'full_payment', $user_id)
    {
        $contact_data = $booking_summary->contact_data;
        $contact_data = json_decode($contact_data);
        $customsTable = $this->getTable('customs');
        $store_data_contact = new stdClass();
        $store_data_contact->custom_name = $contact_data->contact_name;
        $store_data_contact->phone = $contact_data->phone_number;
        $store_data_contact->email = $contact_data->email_address;
        $store_data_contact->street = $contact_data->street_address;
        $store_data_contact->sub_town = $contact_data->suburb_town;
        $store_data_contact->state_province = $contact_data->state_province;
        $store_data_contact->zip_code = $contact_data->post_code_zip;
        $store_data_contact->country = $contact_data->res_country;
        $store_data_contact->emergency_contact_name = $contact_data->emergency_contact_name;
        $store_data_contact->emergency_contact_address = $contact_data->emergency_email_address;
        $store_data_contact->emergency_contact_phone = $contact_data->emergency_phone_number;
        $store_data_contact = (array)$store_data_contact;
        $ok = $customsTable->bindChecknStore($store_data_contact);
        if (!$ok) {
            echo "<pre>";
            print_r($customsTable->getErrors(), false);
            echo "</pre>";
            die;
            die;
        }
        $tsmart_price_id = $booking_summary->departure->tsmart_price_id;
        $tsmart_product_id = $booking_summary->departure->tsmart_product_id;
        $payment_helper=tsmHelper::getHepler('payment');
        $payment_rule=$payment_helper->get_payment_rule_by_product($tsmart_product_id);
        $booking_summary->payment_rule=$payment_rule;
        $tsmproduct = tsmHelper::getHepler('product');
        $product = $tsmproduct->get_product_by_tour_id($tsmart_product_id);
        $booking = tsmHelper::getHepler('booking');
        $total_price = $booking->get_total_price();
        $payment_helper = tsmHelper::getHepler('payment');
        $payment_rule = $payment_helper->get_payment_rule_by_product($product->tsmart_product_id);
        $tsmart_orderstate_id = $payment_rule->mode;
        if ($payment_type == 'deposit_payment') {
            $percent_balance_of_day_1 = $payment_rule->percent_balance_of_day_1;
            $receipt = $total_price * $percent_balance_of_day_1 / 100;
        } else {
            $receipt = $total_price;
        }
        $_orderData = array();
        $orderTable = $this->getTable('orders');
        $_orderData['tsmart_user_id'] = $user_id;
        $_orderData['tsmart_vendor_id'] = 1;
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
        $order_number = $today . $rand;
        $_orderData['order_number'] = $order_number;
        $_orderData['tsmart_orderstate_id'] = $tsmart_orderstate_id;
        $_orderData['order_total'] = $total_price;
        $_orderData['tsmart_user_id'] = $booking_summary->total_price;
        $_orderData['coupon_code'] = '';
        $_orderData['receipt'] = $receipt;
        $_orderData['product_type'] = $product->price_type;
        $_orderData['object_id'] = $tsmart_price_id;
        $_orderData['tsmart_custom_id'] = $customsTable->tsmart_custom_id;
        $_orderData['order_data']=json_encode($booking_summary);
        $ok = $orderTable->bindChecknStore($_orderData);
        if (!$ok) {
            echo "<pre>";
            print_r($orderTable->getErrors(), false);
            echo "</pre>";
            die;
            die;
        }
        $passengerTable = $this->getTable('passenger');
        $list_passenger=$booking_summary->list_passenger;
        $passenger_index=0;
        foreach($list_passenger as $group_passenger){
            foreach($group_passenger as $passenger){
                $passengerTable->tsmart_passenger_id=0;
                $passenger->date_of_birth=JFactory::getDate($passenger->date_of_birth)->toSql();
                $passenger->passenger_index=$passenger_index;
                $passenger->tsmart_order_id=$orderTable->tsmart_order_id;
                $passenger->tour_tsmart_passenger_state_id=2;
                $store_data_passenger = (array)$passenger;
                $ok = $passengerTable->bindChecknStore($store_data_passenger);
                if (!$ok) {
                    echo "<pre>";
                    print_r($passengerTable->getErrors(), false);
                    echo "</pre>";
                    die;
                    die;
                }
                $passenger_index++;
            }
        }
        $passenger_helper=TSMHelper::getHepler('passenger');


        $room_orderTable = $this->getTable('room_order');
        $list_passenger_by_order=$passenger_helper->get_list_passenger_by_order_id($orderTable->tsmart_order_id);
        $build_room=$booking_summary->build_room;
        foreach ($build_room as $item) {
            $item=(object)$item;
            if(!count($item->passengers))
            {
                continue;
            }
            $room_orderTable->tsmart_room_order_id=0;
            $room_orderTable->tsmart_order_id=$orderTable->tsmart_order_id;
            $room_orderTable->room_note=$item->room_note;
            $room_orderTable->store();
            $tsmart_room_order_id=$room_orderTable->tsmart_room_order_id;
            $tour_cost_and_room_price = $item->tour_cost_and_room_price;
            $room_type = $item->list_room_type;
            foreach ($tour_cost_and_room_price as $item_passenger) {
                $passenger_index = $item_passenger->passenger_index;
                $passengerTable->load($list_passenger_by_order[$passenger_index]->tsmart_passenger_id);
                $passengerTable->tour_cost=$item_passenger->tour_cost;
                $passengerTable->room_type=$room_type;
                $passengerTable->room_fee=$item_passenger->room_price;
                $passengerTable->extra_fee=$item_passenger->extra_bed_price;
                $passengerTable->room_note=$item_passenger->msg;
                $passengerTable->bed_note=$item_passenger->bed_note;
                $passengerTable->tsmart_room_order_id=$tsmart_room_order_id;
                $passengerTable->store();
            }

        }

        $save_data_night_hotel=function($type="pre",$extra_night_hotel,$orderTable,$list_passenger_by_order,$hotel_addon_orderTable,$passengerTable){
            for ($i = 0, $n = count($extra_night_hotel); $i < $n; $i++) {
                $row = $extra_night_hotel[$i];
                $list_room_type = $row->list_room_type;
                $list_passenger_price = $row->list_passenger_price;
                $list_passenger_price = JArrayHelper::pivot($list_passenger_price, 'passenger_index');
                $tsmart_hotel_addon_id = $row->tsmart_hotel_addon_id;
                $list_passenger_price=$row->list_passenger_price;

                foreach ($list_room_type as $room) {


                    $list_passenger_per_room = $room->list_passenger_per_room;
                    foreach ($list_passenger_per_room as $list_passenger_in_room) {
                        $hotel_addon_orderTable->tsmart_order_hotel_addon_id=0;
                        $hotel_addon_orderTable->tsmart_hotel_addon_id=$tsmart_hotel_addon_id;
                        $hotel_addon_orderTable->tsmart_order_id=$orderTable->tsmart_order_id;
                        $hotel_addon_orderTable->note=$row->room_note;
                        $hotel_addon_orderTable->checkin_date=JFactory::getDate($row->check_in_date)->toSql();
                        $hotel_addon_orderTable->checkout_date=JFactory::getDate($row->check_out_date)->toSql();
                        $hotel_addon_orderTable->store();
                        $tsmart_order_hotel_addon_id=$hotel_addon_orderTable->tsmart_order_hotel_addon_id;

                        foreach ($list_passenger_in_room as $passenger_index) {
                            $passengerTable->load($list_passenger_by_order[$passenger_index]->tsmart_passenger_id);
                            $key_night=$type."_tsmart_order_hotel_addon_id";
                            $key_night_fee=$type."_night_hotel_fee";
                            $passengerTable->$key_night=$tsmart_order_hotel_addon_id;
                            $passengerTable->store();

                        }
                    }
                }
                foreach ($list_passenger_price as $item_passenger_price) {
                    $passenger_index=$item_passenger_price->passenger_index;
                    $passengerTable->load($list_passenger_by_order[$passenger_index]->tsmart_passenger_id);
                    $key_night_fee=$type."_night_hotel_fee";
                    $passengerTable->$key_night_fee=$item_passenger_price->cost;
                    $passengerTable->store();
                }


            }
        };
        $hotel_addon_orderTable = $this->getTable('hotel_addon_order');
        $extra_post_night_hotel=(array)$booking_summary->extra_post_night_hotel;
        if(count($extra_post_night_hotel))
        {
            $save_data_night_hotel("post",$extra_post_night_hotel,$orderTable,$list_passenger_by_order,$hotel_addon_orderTable,$passengerTable);
        }
        $extra_pre_night_hotel=(array)$booking_summary->extra_pre_night_hotel;
        if(count($extra_pre_night_hotel))
        {
            $save_data_night_hotel("pre",$extra_pre_night_hotel,$orderTable,$list_passenger_by_order,$hotel_addon_orderTable,$passengerTable);
        }
        $save_data_transfer=function($type="pre",$extra_transfer,$orderTable,$list_passenger_by_order,$transfer_addon_orderTable,$passengerTable){
            for ($i = 0, $n = count($extra_transfer); $i < $n; $i++) {
                $row = $extra_transfer[$i];
                $tsmart_transfer_addon_id = $row->tsmart_transfer_addon_id;
                $list_passenger_price=$row->list_passenger_price;

                $transfer_addon_orderTable->tsmart_order_transfer_addon_id=0;
                $transfer_addon_orderTable->tsmart_transfer_addon_id=$tsmart_transfer_addon_id;
                $transfer_addon_orderTable->tsmart_order_id=$orderTable->tsmart_order_id;
                $transfer_addon_orderTable->note=$row->transfer_note;
                $transfer_addon_orderTable->checkin_date=JFactory::getDate($row->check_in_date)->toSql();
                $transfer_addon_orderTable->store();
                $tsmart_order_transfer_addon_id=$transfer_addon_orderTable->tsmart_order_transfer_addon_id;

                foreach ($list_passenger_price as $passenger_cost) {
                    $passengerTable->load($list_passenger_by_order[$passenger_cost->passenger_index]->tsmart_passenger_id);
                    $key_transfer=$type."_tsmart_order_transfer_addon_id";
                    $key_transfer_fee=$type."_transfer_fee";
                    $passengerTable->$key_transfer=$tsmart_order_transfer_addon_id;
                    $passengerTable->$key_transfer_fee=$passenger_cost->cost;
                    $passengerTable->store();

                }
            }
        };
        $transfer_addon_orderTable = $this->getTable('transfer_addon_order');
        $build_pre_transfer=(array)$booking_summary->build_pre_transfer;
        if(count($build_pre_transfer)){
            $save_data_transfer("post",$build_pre_transfer,$orderTable,$list_passenger_by_order,$transfer_addon_orderTable,$passengerTable);
        }
        $build_post_transfer=(array)$booking_summary->build_post_transfer;
        if(count($build_post_transfer)){
            $save_data_transfer("post",$build_post_transfer,$orderTable,$list_passenger_by_order,$transfer_addon_orderTable,$passengerTable);
        }
        //-----------------------------------
        $excursion_addon_order_orderTable = $this->getTable('excursion_addon_order');
        $excursion_addon_passenger_price_order_orderTable = $this->getTable('excursion_addon_passenger_price_order');
        $build_excursion_addon=(array)$booking_summary->build_excursion_addon;
        if(count($build_excursion_addon))
        {
            for ($i = 0, $n = count($build_excursion_addon); $i < $n; $i++) {

                $row = $build_excursion_addon[$i];
                if(!count($row->passengers))
                {
                    continue;
                }
                $tsmart_excursion_addon_id = $row->tsmart_excursion_addon_id;
                $list_passenger_price=$row->list_passenger_price;

                $excursion_addon_order_orderTable->tsmart_order_excursion_addon_id=0;
                $excursion_addon_order_orderTable->tsmart_excursion_addon_id=$tsmart_excursion_addon_id;

                $excursion_addon_order_orderTable->tsmart_order_id=$orderTable->tsmart_order_id;
                $excursion_addon_order_orderTable->note=$row->excursion_note;
                $excursion_addon_order_orderTable->store();
                $tsmart_order_excursion_addon_id=$excursion_addon_order_orderTable->tsmart_order_excursion_addon_id;

                foreach ($list_passenger_price as $passenger_cost) {
                    $tsmart_passenger_id=$list_passenger_by_order[$passenger_cost->passenger_index]->tsmart_passenger_id;
                    $excursion_addon_passenger_price_order_orderTable->id=0;
                    $excursion_addon_passenger_price_order_orderTable->tsmart_order_excursion_addon_id=$tsmart_order_excursion_addon_id;
                    $excursion_addon_passenger_price_order_orderTable->tsmart_passenger_id=$tsmart_passenger_id;
                    $excursion_addon_passenger_price_order_orderTable->excusion_fee=$passenger_cost->cost;
                    $excursion_addon_passenger_price_order_orderTable->store();

                }
            }
        }
        return $orderTable;
    }
    public function send_bookprivategroupsumary($booking_summary, $email_address, $new_member = true, $order, $user_token = '')
    {
        $company_info = tsmConfig::get_company_info();
        $tour = $booking_summary->tour;
        $list_passenger = $booking_summary->list_passenger;
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $departure = $booking_summary->departure;
        $list_destination = $departure->list_destination;
        $list_destination = explode(';', $list_destination);
        $contact_data = $booking_summary->contact_data;
        $contact_data = json_decode($contact_data);
        $from_date = $departure->departure_date;
        $from_date = JFactory::getDate($from_date);
        $to_date = clone $from_date;
        $from_date = JHtml::_('date', $from_date);
        $total_day = $departure->total_day;
        $total_day--;
        $to_date->modify("+$total_day days");
        $to_date = JHtml::_('date', $to_date);
        ob_start();
        ?>
        <html>
        <head>
            <meta http-equiv="Content-Language" content="en-us">
            <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
            <title><?php echo JText::_('Your booking') ?></title>
            <style type="text/css">
                .ReadMsgBody {
                    width: 100%;
                }
                .ExternalClass {
                    width: 100%;
                }
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
                    line-height: 100%;
                }
                body, table, td, a {
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                }
                table {
                    border-collapse: collapse !important;
                }
                table, td {
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                }
                img {
                    border: 0;
                    line-height: 100%;
                    outline: none;
                    text-decoration: none;
                    -ms-interpolation-mode: bicubic;
                }
                @media screen and (max-width: 480px) {
                    html {
                        -webkit-text-size-adjust: none;
                    }
                    *[class].mobile-width {
                        width: 100% !important;
                        padding-left: 10px;
                        padding-right: 10px;
                    }
                    *[class].mobile-width-nopad {
                        width: 100% !important;
                    }
                    *[class].stack {
                        display: block !important;
                        width: 100% !important;
                    }
                    *[class].hide {
                        display: none !important;
                    }
                    *[class].center, *[class].center img {
                        text-align: center !important;
                        margin: 0 auto;
                    }
                    *[class].scale img, *[class].editable_image img {
                        max-width: 100%;
                        height: auto;
                        margin: 0 auto;
                    }
                    *[class].addpad {
                        padding: 10px !important;
                    }
                    *[class].addpad-top {
                        padding-top: 30px !important;
                    }
                    *[class].sanpad {
                        padding: 0px !important;
                    }
                    *[class].sanborder {
                        border: none !important;
                    }
                }
            </style>
        </head>
        </head>
        <body style="margin:0; padding:0; width:100% !important; background-color:#ffffff; ">
        <div>
            <div class="mktEditable">
                <div style="display: none; mso-hide: all; width: 0px; height: 0px; max-width: 0px; max-height: 0px; font-size: 0px; line-height: 0px;">
                    <br/></div>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="top" align="center" bgcolor="#E8E9E9" style="padding: 0px 10px;">
                            <table width="640" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" class="mobile-width-nopad">
                                <tbody>
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td align="center" valign="top" style="padding-top: 10px; padding-bottom: 10px;">
                                                    <table border="0" width="100%" cellspacing="0" class="mobile-width-nopad">
                                                        <tbody>
                                                        <tr>
                                                            <td align="right">
                                                                <img border="0" src="<?php echo JUri::root() ?>/images/asian_logo.jpg" width="225" height="70">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 10px;  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;">
                                                    <?php echo JText::sprintf('Hi %s', $contact_data->contact_name) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;">
                                                    <?php echo JText::sprintf('Thanks for booking of "%s from %s to %s". A "%s" agent will contact you shortly and provide you width more details	about this tour', $tour->product_name, $from_date, $to_date, $company_info->company_name) ?> </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="padding-top: 20px; padding-bottom: 20px;">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                            <td bgcolor="#003366" style="font-family:HelveticaNeueLight,HelveticaNeue-Light,'Helvetica Neue Light',HelveticaNeue,Helvetica,Arial,sans-serif;font-weight:300;font-stretch:normal;text-align:center;color:#fff;font-size:15px;background:#0079C1;;border-radius:7px!important; -moz-border-radius: 7px !important; -o-border-radius: 7px !important; -ms-border-radius: 7px !important;line-height:1.45em;padding:7px 15px 8px;margin:0 auto 16px;font-size:1em;padding-bottom:7px;">
                                                                <a href="<?php echo JUri::root() ?><?php echo $new_member ? '/index.php?option=com_tsmart&controller=user&task=activate&token=' . $user_token . '&go_to=last_booking' : 'index.php?option=com_tsmart&view=order&id=' . $order->tsmart_order_id . '&Itemid=140' ?>" style="color:#ffffff; text-decoration:none; display:block; font-family:Arial,sans-serif; font-weight:bold; font-size:15px; line-height:15px;text-transform: uppercase" target="_blank"><?php echo JText::_('View booking') ?></a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9">
                                                    &nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="2" style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px; text-align:left; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; font-size: 17px;text-transform: uppercase"><?php echo JText::_('My trip summary') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('Trip name') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                <?php echo $tour->product_name ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('Trip style') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                <?php echo $tour->product_name ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('Trip type') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15Px;">
                                                                <?php echo $tour->product_name ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('Duration') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                <?php echo JText::sprintf('%s days & %s nights', $total_day + 1, $total_day) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('Starts in') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                <?php echo JText::sprintf('%s in %s', $from_date, reset($list_destination)) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('End in') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                <?php echo JText::sprintf('%s in %s', $to_date, end($list_destination)) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase">
                                                                <?php echo JText::_('No. of traveler') ?></td>
                                                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:5px;  text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                <?php echo JText::sprintf('%s person', count($list_passenger)) ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9">
                                                    &nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="2" valign="top" style="padding-top: 10px; padding-bottom: 10px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;text-transform: uppercase"><?php echo JText::_('Passenger list') ?></td>
                                                        </tr>
                                                        <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                                                            <?php
                                                            $passenger = $list_passenger[$i];
                                                            $date_of_birth = $passenger->date_of_birth;
                                                            $date_of_birth = JHtml::_('date', $date_of_birth);
                                                            ?>
                                                            <tr>
                                                                <td valign="top" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase" width="30%"><?php echo JText::sprintf('Passenger %s', $i + 1) ?></td>
                                                                <td valign="top" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;" width="70%"><?php echo JText::sprintf('%s %s %s %s , %s(date of birth), %s', $passenger->title, $passenger->last_name, $passenger->middle_name, $passenger->first_name, $date_of_birth, $passenger->nationality) ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9">
                                                    &nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $build_pre_transfer = (array)$booking_summary->build_pre_transfer;
                                                    $build_post_transfer = (array)$booking_summary->build_post_transfer;
                                                    $extra_pre_night_hotel = (array)$booking_summary->extra_pre_night_hotel;
                                                    $extra_post_night_hotel = (array)$booking_summary->extra_post_night_hotel;
                                                    ?>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td colspan="2" valign="top" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;text-transform: uppercase"><?php echo JText::_('Add on service') ?></td>
                                                        </tr>
                                                        <?php if(is_array($extra_pre_night_hotel)&&count($extra_pre_night_hotel) )for ($i = 0; $i < count($extra_pre_night_hotel); $i++) { ?>
                                                            <?php

                                                            $pre_night_hotel = $extra_pre_night_hotel[$i];
                                                            $list_room_type = $pre_night_hotel->list_room_type;
                                                            $list_room_type1 = array();
                                                            foreach ($list_room_type as $room) {
                                                                $list_room_type1[] = "$room->total_room $room->room_type";
                                                            }
                                                            $list_room_type1 = implode(',', $list_room_type1);
                                                            $check_in = JFactory::getDate();
                                                            $check_in = JHtml::_('date', $check_in);
                                                            $check_out = JFactory::getDate();
                                                            $check_out = JHtml::_('date', $check_out);
                                                            ?>
                                                            <tr>
                                                                <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase"><?php echo JText::sprintf('Pre night %s', $i + 1) ?></td>
                                                                <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;"><?php echo JText::sprintf('%s <br/> check in date: %s,Check out date: %s', $list_room_type1, $check_in, $check_out) ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php for ($i = 0; $i < count($extra_post_night_hotel); $i++) { ?>
                                                            <?php
                                                            $post_night_hotel = $extra_post_night_hotel[$i];
                                                            $list_room_type = $post_night_hotel->list_room_type;
                                                            $list_room_type1 = array();
                                                            foreach ($list_room_type as $room) {
                                                                $list_room_type1[] = "$room->total_room $room->room_type";
                                                            }
                                                            $list_room_type1 = implode(',', $list_room_type1);
                                                            $check_in = JFactory::getDate();
                                                            $check_in = JHtml::_('date', $check_in);
                                                            $check_out = JFactory::getDate();
                                                            $check_out = JHtml::_('date', $check_out);
                                                            ?>
                                                            <tr>
                                                                <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase"><?php echo JText::sprintf('Post night %s', $i + 1) ?></td>
                                                                <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;"><?php echo JText::sprintf('%s <br/> check in date: %s,Check out date: %s', $list_room_type1, $check_in, $check_out) ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;">
                                                                PRE-TRANSFER
                                                            </td>
                                                            <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                19 Sep, 2017, 3 passengers
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;">
                                                                POST TRANSFER
                                                            </td>
                                                            <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                30 Sep, 2017, 4 passengers
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;">
                                                                EXCURSIONS
                                                            </td>
                                                            <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:5px; padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                Half day trip to Bat Trang for 3 passengers. Street food
                                                                in Hanoi
                                                                for 4 passengers
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9">&nbsp;</td>
                                            </tr>
                                            <td align="center" style="padding-top: 20px; padding-bottom: 20px;">
                                                <table border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                    <tr>
                                                        <td bgcolor="#003366" style="font-family:HelveticaNeueLight,HelveticaNeue-Light,'Helvetica Neue Light',HelveticaNeue,Helvetica,Arial,sans-serif;font-weight:300;font-stretch:normal;text-align:center;color:#fff;font-size:15px;background:#0079C1;;border-radius:7px!important; -moz-border-radius: 7px !important; -o-border-radius: 7px !important; -ms-border-radius: 7px !important;line-height:1.45em;padding:7px 15px 8px;margin:0 auto 16px;font-size:1em;padding-bottom:7px;">
                                                            <a href="http://www.asianventure.com" style="color:#ffffff; text-decoration:none; display:block; font-family:Arial,sans-serif; font-weight:bold; font-size:15px; line-height:15px;" target="_blank">VIEW
                                                                TRIP DETAILS &amp; MESSAGES</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="mobile-width-nopad">
                                                        <tbody>
                                                        <tr>
                                                            <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                NO BOOKING FEES
                                                            </td>
                                                            <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                SECURE PAYMENT
                                                            </td>
                                                            <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">
                                                                24 /7 SUPPORT
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#003333" height="35px">&nbsp;</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                </tbody>
                            </table>
                    </tbody>
                </table>
            </div>
        </div>
        </body>
        </html>
        <?php
        $email_content = ob_get_clean();
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);
        $user = JFactory::getUser();
        $recipient = $user->email;
        $mailer->addRecipient($recipient);
        $recipient = array($email_address, 'cuong@asianventure.com');
        $mailer->addRecipient($recipient);
        $body = $email_content;
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject('Your booking');
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
            throw new Exception('Error sending email: ' . $send->__toString());
        } else {
            return true;
        }
    }
}
// pure php no closing tag