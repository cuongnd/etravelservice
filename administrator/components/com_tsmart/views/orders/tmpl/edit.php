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
TSMHtmlJquery::zozo_tab();
TSMHtmlJquery::delorean();
$this->debug=$this->tsmutility_helper->get_debug();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_orders_edit.less');
$doc->addScript(JUri::root().'/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_orders_edit.js');
AdminUIHelper::startAdminArea($this);

$order_data=json_decode($this->item->order_data);
$this->order_data=$order_data;
$this->passenger_config=$this->order_data->passenger_config;
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

$input=JFactory::getApplication()->input;
$this->tab_selected=$input->getString('tab','general');
$this->list_tab=array(
    general=>"General",
    active=>"Active",
    passenger=>"Passenger",
    finance=>"Finance",
    conversation=>"Conversation",
);
$this->debug=TSMUtility::get_debug();
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
    <div class="order_book_add_on hide">
        <?php echo $this->loadTemplate('form_book_add_on') ?>
    </div>
    <div class="form_order_add_hoc_item hide">
        <?php echo $this->loadTemplate('form_order_add_hoc_item') ?>
    </div>
    <div class="form_order_voucher_center hide">
        <?php echo $this->loadTemplate('form_order_voucher_center') ?>
    </div>
    <div class="form_order_add_flight hide">
        <?php echo $this->loadTemplate('form_order_add_flight') ?>
    </div>
    <div class="order_edit_excursion hide">

        <?php echo $this->loadTemplate('form_excursion') ?>
    </div>
    <div class="order_edit_transfer hide">

        <?php echo $this->loadTemplate('form_transfer') ?>
    </div>
    <div class="order_edit_night_hotel hide">

        <?php echo $this->loadTemplate('form_night_hotel') ?>
    </div>

    <div class="order_edit_main_tour hide">

        <?php echo $this->loadTemplate('form') ?>
    </div>
    <div class="order_edit_passenger hide">
        <?php echo $this->loadTemplate('form_edit_passenger') ?>
    </div>
    <div class="order_edit_passenger_cost_edit_hotel_add_on hide">
        <?php echo $this->loadTemplate('form_edit_passenger_cost_hotel_add_on') ?>
    </div>
    <div class="order_edit_passenger_cost hide">
        <?php echo $this->loadTemplate('form_edit_passenger_cost') ?>
    </div>
    <div class="order_edit_room hide">
        <?php echo $this->loadTemplate('form_edit_room') ?>
    </div>
    <div class="order_hotel_add_on_edit_rooming hide">
        <?php echo $this->loadTemplate('order_hotel_add_on_edit_rooming') ?>
    </div>
    <div class="edit_form_general_main_tour_popup hide">
        <?php echo $this->loadTemplate('form_general_popup_main_tour') ?>
    </div>
    <div class="order_form_add_and_remove_room_edit_hotel_addon hide">
        <?php echo $this->loadTemplate('form_add_and_remove_room_edit_hotel_addon') ?>
    </div>
    <div class="order_form_add_and_remove_passenger hide">
        <?php echo $this->loadTemplate('form_add_and_remove_passenger') ?>
    </div>

    <div class="order_edit_form_show_first_history_rooming hide">
        <?php echo $this->loadTemplate('form_show_first_history_rooming') ?>
    </div>
    <div class="order_edit_form_show_near_last_history_rooming hide">
        <?php echo $this->loadTemplate('form_show_near_last_history_rooming') ?>
    </div>
    <div class="order_edit_form_show_last_history_rooming hide">
        <?php echo $this->loadTemplate('form_show_last_history_rooming') ?>
    </div>
    <div class="order_form_edit_night_hotel hide">
        <?php echo $this->loadTemplate('form_order_form_edit_night_hotel') ?>
    </div>

    <div class="form_edit_general_transfer_popup hide">
        <?php echo $this->loadTemplate('form_edit_general_transfer_popup') ?>
    </div>
    <div class="form_and_passenger_to_transfer_add_on hide">
        <?php echo $this->loadTemplate('form_add_passenger_to_transfer_add_on') ?>
    </div>
    <div class="form_edit_passenger_cost_transfer_add_on hide">
        <?php echo $this->loadTemplate('form_edit_passenger_cost_transfer_add_on') ?>
    </div>
    <div class="form_edit_general_excursion_popup hide">
        <?php echo $this->loadTemplate('form_general_edit_excursion_addon_popup') ?>
    </div>
    <div class="order_edit_passenger_cost_edit_excursion_add_on hide">
        <?php echo $this->loadTemplate('form_edit_passenger_cost_excursion_add_on') ?>
    </div>
    <div class="form_add_and_passenger_to_excurson_add_on hide">
        <?php echo $this->loadTemplate('form_add_passenger_to_excursion_add_on') ?>
    </div>
</div>

<?php AdminUIHelper::endAdminArea(); ?>
<?php
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-orders-edit').view_orders_edit({
                list_passenger_not_in_room:<?php echo json_encode($this->list_passenger_not_in_room) ?>,
                departure:<?php echo json_encode($this->departure) ?>,
                debug:<?php echo json_encode($this->debug) ?>
            });
        });
    </script>
<?php
$utility=tsmHelper::getHepler('utility');
$js_content = ob_get_clean();
$js_content = $utility->remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
