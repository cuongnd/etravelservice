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
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_transferaddon_edit.less');
$doc->addScript(JUri::root().'/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_transferaddon_edit.js');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-transferaddon-edit').view_transferaddon_edit({
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view-transferaddon-edit">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">
        <div class="row-fluid">
            <div class="span4">
                <h3> Service name</h3>
            </div>
            <div class="span8">
                <?php echo VmHTML::input( 'transfer_addon_name', $this->item->transfer_addon_name, 'class="required" placeholder="transfer name" '); ?>
                <br/>
                <?php echo VmHTML::location_city('tsmart_cityarea_id', $this->list_cityarea, $this->item->tsmart_cityarea_id, '', 'tsmart_cityarea_id', 'full_city'); ?>
                <h3>Edit term</h3>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::list_radio('transfer_type', $this->list_transfer_type, $this->item->transfer_type); ?>

                    </div>
                    <div class="span6">
                        <?php echo VmHTML::list_radio('transfer_payment_type', $this->list_transfer_payment_type, $this->item->transfer_payment_type); ?>

                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('select_from_to', 'Passenger allowance(Age to age)', 'passenger_age_from','passenger_age_to',$this->item->passenger_age_from, $this->item->passenger_age_to, 'class="required"'); ?>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('range_of_date','Valid date (Date to Date)', 'vail_from', 'vail_to', $this->item->vail_from,$this->item->vail_to); ?>

                    </div>
                </div>
                <h3>Edit price</h3>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span2"></div>
            <div class="span10">
                <?php echo VmHTML::edit_price_add_on('data_price',$this->item->data_price); ?>

            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo VmHTML::row_control('editor', 'Itinerary', 'itinerary', $this->item->itinerary, '100%', 10); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo VmHTML::row_control('editor', 'inclusion', 'inclusion', $this->item->inclusion, '100%', 10); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo VmHTML::row_basic('list_checkbox', 'select tour apply', 'list_tour_id', $this->list_tour, $this->item->list_tour_id, '', 'tsmart_product_id', 'product_name', false); ?>
            </div>
        </div>

        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(tsmart_transfer_addon_id => $this->item->tsmart_transfer_addon_id)); ?>
        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="transferaddon" name="controller">
        <input type="hidden" value="transferaddon" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="toolbar pull-right">
            <button class="btn btn-small btn-success save" type="submit"><span class="icon-save icon-white"></span>Save</button>
            <button class="btn btn-small btn-success reset" ><span class="icon-new icon-white"></span>Reset</button>
            <button class="btn btn-small btn-success cancel" ><span class="icon-new icon-white"></span>cancel</button>
        </div>

    </form>
</div>

