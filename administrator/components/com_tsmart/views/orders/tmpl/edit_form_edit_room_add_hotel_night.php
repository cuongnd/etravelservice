<?php
/**
 *
 * Main product information
 *
 * @package    tsmart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2015 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 8982 2015-09-14 09:45:02Z Milbo $
 */
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_edit_room.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_edit_room form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Rooming') ?></h3>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_order_id', 'Id'); ?>
                        </label>

                    </th>
                    <th width="20%">
                        <?php echo $this->sort('customer_name', 'Passenger'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('title', 'title'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('room_type', 'Room type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('room_note', 'Room note'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Creation'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Action') ?>
                    </th>
                </tr>
                </thead>
                <?php
                $render_tr=function($row){
                    $i=0;
                    $checked = JHtml::_('grid.id', $i, $row->tsmart_room_order_id);
                    ob_start();
                    ?>
                    <tr class="room_order_item" data-tsmart_room_order_id="<?php echo $row->tsmart_room_order_id ?>">
                        <td><?php echo $checked ?></td>
                        <td><?php echo $row->names ?></td>
                        <td><?php echo $row->titles  ?></td>
                        <td><?php echo $row->room_type  ?></td>
                        <td><?php echo $row->room_note  ?></td>
                        <td><?php echo $row->creation  ?></td>
                        <td><a class="delete" href="javascript:void(0)"><span class="icon-delete"></span></a></td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                foreach($this->list_roomming as $item){
                    $passengers=$this->passenger_helper->get_list_passenger_by_room_oder_id($item->tsmart_room_order_id);
                    if(!count($passengers))
                        continue;
                    $title=array();
                    $names=array();
                    $room_notes=array();
                    foreach($passengers as $passenger){
                        $title[]=TSMUtility::get_title_passenger($passenger->year_old);
                        $names[]=TSMUtility::get_full_name($passenger)."($passenger->tsmart_passenger_id)";
                        if($passenger->room_note)
                            $room_notes[]=$passenger->room_note;
                    }
                    $row->tsmart_room_order_id=$item->tsmart_room_order_id;
                    $row->creation=$item->created_on;
                    $row->room_type=$item->room_type;
                    $row->titles=implode("<br/>",$title);
                    $row->names=implode("<br/>",$names);
                    $row->room_note=count($room_notes)?implode(",",$room_notes):'';
                    echo $render_tr($row);
                }


                ?>
            </table>
            <h4><?php echo JText::_('List passenger unset room') ?></h4>
            <div class="row">
                <div class="span12">
                    <ul class="list_passenger_not_in_temporary_and_not_in_room">
                        <?php foreach($this->list_passenger_not_in_temporary_and_not_in_room as $passenger){
                            $year_old=TSMUtility::get_year_old_by_date($passenger->date_of_birth);

                            ?>
                            <li class="tag passenger"><?php echo TSMUtility::get_full_name($passenger) ?>(<?php echo JText::sprintf("%s year",$year_old) ?>)</li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="span12">
                    <div class="pull-right">
                    <button class="btn btn-primary show-first-history"><span class="icon-redo"></span><?php echo JText::_('Show first history') ?></button>
                    <button class="btn btn-primary show-near-last-one-history"><span class="icon-redo"></span><?php echo JText::_('Show near the last one history') ?></button>
                    <button class="btn btn-primary show-last-history"><span class="icon-redo"></span><?php echo JText::_('Show last history') ?></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="span12">
                    <?php
                    foreach($this->list_passenger_not_in_temporary_and_not_in_room as &$passenger){
                        $passenger->year_old=TSMUtility::get_year_old_by_date($passenger->date_of_birth);
                    }
                    ?>
                    <?php echo VmHtml::_('buil_rooming_list.rooming_list',$this->list_passenger_not_in_temporary_and_not_in_room,'add_hotel_add_on_rooming_list','',$this->departure,$this->passenger_config,$this->debug);?>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

