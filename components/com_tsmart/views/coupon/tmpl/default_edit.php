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
$doc->addScript(JUri::root().'/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_coupon_edit.js');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-coupon-edit').view_coupon_edit({
            hotel:<?php echo json_encode($this->hotel) ?>

        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view-coupon-edit">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">
        <?php
        $span_left='span4';
        $span_right='span6';
        ?>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Coupon name') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::input( 'coupon_name', $this->item->coupon_name, ' placeholder="Coupon name" '); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Generate code') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::generate_code( 'coupon_code', $this->item->coupon_code); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Description') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::textarea( 'Description', $this->item->coupon_name, ' placeholder="Coupon name" '); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Select picture') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::image('image', $row->image, 'class="required"'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Set validity') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::range_of_date('coupon_start_date','coupon_expiry_date' ); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <h4><?php echo JText::_('Coupon detail') ?></h4>
            </div>
            <div class="<?php echo $span_right ?>">
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Number of user') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::input('coupon_used',$this->item->$this->item->coupon_name); ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Use per customer') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::input( 'use_per_customer', $this->item->use_per_customer, ' placeholder="Number of user" '); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Discount value') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::input( 'coupon_value_valid', $this->item->coupon_value_valid, ' placeholder="Number of user" '); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Select unit') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                <?php echo VmHTML::select_type_percent_or_amount('percent_or_total', $this->item->coupon_name); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Exclusion') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                Exclusion here
            </div>
        </div>
        <div class="row-fluid">
            <div class="<?php echo $span_left ?>">
                <?php echo JText::_('Apply to') ?>
            </div>
            <div class="<?php echo $span_right ?>">
                Apply to here
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo VmHTML::select_trip_join_and_private('coupon',$this->item); ?>
            </div>
        </div>


        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(tsmart_hotel_addon_id => $this->item->tsmart_hotel_addon_id)); ?>
        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="coupon" name="controller">
        <input type="hidden" value="1" name="published">

        <input type="hidden" value="coupon" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="toolbar pull-right">
            <button class="btn btn-small btn-success save" type="submit"><span class="icon-save icon-white"></span>Save</button>
            <button class="btn btn-small btn-success reset" ><span class="icon-new icon-white"></span>Reset</button>
            <button class="btn btn-small btn-success cancel" ><span class="icon-new icon-white"></span>cancel</button>
        </div>

    </form>
</div>

