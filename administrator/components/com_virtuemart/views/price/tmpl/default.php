<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
$format_date=VmConfig::$date_format;// 'd-m-Y';
require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmgroupsize.php';
$doc->addScript(JUri::root() . '/media/system/js/datepicker/js/datepicker.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/jquery.dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js');
//$doc->addScript(JUri::root() . '/media/system/js/jquery.twbsPagination.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');

$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');


$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_price_default.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/clean.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_virtuemart/assets/less/view_price_default.less');


AdminUIHelper::startAdminArea($this);

?>
<div class="view-price-default">
    <?php echo vmproduct::get_html_tour_information($this,$this->virtuemart_product_id); ?>
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate ">
        <div class="row-fluid" style="padding-top: 0px">
            <div class="span12">
                <div class="vm-page-nav">

                </div>

                <table class="table-bordered  table table-striped list-prices">
                    <thead>
                    <tr>
                        <th><label class="checkbox"><input type="checkbox" class="check-all">Id</label></th>
                        <th>tour name</th>
                        <th>service class</th>
                        <th>vailid</th>
                        <th>tour type</th>

                        <th>add</th>
                        <th>amend</th>
                        <th>price</th>
                        <th>note</th>
                        <th colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->prices as $price) {
                        ?>
                        <tr role="row" data-virtuemart_price_id="<?php echo $price->virtuemart_price_id ?>">
                            <td><label class="checkbox"><input type="checkbox" name="row_virtuemart_price_id[]"
                                                               value="<?php echo $price->virtuemart_price_id ?>"
                                                               class="check-item"><span
                                        class="item-id"><?php echo $price->virtuemart_price_id ?></span></label></td>
                            <td><?php echo $this->product->product_name ?></td>
                            <td class="service_class_name"><?php echo $price->service_class_name ?></td>

                            <td class="sale_period"><?php echo JHtml::_('date', $price->sale_period_from, $format_date); ?>
                                 <span class="icon-next"></span> <?php echo JHtml::_('date', $price->sale_period_to, $format_date); ?></td>
                            <td class="tour_type_name"><?php echo $price->tour_type_name ?></td>
                            <td><?php echo JHtml::_('date', $price->created_on, $format_date); ?></td>
                            <td class="modified_on"><?php echo JHtml::_('date', $price->modified_on, $format_date); ?></td>
                            <td>
                                <a href="#price-form" class=" edit-price">
                                    <span class="icon-eye icon-white"></span>
                                </a>
                            </td>
                            <td class="price_note"><?php echo $price->price_note ?></td>
                            <td><a href="#price-form" class=" edit-price">
                                    <span class="icon-edit icon-white"></span>
                                </a>
                                <a href="#price-form" class=" publish-price">
                                    <span
                                        class="icon-<?php echo $price->published ? 'publish' : 'unpublish' ?> icon-white"></span>
                                </a>
                                <a href="#price-form" class=" delete-price">
                                    <span class="icon-delete icon-white"></span>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>


            </div>
        </div>
        <div id="price-form" class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <div class="span8">
                        <table class="table-bordered  table table-striped">
                            <tr>
                                <td>Tour</td>
                                <td colspan="4"><?php echo $this->product->product_name ?></td>
                            </tr>
                            <tr>
                                <td>Service class</td>
                                <td colspan="4">

                                    <?php echo VmHTML::select_service_class($this->list_service_class_by_tour_id,'virtuemart_service_class_id',$this->price->service_class_id,''); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>Sale periol</td>
                                <td colspan="4">
                                    <?php echo VmHTML::range_of_date('sale_period_from', 'sale_period_to', $this->price->sale_period_from,$this->price->sale_period_to); ?>
                                </td>


                            </tr>

                        </table>


                    </div>
                    <div class="span4">
                        <div> Price node</div>
                                    <textarea class="price-note" name="price_note" style="width: 80%; height: 100%">

                                    </textarea>
                    </div>
                </div>

                <?php if ($this->product->price_type != vmGroupSize::FLAT_PRICE) { ?>
                    <div class="row-fluid">
                        <div class="span12">

                            <h3>NET PRICE <button class="btn btn-primary random-price">random price</button></h3>
                            <table class="table-bordered  table table-striped base-price">
                                <thead>
                                <tr>
                                    <td>Passenger</td>
                                    <td style="text-align: center">Senior</td>
                                    <td style="text-align: center">Adult</td>
                                    <td style="text-align: center">Teen</td>
                                    <td style="text-align: center">Child 1</td>
                                    <td style="text-align: center">Child 2</td>
                                    <td style="text-align: center">Infant</td>
                                    <td style="text-align: center">Pr. Room</td>
                                    <td style="text-align: center">Extra bed</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_group=count($this->list_group_size_by_tour_id);
                                ?>
                                <?php for ($i=0;$i<$total_group;$i++) { ?>
                                    <?php
                                    $group_size=$this->list_group_size_by_tour_id[$i];
                                    $tour_price_by_tour_price_id = $this->list_tour_price_by_tour_price_id[$group_size->virtuemart_group_size_id];
                                    $price_senior = $tour_price_by_tour_price_id->price_senior;
                                    $price_adult = $tour_price_by_tour_price_id->price_adult;
                                    $price_teen = $tour_price_by_tour_price_id->price_teen;
                                    $price_children1 = $tour_price_by_tour_price_id->price_children1;
                                    $price_children2 = $tour_price_by_tour_price_id->price_children2;
                                    $price_infant = $tour_price_by_tour_price_id->price_infant;
                                    $price_private_room = $tour_price_by_tour_price_id->price_private_room;
                                    $price_extra_bed = $tour_price_by_tour_price_id->price_extra_bed;
                                    ?>
                                    <tr role="row"
                                        data-group_size_id="<?php echo $group_size->virtuemart_group_size_id ?>">
                                        <td style="text-align: center"><?php echo $group_size->group_type==vmGroupSize::FLAT_PRICE?JText::_('Flat price'):$group_size->group_name ?></td>
                                        <td>
                                            <input type="hidden" name="tour_price_by_tour_price_id[<?php echo $i ?>][virtuemart_group_size_id]" value="<?php echo $group_size->virtuemart_group_size_id ?>">
                                            <input required="true"
                                                   group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="senior" type="text" size="7"
                                                   value="<?php echo $price_senior ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_senior]"
                                                   required="true" class="inputbox number price_senior"></td>
                                        <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="adult" type="text" size="7"
                                                   value="<?php echo $price_adult ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_adult]"
                                                   required="true" class="inputbox number price_adult"></td>
                                        <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="teen" type="text" size="7"
                                                   value="<?php echo $price_teen ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_teen]"
                                                   required="true" class="inputbox number price_teen"></td>
                                        <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="children1" type="text" size="7"
                                                   value="<?php echo $price_children1 ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_children1]"
                                                   required="true" class="inputbox number price_children1"></td>
                                        <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="children2" type="text" size="7"
                                                   value="<?php echo $price_children2 ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_children2]"
                                                   required="true" class="inputbox number price_children2"></td>
                                        <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="infant" type="text" size="7"
                                                   value="<?php echo $price_infant ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_infant]"
                                                   required="true" class="inputbox number price_infant"></td>
                                        <?php if($i==0){ ?>
                                        <td rowspan="<?php echo $total_group ?>" style="vertical-align: middle" ><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="private_room" type="text" size="7"
                                                   value="<?php echo $price_private_room ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_private_room]"
                                                   required="true" class="inputbox number price_private_room"></td>
                                        <td rowspan="<?php echo $total_group ?>" style="vertical-align: middle"><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                   column-type="price_extra_bed" type="text" size="7"
                                                   value="<?php echo $price_extra_bed ?>"
                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_extra_bed]"
                                                   required="true" class="inputbox number price_extra_bed"></td>
                                        <?php } ?>

                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td colspan="10">
                                            <label>Full charge children 1 <input type="checkbox" name="full_charge_children1" value="<?php echo $this->price->full_charge_children1 ?>"></label>
                                            <label>Full charge children 2 <input type="checkbox" name="full_charge_children2" value="<?php echo $this->price->full_charge_children2 ?>"></label>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <h3>mark up <button class="btn btn-primary random-markup">random markup</button></h3>
                            <table class="table-bordered  table table-striped mark-up-price">
                                <tr>
                                    <td>MARK UP VALUE</td>
                                    <td style="text-align: center">Senior</td>
                                    <td style="text-align: center">Adult</td>
                                    <td style="text-align: center">Teen</td>
                                    <td style="text-align: center">Child 1</td>
                                    <td style="text-align: center">Child 2</td>
                                    <td style="text-align: center">Infant</td>
                                    <td style="text-align: center">Pr. Room</td>
                                    <td style="text-align: center">Extra bed</td>
                                </tr>
                                <tr class="amount">
                                    <td>Amout</td>
                                    <td style="text-align: center"><input name="amount[senior]"
                                                                          value="<?php echo $this->list_mark_up['amount']->senior ?>"
                                                                          class="inputbox number" column-type="senior"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="amount[adult]"
                                                                          value="<?php echo $this->list_mark_up['amount']->adult ?>"
                                                                          class="inputbox number" column-type="adult"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="amount[teen]"
                                                                          value="<?php echo $this->list_mark_up['amount']->teen ?>"
                                                                          class="inputbox number" column-type="teen"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="amount[children1]"
                                                                          value="<?php echo $this->list_mark_up['amount']->children1 ?>"
                                                                          class="inputbox number"
                                                                          column-type="children1" type="text"></td>
                                    <td style="text-align: center"><input name="amount[children2]"
                                                                          value="<?php echo $this->list_mark_up['amount']->children2 ?>"
                                                                          class="inputbox number"
                                                                          column-type="children2" type="text"></td>
                                    <td style="text-align: center"><input name="amount[infant]"
                                                                          value="<?php echo $this->list_mark_up['amount']->infant ?>"
                                                                          class="inputbox number" column-type="infant"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="amount[private_room]"
                                                                          value="<?php echo $this->list_mark_up['amount']->private_room ?>"
                                                                          class="inputbox number"
                                                                          column-type="private_room" type="text"></td>
                                    <td style="text-align: center"><input name="amount[extra_bed]"
                                                                          value="<?php echo $this->list_mark_up['amount']->extra_bed ?>"
                                                                          class="inputbox number"
                                                                          column-type="extra_bed" type="text"></td>
                                </tr>
                                <tr class="percent">
                                    <td>percent</td>
                                    <td style="text-align: center"><input name="percent[senior]"
                                                                          value="<?php echo $this->list_mark_up['percent']->senior ?>"
                                                                          class="inputbox number" column-type="senior"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="percent[adult]"
                                                                          value="<?php echo $this->list_mark_up['percent']->adult ?>"
                                                                          class="inputbox number" column-type="adult"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="percent[teen]"
                                                                          value="<?php echo $this->list_mark_up['percent']->teen ?>"
                                                                          class="inputbox number" column-type="teen"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="percent[children1]"
                                                                          value="<?php echo $this->list_mark_up['percent']->children1 ?>"
                                                                          class="inputbox number"
                                                                          column-type="children1" type="text"></td>
                                    <td style="text-align: center"><input name="percent[children2]"
                                                                          value="<?php echo $this->list_mark_up['percent']->children2 ?>"
                                                                          class="inputbox number"
                                                                          column-type="children2" type="text"></td>
                                    <td style="text-align: center"><input name="percent[infant]"
                                                                          value="<?php echo $this->list_mark_up['percent']->infant ?>"
                                                                          class="inputbox number" column-type="infant"
                                                                          type="text"></td>
                                    <td style="text-align: center"><input name="percent[private_room]"
                                                                          value="<?php echo $this->list_mark_up['percent']->private_room ?>"
                                                                          class="inputbox number"
                                                                          column-type="private_room" type="text"></td>
                                    <td style="text-align: center"><input name="percent[extra_bed]"
                                                                          value="<?php echo $this->list_mark_up['percent']->extra_bed ?>"
                                                                          class="inputbox number"
                                                                          column-type="extra_bed" type="text"></td>
                                </tr>
                            </table>
                            <h3>PROFIT</h3>
                            <table class="table-bordered  table table-striped profit-price">
                                <tr>
                                    <td>Passenger</td>
                                    <td style="text-align: center">Senior</td>
                                    <td style="text-align: center">Adult</td>
                                    <td style="text-align: center">Teen</td>
                                    <td style="text-align: center">Child 1</td>
                                    <td style="text-align: center">Child 2</td>
                                    <td style="text-align: center">Infant</td>
                                    <td style="text-align: center">Pr. Room</td>
                                    <td style="text-align: center">Extra bed</td>
                                </tr>

                                <?php for ($i=0;$i<$total_group;$i++ ) { ?>
                                <?php
                                    $group_size=$this->list_group_size_by_tour_id[$i];
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo $group_size->group_type==vmGroupSize::FLAT_PRICE?JText::_('Flat price'):$group_size->group_name ?></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="senior"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="adult"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="teen"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="children1"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="children2"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="infant"></span></td>
                                        <?php if($i==0){ ?>
                                        <td  rowspan="<?php echo $total_group ?>" style="vertical-align: middle"> <span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="private_room"></span></td>
                                        <td  rowspan="<?php echo $total_group ?>" style="vertical-align: middle"><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="extra_bed"></span></td>
                                        <?php } ?>


                                    </tr>
                                <?php } ?>
                            </table>
                            <h3>Tax</h3>
                            <table class="table-bordered  table table-striped tax-price">
                                <tr>
                                    <td>Value</td>
                                    <td style="text-align: center"><input type="text" class="inputbox number" name="tax"
                                                                          value="<?php echo $this->price->tax ?>"
                                                                          style="width: 80%"></td>
                                </tr>
                            </table>
                            <h3>Sale price</h3>
                            <table class="table-bordered  table table-striped sale-price">
                                <tr>
                                    <td>Passenger</td>
                                    <td style="text-align: center">Senior</td>
                                    <td style="text-align: center">Adult</td>
                                    <td style="text-align: center">Teen</td>
                                    <td style="text-align: center">Child 1</td>
                                    <td style="text-align: center">Child 2</td>
                                    <td style="text-align: center">Infant</td>
                                    <td style="text-align: center">Pr. Room</td>
                                    <td style="text-align: center">Extra bed</td>
                                </tr>
                                <?php for ($i=0;$i<$total_group;$i++ ) {

                                $group_size=$this->list_group_size_by_tour_id[$i];
                                ?>

                                    <tr>
                                        <td style="text-align: center"><?php echo $group_size->group_type==vmGroupSize::FLAT_PRICE?JText::_('Flat price'):$group_size->group_name ?></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="senior"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="adult"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="teen"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="children1"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="children2"></span></td>
                                        <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="infant"></span></td>
                                        <?php if($i==0){ ?>
                                        <td rowspan="<?php echo $total_group ?>" style="vertical-align: middle"><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="private_room"></span></td>
                                        <td rowspan="<?php echo $total_group ?>" style="vertical-align: middle"> <span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                  column-type="extra_bed"></span></td>
                                        <?php } ?>

                                    </tr>
                                <?php } ?>
                            </table>

                        </div>
                        <div class="span6">

                        </div>
                    </div>

                <?php } else { ?>
                    <h3>NET PRICE<button class="btn btn-primary random-price">random price</button></h3>
                    <table class="table-bordered  table table-striped base-price">
                        <thead>
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
                        </thead>
                        <tbody>
                        <?php
                        $total_group=count($this->list_group_size_by_tour_id);
                        ?>
                        <?php for ($i=0;$i<$total_group;$i++) { ?>
                            <?php
                            $group_size=$this->list_group_size_by_tour_id[$i];
                            $tour_price_by_tour_price_id = $this->list_tour_price_by_tour_price_id[$group_size->virtuemart_group_size_id];
                            $price_senior = $tour_price_by_tour_price_id->price_senior;
                            $price_adult = $tour_price_by_tour_price_id->price_adult;
                            $price_teen = $tour_price_by_tour_price_id->price_teen;
                            $price_children1 = $tour_price_by_tour_price_id->price_children1;
                            $price_children2 = $tour_price_by_tour_price_id->price_children2;
                            $price_infant = $tour_price_by_tour_price_id->price_infant;
                            $price_private_room = $tour_price_by_tour_price_id->price_private_room;
                            $price_extra_bed = $tour_price_by_tour_price_id->price_extra_bed;
                            ?>
                            <tr role="row"
                                data-group_size_id="<?php echo $group_size->virtuemart_group_size_id ?>">
                                <td style="text-align: center"><?php echo $group_size->group_type==vmGroupSize::FLAT_PRICE?JText::_('Flat price'):$group_size->group_name ?></td>
                                <td>
                                    <input type="hidden" name="tour_price_by_tour_price_id[<?php echo $i ?>][virtuemart_group_size_id]" value="<?php echo $group_size->virtuemart_group_size_id ?>">
                                    <input required="true"
                                           group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                           column-type="senior" type="text" size="7"
                                           value="<?php echo $price_senior ?>"
                                           name="tour_price_by_tour_price_id[<?php echo $i ?>][price_senior]"
                                           required="true" class="inputbox number price_senior"></td>
                                <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                           column-type="adult" type="text" size="7"
                                           value="<?php echo $price_adult ?>"
                                           name="tour_price_by_tour_price_id[<?php echo $i ?>][price_adult]"
                                           required="true" class="inputbox number price_adult"></td>
                                <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                           column-type="teen" type="text" size="7"
                                           value="<?php echo $price_teen ?>"
                                           name="tour_price_by_tour_price_id[<?php echo $i ?>][price_teen]"
                                           required="true" class="inputbox number price_teen"></td>
                                <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                           column-type="children1" type="text" size="7"
                                           value="<?php echo $price_children1 ?>"
                                           name="tour_price_by_tour_price_id[<?php echo $i ?>][price_children1]"
                                           required="true" class="inputbox number price_children1"></td>
                                <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                           column-type="children2" type="text" size="7"
                                           value="<?php echo $price_children2 ?>"
                                           name="tour_price_by_tour_price_id[<?php echo $i ?>][price_children2]"
                                           required="true" class="inputbox number price_children2"></td>
                                <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                           column-type="infant" type="text" size="7"
                                           value="<?php echo $price_infant ?>"
                                           name="tour_price_by_tour_price_id[<?php echo $i ?>][price_infant]"
                                           required="true" class="inputbox number price_infant"></td>
                                <?php if($i==0){ ?>
                                    <td rowspan="<?php echo $total_group ?>" style="vertical-align: middle" ><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                                                                                    column-type="private_room" type="text" size="7"
                                                                                                                    value="<?php echo $price_private_room ?>"
                                                                                                                    name="tour_price_by_tour_price_id[<?php echo $i ?>][price_private_room]"
                                                                                                                    required="true" class="inputbox number price_private_room"></td>
                                    <td rowspan="<?php echo $total_group ?>" style="vertical-align: middle"><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                                                                                                                   column-type="extra_bed" type="text" size="7"
                                                                                                                   value="<?php echo $extra_bed ?>"
                                                                                                                   name="tour_price_by_tour_price_id[<?php echo $i ?>][price_extra_bed]"
                                                                                                                   required="true" class="inputbox number price_extra_bed"></td>
                                <?php } ?>

                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="10">
                                <label>Full charge children 1 <input type="checkbox" name="full_charge_children1" value="1"></label>
                                <label>Full charge children 2 <input type="checkbox" name="full_charge_children2" value="1"></label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3>mark up NET PRICE<button class="btn btn-primary random-markup">random markup</button></h3>
                    <table class="table-bordered  table table-striped mark-up-price">
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
                        <tr class="amount">
                            <td>Amout</td>
                            <td style="text-align: center"><input name="amount[senior]"
                                                                  value="<?php echo $this->list_mark_up['amount']->senior ?>"
                                                                  class="inputbox number" column-type="senior"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="amount[adult]"
                                                                  value="<?php echo $this->list_mark_up['amount']->adult ?>"
                                                                  class="inputbox number" column-type="adult"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="amount[teen]"
                                                                  value="<?php echo $this->list_mark_up['amount']->teen ?>"
                                                                  class="inputbox number" column-type="teen"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="amount[children1]"
                                                                  value="<?php echo $this->list_mark_up['amount']->children1 ?>"
                                                                  class="inputbox number" column-type="children1"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="amount[children2]"
                                                                  value="<?php echo $this->list_mark_up['amount']->children2 ?>"
                                                                  class="inputbox number" column-type="children2"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="amount[infant]"
                                                                  value="<?php echo $this->list_mark_up['amount']->infant ?>"
                                                                  class="inputbox number" column-type="infant"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="amount[private_room]"
                                                                  value="<?php echo $this->list_mark_up['amount']->private_room ?>"
                                                                  class="inputbox number" column-type="private_room"
                                                                  type="text"></td>
                        </tr>
                        <tr class="percent">
                            <td>percent</td>
                            <td style="text-align: center"><input name="percent[senior]"
                                                                  value="<?php echo $this->list_mark_up['percent']->senior ?>"
                                                                  class="inputbox number" column-type="senior"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="percent[adult]"
                                                                  value="<?php echo $this->list_mark_up['percent']->adult ?>"
                                                                  class="inputbox number" column-type="adult"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="percent[teen]"
                                                                  value="<?php echo $this->list_mark_up['percent']->teen ?>"
                                                                  class="inputbox number" column-type="teen"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="percent[children1]"
                                                                  value="<?php echo $this->list_mark_up['percent']->children1 ?>"
                                                                  class="inputbox number" column-type="children1"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="percent[children2]"
                                                                  value="<?php echo $this->list_mark_up['percent']->children2 ?>"
                                                                  class="inputbox number" column-type="children2"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="percent[infant]"
                                                                  value="<?php echo $this->list_mark_up['percent']->infant ?>"
                                                                  class="inputbox number" column-type="infant"
                                                                  type="text"></td>
                            <td style="text-align: center"><input name="percent[private_room]"
                                                                  value="<?php echo $this->list_mark_up['percent']->private_room ?>"
                                                                  class="inputbox number" column-type="private_room"
                                                                  type="text"></td>
                        </tr>
                    </table>
                    <h3>PROFIT</h3>
                    <table class="table-bordered  table table-striped profit-price">
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
                        <tbody>
                        <tr role="row">
                            <td style="text-align: center">Value </td>
                            <td><span column-type="senior"></span></td>
                            <td><span column-type="adult"></span></td>
                            <td><span column-type="teen"></span></td>
                            <td><span column-type="children1"></span></td>
                            <td><span column-type="children2"></span></td>
                            <td><span column-type="infant"></span></td>
                            <td><span column-type="private_room"></span></td>


                        </tr>
                        </tbody>

                    </table>
                    <h3>Tax</h3>
                    <table class="table-bordered  table table-striped tax-price">
                        <tr>
                            <td>Value</td>
                            <td style="text-align: center"><input type="text" class="inputbox number" name="tax"
                                                                  value="<?php echo $this->price->tax ?>"
                                                                  style="width: 80%"></td>
                        </tr>
                    </table>
                    <h3>Sale price</h3>
                    <table class="table-bordered  table table-striped sale-price">
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
                        <tbody>
                        <tr role="row">
                            <td style="text-align: center"></td>
                            <td><span column-type="senior"></span></td>
                            <td><span column-type="adult"></span></td>
                            <td><span column-type="teen"></span></td>
                            <td><span column-type="children1"></span></td>
                            <td><span column-type="children2"></span></td>
                            <td><span column-type="infant"></span></td>
                            <td><span column-type="private_room"></span></td>

                        </tr>
                        </tbody>

                    </table>

                <?php } ?>
                <div class="row-fluid">
                    <div class="span12">
                        <button class="btn btn-small btn-success pull-right save-close-price">
                            <span class="icon-save icon-white"></span>
                            Save&close
                        </button>
                        <button class="btn btn-small btn-success pull-right apply-price">
                            <span class="icon-apply icon-white"></span>
                            apply
                        </button>
                        <button class="btn btn-small btn-success pull-right cancel-price">
                            <span class="icon-cancel icon-white"></span>
                            Cancel
                        </button>

                    </div>
                </div>
                <input type="hidden" name="virtuemart_product_id" value="<?php echo $this->virtuemart_product_id; ?>"/>
                <input type="hidden" name="virtuemart_price_id" value="0">
                <input type="hidden" name="published" value="1"/>
                <input type="hidden" name="tour_method" id="tour_method" value=""/>
            </div>

        </div>
        <input type="hidden" name="option" value="com_virtuemart"/>
        <input type="hidden" name="controller" value="price"/>
        <input type="hidden" name="view" value="price"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="key[virtuemart_product_id]" value="<?php echo $this->virtuemart_product_id; ?>"/>

        <input type="hidden" name="cid[]" value="0"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#adminForm').view_price_default({
                list_price:<?php echo json_encode($this->prices) ?>,
                flat_price:"<?php echo vmGroupSize::FLAT_PRICE ?>",
                tour_id:<?php echo $this->virtuemart_product_id ?>,
                totalItem:<?php echo count($this->prices) ?>,
                totalPages:<?php echo count($this->prices) ?>,

                price_type: "<?php echo $this->product->price_type ?>",
                date_format: "<?php echo JText::_('COM_VIRTUEMART_DATE_FORMAT_INPUT_J16')  ?>"
            });
        });
    </script>
<?php AdminUIHelper::endAdminArea(); ?>