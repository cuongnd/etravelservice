<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/system/js/datepicker/js/datepicker.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/jquery.dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js');


$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_price.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/clean.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_virtuemart/assets/less/view_price.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'COM_VIRTUEMART_CURRENCY_DETAILS');


?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">


        <div class="col50">

            <?php if ($this->product->tour_methor == 'tour_group') { ?>
                <div class="row-fluid">
                    <div class="span6">
                        <h3>NET PRICE</h3>
                        <table class="table-bordered  table table-striped base-price">
                            <tr>
                                <td>Passenger</td>
                                <td style="text-align: center">Senior</td>
                                <td style="text-align: center">Adult</td>
                                <td style="text-align: center">Teen</td>
                                <td style="text-align: center">Child 1</td>
                                <td style="text-align: center">Child 2</td>
                                <td style="text-align: center">Infant</td>
                                <td style="text-align: center">Pr. Room</td>
                            </tr>
                            <?php foreach ($this->list_group_size_by_tour_id AS $group_size) { ?>
                                <?php

                                $tour_price_by_tour_price_id = $this->list_tour_price_by_tour_price_id[$group_size->virtuemart_group_size_id];
                                $price_adult = isset($tour_price_by_tour_price_id->price_adult) ? $tour_price_by_tour_price_id->price_adult : 0;
                                $price_infant = isset($tour_price_by_tour_price_id->price_infant) ? $tour_price_by_tour_price_id->price_infant : 0;
                                $price_children1 = isset($tour_price_by_tour_price_id->price_children1) ? $tour_price_by_tour_price_id->price_children1 : 0;
                                $price_children2 = isset($tour_price_by_tour_price_id->price_children2) ? $tour_price_by_tour_price_id->price_children2 : 0;
                                $price_children = isset($tour_price_by_tour_price_id->private_room_price) ? $tour_price_by_tour_price_id->private_room_price : 0;
                                ?>
                                <tr>
                                    <td style="text-align: center"><?php echo $group_size->group_name ?></td>
                                    <td><input type="text" size="7" value="<?php echo $price_adult ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_adult]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_children ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_children ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children]"
                                               required="true" class="inputbox number"></td>

                                </tr>
                            <?php } ?>
                        </table>
                        <h3>mark up</h3>
                        <table class="table-bordered  table table-striped base-price">
                            <tr>
                                <td>MARK UP VALUE</td>
                                <td style="text-align: center">Senior</td>
                                <td style="text-align: center">Adult</td>
                                <td style="text-align: center">Teen</td>
                                <td style="text-align: center">Child 1</td>
                                <td style="text-align: center">Child 2</td>
                                <td style="text-align: center">Infant</td>
                                <td style="text-align: center">Pr. Room</td>
                            </tr>
                            <tr>
                                <td>Amout</td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                            </tr>
                            <tr>
                                <td>percent</td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                                <td style="text-align: center"><input type="text"></td>
                            </tr>
                        </table>
                        <h3>PROFIT</h3>
                        <table class="table-bordered  table table-striped base-price">
                            <tr>
                                <td>Passenger</td>
                                <td style="text-align: center">Senior</td>
                                <td style="text-align: center">Adult</td>
                                <td style="text-align: center">Teen</td>
                                <td style="text-align: center">Child 1</td>
                                <td style="text-align: center">Child 2</td>
                                <td style="text-align: center">Infant</td>
                                <td style="text-align: center">Pr. Room</td>
                            </tr>
                            <?php foreach ($this->list_group_size_by_tour_id AS $group_size) { ?>
                                <?php

                                $tour_price_by_tour_price_id = $this->list_tour_price_by_tour_price_id[$group_size->virtuemart_group_size_id];
                                $price_adult = isset($tour_price_by_tour_price_id->price_adult) ? $tour_price_by_tour_price_id->price_adult : 0;
                                $price_infant = isset($tour_price_by_tour_price_id->price_infant) ? $tour_price_by_tour_price_id->price_infant : 0;
                                $price_children = isset($tour_price_by_tour_price_id->price_children) ? $tour_price_by_tour_price_id->price_children : 0;
                                ?>
                                <tr>
                                    <td style="text-align: center"><?php echo $group_size->group_name ?></td>
                                    <td><input type="text" size="7" value="<?php echo $price_adult ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_adult]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_children ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_children ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children]"
                                               required="true" class="inputbox number"></td>

                                </tr>
                            <?php } ?>
                        </table>
                        <h3>Tax</h3>
                        <table class="table-bordered  table table-striped base-price">
                            <tr>
                                <td>Value</td>
                                <td style="text-align: center"><input type="text" style="width: 80%"></td>
                            </tr>
                        </table>
                        <h3>Sale price</h3>
                        <table class="table-bordered  table table-striped base-price">
                            <tr>
                                <td>Passenger</td>
                                <td style="text-align: center">Senior</td>
                                <td style="text-align: center">Adult</td>
                                <td style="text-align: center">Teen</td>
                                <td style="text-align: center">Child 1</td>
                                <td style="text-align: center">Child 2</td>
                                <td style="text-align: center">Infant</td>
                                <td style="text-align: center">Pr. Room</td>
                            </tr>
                            <?php foreach ($this->list_group_size_by_tour_id AS $group_size) { ?>
                                <?php

                                $tour_price_by_tour_price_id = $this->list_tour_price_by_tour_price_id[$group_size->virtuemart_group_size_id];
                                $price_adult = isset($tour_price_by_tour_price_id->price_adult) ? $tour_price_by_tour_price_id->price_adult : 0;
                                $price_infant = isset($tour_price_by_tour_price_id->price_infant) ? $tour_price_by_tour_price_id->price_infant : 0;
                                $price_children = isset($tour_price_by_tour_price_id->price_children) ? $tour_price_by_tour_price_id->price_children : 0;
                                ?>
                                <tr>
                                    <td style="text-align: center"><?php echo $group_size->group_name ?></td>
                                    <td><input type="text" size="7" value="<?php echo $price_adult ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_adult]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_infant ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_children ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children]"
                                               required="true" class="inputbox number"></td>
                                    <td><input type="text" size="7" value="<?php echo $price_children ?>"
                                               name="tour_price_by_tour_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children]"
                                               required="true" class="inputbox number"></td>

                                </tr>
                            <?php } ?>
                        </table>

                    </div>
                    <div class="span6">
                        <table class="table-bordered  table table-striped">
                            <tr>
                                <td>title</td>
                                <td colspan="4"><input type="text" size="16" value="<?php echo $this->price->title ?>" id="title"
                                                       required="true" name="title" class="inputbox number"></td>
                            </tr>
                            <tr>
                                <td>Service class</td>
                                <td colspan="4">
                                    <select name="virtuemart_service_class_id">
                                        <?php foreach ($this->list_service_class_by_tour_id as $service_class) { ?>
                                            <option
                                                value="<?php echo $service_class->virtuemart_service_class_id ?>"><?php echo $service_class->service_class_name ?></option>
                                        <?php } ?>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td>Sale periol</td>
                                <td>From</td>
                                <td nowrap>
                                    <?php echo vmJsApi::jDate($this->price->sale_period_from, 'sale_period_from'); ?>
                                </td>
                                <td>To</td>
                                <td nowrap>
                                    <?php echo vmJsApi::jDate($this->price->sale_period_to, 'sale_period_to'); ?>
                                </td>

                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-small btn-success" onclick="Joomla.submitbutton('apply')">
                                        <span class="icon-apply icon-white"></span>
                                        add new price</button>
                                </td>
                            </tr>

                        </table>

                    </div>
                </div>

            <?php } else { ?>
                <table class="table-bordered adminlist table table-striped">
                    <tr>
                        <td style="text-align: center">Adult</td>
                        <td style="text-align: center">children</td>
                        <td style="text-align: center">Infant</td>
                    </tr>
                    <tr>
                        <td><input type="text" size="7"
                                   value="<?php echo $this->tour_private_price_by_tour_price_id->price_adult ?>"
                                   name="price_adult" required="true" class="inputbox number"></td>
                        <td><input type="text" size="7"
                                   value="<?php echo $this->tour_private_price_by_tour_price_id->price_infant ?>"
                                   name="price_infant" required="true" class="inputbox number"></td>
                        <td><input type="text" size="7"
                                   value="<?php echo $this->tour_private_price_by_tour_price_id->price_children ?>"
                                   name="price_children" required="true" class="inputbox number"></td>
                    </tr>

                </table>


                <table class="table-bordered  table table-striped">
                    <tr>
                        <td>Private room suplement</td>
                        <td><input type="text" size="16" value="<?php echo $this->price->private_room_supplement ?>"
                                   id="private_room_supplement" required="true" name="private_room_supplement"
                                   class="inputbox number"></td>
                        <td>Discount for elder</td>
                        <td><input type="text" size="16" value="<?php echo $this->price->discount_for_elder ?>"
                                   id="discount_for_elder" required="true" name="discount_for_elder" class="inputbox number">
                        </td>
                        <td>Discount for teen</td>
                        <td><input type="text" size="16" value="<?php echo $this->price->discount_for_teen ?>"
                                   id="discount_for_teen" required="true" name="discount_for_teen" class="inputbox number">
                        </td>
                        <td>Price note</td>
                        <td><textarea id="price_note" cols="10" rows="3" name="price_note"></textarea></td>
                    </tr>


                </table>

            <?php } ?>


        </div>
        <input type="hidden" name="virtuemart_price_id" value="<?php echo $this->price->virtuemart_price_id; ?>"/>
        <input type="hidden" name="tour_methor" value="<?php echo $this->product->tour_methor; ?>"/>
        <input type="hidden" name="key[virtuemart_product_id]"
               value="<?php echo $this->product->virtuemart_product_id; ?>"/>
        <?php echo $this->addStandardHiddenToForm(); ?>
    </form>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#adminForm').view_price({});
        });
    </script>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>