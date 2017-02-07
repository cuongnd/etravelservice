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

    public function save_order($booking_summary,$user_id){
        $_orderData=array();
        $orderTable =  $this->getTable('orders');
        $_orderData['tsmart_user_id']=$user_id;
        $_orderData['tsmart_vendor_id']=1;
        $_orderData['order_total']=$user_id;
        $_orderData['tsmart_user_id']=$booking_summary->total_price;
        $_orderData['coupon_code']='';
        $_orderData['coupon_code']='';
        $_orderData['order_data']=json_encode($booking_summary);
        $orderTable -> bindChecknStore($_orderData);
        return $orderTable;
    }
    public function send_bookprivategroupsumary($booking_summary, $email_address,$new_member=true,$order,$user_token='')
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
            <title><?php echo JText::_('Your booking')?></title>
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
        <div >
            <div class="mktEditable" >
                <div style="display: none; mso-hide: all; width: 0px; height: 0px; max-width: 0px; max-height: 0px; font-size: 0px; line-height: 0px;"><br /></div>
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
                                                            <td align="right" >
                                                                <img border="0" src="<?php echo JUri::root() ?>/images/asian_logo.jpg" width="225" height="70"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>


                                            </td>
                                            </tr>
                                            <tr>
                                                <td  style="padding: 10px 10px;  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;">
                                                    <?php echo JText::sprintf('Hi %s', $contact_data->contact_name) ?>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td   style="padding: 10px 10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;">

                                                    <?php echo JText::sprintf('Thanks for booking of "%s from %s to %s". A "%s" agent will contact you shortly and provide you width more details	about this tour', $tour->product_name, $from_date, $to_date, $company_info->company_name) ?> </td>

                                            </tr>
                                            <tr>
                                                <td  align="center"  style="padding-top: 20px; padding-bottom: 20px;">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                            <td bgcolor="#003366" style="font-family:HelveticaNeueLight,HelveticaNeue-Light,'Helvetica Neue Light',HelveticaNeue,Helvetica,Arial,sans-serif;font-weight:300;font-stretch:normal;text-align:center;color:#fff;font-size:15px;background:#0079C1;;border-radius:7px!important; -moz-border-radius: 7px !important; -o-border-radius: 7px !important; -ms-border-radius: 7px !important;line-height:1.45em;padding:7px 15px 8px;margin:0 auto 16px;font-size:1em;padding-bottom:7px;" >
                                                                <a href="<?php echo JUri::root() ?><?php echo $new_member?'/index.php?option=com_tsmart&controller=user&task=activate&token='.$user_token.'&go_to=last_booking':'index.php?option=com_tsmart&view=order&id='.$order->tsmart_order_id.'&Itemid=140' ?>" style="color:#ffffff; text-decoration:none; display:block; font-family:Arial,sans-serif; font-weight:bold; font-size:15px; line-height:15px;text-transform: uppercase" target="_blank"><?php echo JText::_('View booking') ?></a></td>
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
                                                            <td  valign="top" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;" width="70%"><?php echo JText::sprintf('%s %s %s %s , %s(date of birth), %s', $passenger->title, $passenger->last_name, $passenger->middle_name, $passenger->first_name, $date_of_birth, $passenger->nationality) ?></td>
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
                                                    $build_pre_transfer = $booking_summary->build_pre_transfer;

                                                    $build_post_transfer = $booking_summary->build_post_transfer;
                                                    $extra_pre_night_hotel = $booking_summary->extra_pre_night_hotel;
                                                    $extra_post_night_hotel = $booking_summary->extra_post_night_hotel;

                                                    ?>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td colspan="2" valign="top"  style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;text-transform: uppercase"><?php echo JText::_('Add on service') ?></td>
                                                        </tr>
                                                        <?php for ($i = 0; $i < count($extra_pre_night_hotel); $i++) { ?>
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
                                                            <td valign="top"  width="30%"style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;text-transform: uppercase"><?php echo JText::sprintf('Pre night %s', $i + 1) ?></td>
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
                                                            <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;" ><?php echo JText::sprintf('%s <br/> check in date: %s,Check out date: %s', $list_room_type1, $check_in, $check_out) ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;">PRE-TRANSFER</td>
                                                            <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">19 Sep, 2017, 3 passengers</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;">POST TRANSFER</td>
                                                            <td valign="top" width="70%" style="padding-top: 5px; padding-bottom: 5px; padding-left:5px;padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">30 Sep, 2017, 4 passengers</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left:10px;padding-right:5px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 13px;">EXCURSIONS</td>
                                                            <td valign="top" width="70%"style="padding-top: 5px; padding-bottom: 5px; padding-left:5px; padding-right:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">Half day trip to Bat Trang for 3 passengers. Street food in Hanoi
                                                                for 4 passengers</td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9" >&nbsp;</td>
                                            </tr>
                                            <td  align="center"  style="padding-top: 20px; padding-bottom: 20px;">
                                                <table border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                    <tr>
                                                        <td  bgcolor="#003366"   style="font-family:HelveticaNeueLight,HelveticaNeue-Light,'Helvetica Neue Light',HelveticaNeue,Helvetica,Arial,sans-serif;font-weight:300;font-stretch:normal;text-align:center;color:#fff;font-size:15px;background:#0079C1;;border-radius:7px!important; -moz-border-radius: 7px !important; -o-border-radius: 7px !important; -ms-border-radius: 7px !important;line-height:1.45em;padding:7px 15px 8px;margin:0 auto 16px;font-size:1em;padding-bottom:7px;" >
                                                            <a href="http://www.asianventure.com" style="color:#ffffff; text-decoration:none; display:block; font-family:Arial,sans-serif; font-weight:bold; font-size:15px; line-height:15px;" target="_blank">VIEW TRIP DETAILS &amp; MESSAGES</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </td>

                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9" >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="mobile-width-nopad">
                                                        <tbody>
                                                        <tr>
                                                            <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">NO BOOKING FEES</td>
                                                            <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">SECURE PAYMENT </td>
                                                            <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;">24 /7 SUPPORT</td>
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

        $recipient = array($email_address, 'asianventuretours@gmail.com', 'hong@asianventure.com', 'cuong@asianventure.com');

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