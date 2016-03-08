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
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_virtuemart/assets/less/view_transferaddon_edit.less');
$doc->addScript(JUri::root().'/administrator/components/com_virtuemart/assets/js/view_transferaddon_edit.js');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-transferaddon-default').view_transferaddon_edit({
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
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
                <?php echo VmHTML::location_city('virtuemart_cityarea_id', $this->list_cityarea, $this->item->virtuemart_cityarea_id, '', 'virtuemart_cityarea_id', 'full_city'); ?>
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
                        <?php echo VmHTML::row_control('select_from_to', 'Passenger allowance(Age to age)', 'from_name','to_name',$this->item->passenger_age_from, $this->item->passenger_age_to, 'class="required"'); ?>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('range_of_date', 'valid date', 'vail', $this->item->vail, '', '', 'class="required"'); ?>

                    </div>
                </div>
                <h3>Edit price</h3>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span2"></div>
            <div class="span10">
                <?php echo VmHTML::edit_price_add_on('virtuemart_cityarea_id', $this->list_cityarea, $this->item->virtuemart_cityarea_id, '', 'virtuemart_cityarea_id', 'full_city'); ?>

            </div>
        </div>
        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(virtuemart_transferaddon_id => $this->item->virtuemart_transferaddon_id)); ?>
        <?php echo $this->addStandardHiddenToForm(); ?>
    </form>
  <div class="toolbar">
      <button class="btn btn-small btn-success" ><span class="icon-save icon-white"></span>Save</button>
      <button class="btn btn-small btn-success" ><span class="icon-new icon-white"></span>Reset</button>
  </div>
</div>

