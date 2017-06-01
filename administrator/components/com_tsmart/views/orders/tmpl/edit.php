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
TSMHtmlJquery::numeric();
TSMHtmlJquery::serializeobject();
TSMHtmlJquery::utility();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_orders_edit.less');
$doc->addScript(JUri::root().'/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_orders_edit.js');
AdminUIHelper::startAdminArea($this);

$order_data=json_decode($this->item->order_data);
$this->order_data=$order_data;

$list_passenger=$order_data->list_passenger;
$this->build_room=(array)$order_data->build_room;
//$this->list_passenger=array_merge($list_passenger->senior_adult_teen,$list_passenger->children_infant);
$this->tour=$order_data->tour;
$this->departure=$order_data->departure;
if(!$this->departure->departure_date_end) {
    $departure_date = $this->departure->departure_date;
    $departure_date = JFactory::getDate($departure_date);
    $departure_date_end = clone $departure_date;
    $total_day = $this->tour->tour_length;
    $total_day--;
    $departure_date_end->modify("+$total_day day");
    $this->departure->departure_date_end = $departure_date_end;
}
$build_pre_transfer=(array)$order_data->build_pre_transfer;
$build_post_transfer=(array)$order_data->build_post_transfer;
$this->transfer=array(
    pre_transfer=>$build_pre_transfer,
    post_transfer=>$build_post_transfer,
);
$extra_pre_night_hotel=(array)$order_data->extra_pre_night_hotel;
$extra_post_night_hotel=(array)$order_data->extra_post_night_hotel;
$this->night_hotel=array_merge($extra_pre_night_hotel,$extra_post_night_hotel);
$this->list_excursion_addon=(array)$order_data->build_excursion_addon;
$this->payment_rule=$order_data->payment_rule;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-orders-edit').view_orders_edit({
        });
    });
</script>
<?php
$utility=tsmHelper::getHepler('utility');
$js_content = ob_get_clean();
$js_content = $utility->remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

$input=JFactory::getApplication()->input;
$this->tab_selected=$input->getString('tab','general');
$this->list_tab=array(
    general=>"General",
    active=>"Active",
    passenger=>"Passenger",
    finance=>"Finance",
    conversation=>"Conversation",
);
?>
<div class="view-orders-edit">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $this->loadTemplate('menu') ?>
                    </div>
                </div>
                <div class="wrapper-booking-detail">
                    <div class="row-fluid">
                        <div class="span12">

                            <?php echo $this->loadTemplate('bookinginformation') ?>
                        </div>
                    </div>
                    <?php echo $this->loadTemplate("tab_$this->tab_selected") ?>

                </div>
            </div>
        </div>
        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(tsmart_transfer_addon_id => $this->item->tsmart_transfer_addon_id)); ?>
        <input type="hidden" value="<?php echo $this->item->tsmart_order_id ?>" name="tsmart_order_id">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="orders" name="controller">
        <input type="hidden" value="orders" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>

    </form>
    <div class="order_edit_main_tour hide">

        <?php echo $this->loadTemplate('form') ?>
    </div>
    <div class="order_edit_passenger hide">
        <?php echo $this->loadTemplate('form_edit_passenger') ?>
    </div>
    <div class="order_edit_passenger_cost hide">
        <?php echo $this->loadTemplate('form_edit_passenger_cost') ?>
    </div>
    <div class="order_edit_room hide">
        <?php echo $this->loadTemplate('form_edit_room') ?>
    </div>
    <div class="order_form_add_and_remove_passenger hide">
        <?php echo $this->loadTemplate('form_add_and_remove_passenger') ?>
    </div>
</div>

<?php AdminUIHelper::endAdminArea(); ?>

