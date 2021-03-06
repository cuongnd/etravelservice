<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_coupon_edit.less');
$doc->addScript(JUri::root() . '/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_coupon_edit.js');

$this->item->model_price=$this->item->model_price?$this->item->model_price:'flat_price';
?>
<div class="view-coupon-edit">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">
        <div class="general-information">
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="title"><?php echo JText::_('General information') ?></h3>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('input', 'Coupon name', 'coupon_name', $this->item->coupon_name, ' placeholder="Coupon name"  '); ?>
                </div>
                <div class="span6">
                    <?php echo VmHTML::row_control('generate_code', 'Generate code', 'coupon_code', $this->item->coupon_code,'coupon','get_coupon_code',$this->item->coupon_code?true:false); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('image', 'Add photo', 'image', $this->item->image); ?>
                    <?php echo VmHTML::row_control('range_of_date', 'Set validity', 'coupon_start_date','coupon_expiry_date', $this->item->coupon_start_date,$this->item->coupon_expiry_date); ?>
                </div>
                <div class="span6">
                    <?php echo VmHTML::row_control('textarea', 'Coupon description', 'description', $this->item->description); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('input_number', 'Number of user', 'coupon_used', $this->item->coupon_used); ?>
                </div>
                <div class="span6">
                    <?php echo VmHTML::row_control('input_number', 'Use per customer', 'use_per_customer', $this->item->use_per_customer); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('input_percent_or_amount', 'Coupon value', 'coupon_value','percent_or_total',$this->item->coupon_value, $this->item->percent_or_total); ?>

                </div>
                <div class="span6">
                    <div class="exclude-area">
                        <div class="exclude-title" ><?php echo JText::_('Exclude') ?></div>
                        <div class="row-fluid">
                            <div class="pull-left">
                                <?php echo VmHTML::row_control('checkbox', 'Coupon products', 'exclude_coupon_product',$this->item->exclude_coupon_product); ?>
                            </div>
                            <div class="pull-right">
                                <?php echo VmHTML::row_control('checkbox', 'Discount products', 'exclude_discount_product', $this->item->exclude_discount_product); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="application-and-trip-type">
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('select_group_product', 'Application to', 'group_product', $this->item->group_product); ?>
                </div>
                <div class="span6">
                    <?php echo VmHTML::row_control('select_model_price', 'Price model', 'model_price', $this->item->model_price); ?>
                </div>
            </div>
        </div>
        <div class="select-trip">
            <h4 class="title"><?php  echo JText::_('Select trip') ?></h4>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo VmHTML::select_table_tour('tsmart_product_id',array(),array(),false); ?>
                </div>
            </div>
        </div>
        <div class="form-select-service-class">
            <h4 class="title"><?php  echo JText::_('Select service class') ?></h4>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo VmHTML::select_table_service_class('list_service_class',array(),$this->item->list_service_class_id); ?>
                </div>


            </div>
        </div>
        <div class="form-select-departure-date">
            <h4 class="title"><?php  echo JText::_('Departure date') ?></h4>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo VmHTML::select_table_departure_date('list_departure_date',array(),$this->item->list_departure_id); ?>
                </div>
            </div>
        </div>
        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(tsmart_hotel_addon_id => $this->item->tsmart_hotel_addon_id)); ?>
        <input type="hidden" value="<?php echo $this->item->tsmart_coupon_id  ?>" name="tsmart_coupon_id">
        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="coupon" name="controller">
        <input type="hidden" value="coupon" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="toolbar pull-right">
            <button class="btn btn-small btn-success save" type="submit"><span class="icon-save icon-white"></span>Save
            </button>
            <button class="btn btn-small btn-success reset"><span class="icon-new icon-white"></span>Reset</button>
            <button class="btn btn-small btn-success cancel"><span class="icon-new icon-white"></span>cancel</button>
        </div>
    </form>
</div>
<?php
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-coupon-edit').view_coupon_edit({
                model_price:"<?php echo $this->item->model_price ?>",
                tsmart_product_id:<?php echo $this->item->tsmart_product_id ?>
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

