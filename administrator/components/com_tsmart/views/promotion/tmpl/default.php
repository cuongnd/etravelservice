<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
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

$doc->addScript(JUri::root() . '/media/system/js/datepicker/js/datepicker.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/jquery.dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js');
//$doc->addScript(JUri::root() . '/media/system/js/jquery.twbsPagination.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');

$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');


$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_promotion_default.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/clean.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_promotion_default.less');


AdminUIHelper::startAdminArea($this);

?>
<div class="view-promotion-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate ">
        <div class="row-fluid">
            <div class="span12">
                <div class="pagination">
                    <ul id="ul_pagination" class="pagination-sm">

                    </ul>
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
                    foreach ($this->promotion_prices as $promotion_price) {
                        ?>
                        <tr role="row" data-tsmart_promotion_price_id="<?php echo $promotion_price->tsmart_promotion_price_id ?>">
                            <td><label class="checkbox"><input type="checkbox" name="row_price_id[]"
                                                               value="<?php echo $promotion_price->tsmart_promotion_price_id ?>"
                                                               class="check-item"><span
                                        class="item-id"><?php echo $promotion_price->tsmart_promotion_price_id ?></span></label>
                            </td>
                            <td><?php echo $promotion_price->tour_name ?></td>
                            <td class="service_class_name"><?php echo $promotion_price->service_class_name ?></td>

                            <td class="sale_period"><?php echo JHtml::_('date', $promotion_price->sale_period_from, tsmConfig::$date_format); ?>
                                -<?php echo JHtml::_('date', $promotion_price->sale_period_to, tsmConfig::$date_format); ?></td>
                            <td><?php echo JHtml::_('date', $promotion_price->created_on, tsmConfig::$date_format); ?></td>
                            <td class="tour_type_name"><?php echo $promotion_price->tour_type_name ?></td>
                            <td class="modified_on"><?php echo JHtml::_('date', $promotion_price->modified_on, tsmConfig::$date_format); ?></td>
                            <td>
                                <a href="#price-form" class=" edit-price">
                                    <span class="icon-eye icon-white"></span>
                                </a>
                            </td>
                            <td class="price_note"><?php echo $promotion_price->price_note ?></td>
                            <td><a href="#price-form" class=" edit-price">
                                    <span class="icon-edit icon-white"></span>
                                </a>
                                <a href="#price-form" class=" publish-price">
                                    <span
                                        class="icon-<?php echo $promotion_price->published ? 'publish' : 'unpublish' ?> icon-white"></span>
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
                                <td ><select name="tsmart_product_id" id="tsmart_product_id">
                                        <option value="0">select tour</option>
                                        <?php foreach ($this->list_tour as $tour) { ?>
                                            <option
                                                value="<?php echo $tour->tsmart_product_id ?>"><?php echo $tour->product_name ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Service class</td>
                                <td >

                                    <?php echo VmHTML::select_service_class($this->list_service_class_by_tour_id,'tsmart_service_class_id',$this->price->service_class_id,''); ?>

                                </td>
                            </tr>


                            <tr>
                                <td>Range of date</td>
                                <td >
                                    <?php echo VmHTML::select_range_of_date(array(),'tsmart_price_id',$this->price->tsmart_price_id,'','vituemart_price_id','title'); ?>
                                </td>


                            </tr>
                            <tr>
                                <td>pro.  period</td>
                                <td >
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


                <div id="template_price">
                <?php echo $this->loadtemplate('price') ?>
                </div>
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
                <input type="hidden" name="tsmart_promotion_price_id" value="0">
                <input type="hidden" id="published" name="published" value="1"/>
            </div>

        </div>


        <input type="hidden" name="option" value="com_tsmart"/>
        <input type="hidden" name="controller" value="promotion"/>
        <input type="hidden" name="view" value="promotion"/>
        <input type="hidden" name="task" value=""/>

        <input type="hidden" name="key[tsmart_product_id]" value="<?php echo $this->tsmart_product_id; ?>"/>
        <input type="hidden" id="tsmart_product_id" name="tsmart_product_id" value="<?php echo $this->tsmart_product_id; ?>"/>

        <input type="hidden" name="cid[]" value="0"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-promotion-default').view_promotion_default({
                tour_id:<?php echo $this->tsmart_product_id ?>,
                totalItem:<?php echo count($this->prices) ?>,
                totalPages:<?php echo count($this->prices) ?>,
                date_format: "<?php echo JText::_('com_tsmart_DATE_FORMAT_INPUT_J16')  ?>",
                list_tour:<?php echo json_encode($this->list_service_class) ?>
            });
        });
    </script>
</div>
<?php AdminUIHelper::endAdminArea(); ?>