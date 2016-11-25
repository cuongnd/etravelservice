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
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_hoteladdon_edit.less');
$doc->addScript(JUri::root().'/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_hoteladdon_edit.js');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-hoteladdon-edit').view_hoteladdon_edit({
            hotel:<?php echo json_encode($this->hotel) ?>

        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view-hoteladdon-edit">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">
        <div class="row-fluid">
            <div class="span12">
                <h3 class="title"><?php echo JText::_('Hotel information') ?></h3>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <?php echo VmHTML::row_control('select', JText::_('Hotel name'),'tsmart_hotel_id', $this->list_hotel,$this->item->tsmart_hotel_id,' ', 'tsmart_hotel_id','hotel_name'); ?>
                <?php echo VmHTML::row_control('list_radio', 'Add night type','hotel_addon_type', $this->list_hotel_addon_type, $this->item->hotel_addon_type); ?>
            </div>
            <div class="span6">
                <?php echo VmHTML::row_control('input', 'Hotel location','location', $this->hotel->city_area_name, ' placeholder="location" readonly '); ?>
                <?php echo VmHTML::row_control('select_service_class', 'Service class',array(),'tsmart_service_class_id',$this->item->tsmart_service_class_id,''); ?>

            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <?php echo VmHTML::row_control('textarea', 'Included service','included_service', $this->item->included_service,'',100,4); ?>
            </div>
            <div class="span6">
                <?php echo VmHTML::row_control('textarea', 'Hotel note','hoteladdon_note', $this->item->hoteladdon_note,'',100,4); ?>

            </div>
        </div>
        <div class="hoteladdon-body">
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="title"><?php echo JText::_('Edit hotel terms') ?></h3>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('range_of_date','Valid date (Date to Date)', 'vail_from', 'vail_to', $this->item->vail_from,$this->item->vail_to); ?>
                </div>
                <div class="span6">
                    <?php echo VmHTML::list_radio('hotel_payment_type', $this->list_hotel_payment_type, $this->item->hotel_payment_type); ?>

                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo VmHTML::edit_price_hotel_add_on('data_price',$this->item->data_price); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo VmHTML::row_control('select_from_to', 'Passenger allowance(Age to age)', 'passenger_age_from','passenger_age_to',$this->item->passenger_age_from, $this->item->passenger_age_to, 'class="required"'); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo VmHTML::row_basic('select_table_tour', 'select tour apply', 'list_tour_id',array(), $this->item->list_tour_id); ?>
                </div>
            </div>

        </div>


        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(tsmart_hotel_addon_id => $this->item->tsmart_hotel_addon_id)); ?>
        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="hoteladdon" name="controller">
        <input type="hidden" value="hoteladdon" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="toolbar pull-right">
            <button class="btn btn-small btn-success save" type="submit"><span class="icon-save icon-white"></span>Save</button>
            <button class="btn btn-small btn-success reset" ><span class="icon-new icon-white"></span>Reset</button>
            <button class="btn btn-small btn-success cancel" ><span class="icon-new icon-white"></span>cancel</button>
        </div>

    </form>
</div>

