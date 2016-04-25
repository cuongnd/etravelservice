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


$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_promotion_default.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/clean.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_virtuemart/assets/less/view_promotion_default.less');


AdminUIHelper::startAdminArea($this);

?>

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
                        <tr role="row" data-price_id="<?php echo $promotion_price->virtuemart_promotion_price_id ?>">
                            <td><label class="checkbox"><input type="checkbox" name="row_price_id[]"
                                                               value="<?php echo $promotion_price->virtuemart_promotion_price_id ?>"
                                                               class="check-item"><span
                                        class="item-id"><?php echo $promotion_price->virtuemart_promotion_price_id ?></span></label>
                            </td>
                            <td><?php echo $promotion_price->tour_name ?></td>
                            <td class="service_class_name"><?php echo $promotion_price->service_class_name ?></td>
                            <td class="sale_period"><?php echo JHtml::_('date', $promotion_price->sale_period_from, 'd M. Y'); ?>
                                -<?php echo JHtml::_('date', $promotion_price->sale_period_to, 'd M. Y'); ?></td>
                            <td><?php echo JHtml::_('date', $promotion_price->created_on, 'd M. Y'); ?></td>
                            <td class="modified_on"><?php echo JHtml::_('date', $promotion_price->modified_on, 'd M. Y'); ?></td>
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
                                <td colspan="4"><select name="select_virtuemart_product_id" id="select_virtuemart_product_id">
                                        <option value="0">select tour</option>
                                        <?php foreach ($this->list_tour as $tour) { ?>
                                            <option
                                                value="<?php echo $tour->virtuemart_product_id ?>"><?php echo $tour->product_name ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Service class</td>
                                <td colspan="4">
                                    <select name="virtuemart_service_class_id" id="virtuemart_service_class_id">
                                        <option value="0">select service class</option>
                                        <?php foreach ($this->list_service_class_by_tour_id as $service_class) { ?>
                                            <option <?php echo $service_class->virtuemart_service_class_id == $this->price->service_class_id ? 'selected' : '' ?>
                                                value="<?php echo $service_class->virtuemart_service_class_id ?>"><?php echo $service_class->service_class_name ?></option>
                                        <?php } ?>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td>Sale periol</td>
                                <td>From</td>
                                <td nowrap>
                                    <?php echo vmJsApi::jDate('', 'sale_period_from'); ?>
                                </td>
                                <td>To</td>
                                <td nowrap>
                                    <?php echo vmJsApi::jDate('', 'sale_period_to'); ?>
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
                <input type="hidden" name="virtuemart_product_id" value=""/>
                <input type="hidden" name="virtuemart_promotion_price_id" value="0">
                <input type="hidden" id="tour_methor" name="tour_methor" value=""/>
            </div>

        </div>


        <input type="hidden" name="option" value="com_virtuemart"/>
        <input type="hidden" name="controller" value="promotion"/>
        <input type="hidden" name="view" value="promotion"/>
        <input type="hidden" name="task" value=""/>

        <input type="hidden" name="key[virtuemart_product_id]" value="<?php echo $this->virtuemart_product_id; ?>"/>
        <input type="hidden" id="virtuemart_product_id" name="virtuemart_product_id" value="<?php echo $this->virtuemart_product_id; ?>"/>

        <input type="hidden" name="cid[]" value="0"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#adminForm').view_promotion_default({
                tour_id:<?php echo $this->virtuemart_product_id ?>,
                totalItem:<?php echo count($this->prices) ?>,
                totalPages:<?php echo count($this->prices) ?>,
                date_format: "<?php echo JText::_('COM_VIRTUEMART_DATE_FORMAT_INPUT_J16')  ?>"
            });
        });
    </script>
<?php AdminUIHelper::endAdminArea(); ?>