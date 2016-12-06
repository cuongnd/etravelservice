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
$app=JFactory::getApplication();
$input=$app->input;
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_accommodation_edit.less');

$tsmart_itinerary_id=$input->getInt('tsmart_itinerary_id',0);
$tsmart_product_id=$input->getInt('tsmart_product_id',0);
?>
<div class="view-accommodation-edit">
    <form action="index.php" method="post" class="form-horizontal"  name="edit_admin_form" id="edit_admin_form">
        <div class="row-fluid">
            <div class="span4">
                <h3>select location</h3>
                <h4>add node</h4>
                <?php echo VmHTML::textarea( 'description', $this->item->description, 'class="required"', 30, 10); ?>
            </div>
            <div class="span8">
                <h4> add hotel</h4>
                <?php foreach($this->list_hotel_selected_by_service_class_id_and_itinerary_id AS $service_class){ ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="service-class-name"><?php echo $service_class->service_class_name ?></h4>
                            <input type="hidden" name="tsmart_service_class_id" value="<?php echo $service_class->tsmart_service_class_id; ?>"/>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php
                            $list_hotel=$service_class->list_hotel;
                            for($i=0;$i<2;$i++){
                                $hotel_service_class=$list_hotel[$i];
                            ?>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <?php
                                        $element_hotel_name='list_hotel_service_class['.$service_class->tsmart_service_class_id.']['.$i.']';
                                        $element_room_name='list_room_service_class['.$service_class->tsmart_service_class_id.']['.$i.']';
                                        ?>
                                        <?php echo VmHTML::select($element_hotel_name, tsmHotel::get_list_hotel_by_cityarea_id($this->itinerary->tsmart_cityarea_id),$hotel_service_class->tsmart_hotel_id,'', 'tsmart_hotel_id','hotel_name'); ?>
                                    </div>
                                    <div class="span6">
                                        <?php echo VmHTML::select_room($element_room_name, $this->list_room,$hotel_service_class->room_item->tsmart_room_id,'', 'tsmart_room_id','room_name','select[name="'.$element_hotel_name.'"]'); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>

        <input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>"/>
        <input type="hidden" name="tsmart_itinerary_id" value="<?php echo $tsmart_itinerary_id; ?>"/>
        <input type="hidden" name="tsmart_product_id" value="<?php echo $tsmart_product_id; ?>"/>
        <input type="hidden" name="tsmart_accommodation_id" value="<?php echo $this->item->tsmart_accommodation_id; ?>"/>
        <input type="hidden" name="key[tsmart_product_id]" value="<?php echo $tsmart_product_id; ?>"/>
        <input type="hidden" name="key[tsmart_itinerary_id]" value="<?php echo $tsmart_itinerary_id; ?>"/>

        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="accommodation" name="controller">
        <input type="hidden" value="accommodation" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="pull-right">
            <?php
            $bar =  clone JToolbar::getInstance('toolbar');
            $bar->reset();
            $bar->appendButton('Standard', 'save', 'save', 'save', false,'edit_admin_form');
            $bar->appendButton('Standard', 'cancel', 'cancel', 'cancel', false,'edit_admin_form');
            echo $bar->render();
            ?>
        </div>

    </form>

</div>