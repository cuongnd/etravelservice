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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_passenger_manager.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="row-fluid passenger-manager-control">
    <div class="span12">
        <div class="pull-right">
            <button class="btn btn-primary add_passenger" type="button"><span class="icon-plus"></span><?php echo JText::_('Add passenger') ?></button>
        </div>
    </div>
</div>
<div class="view_orders_edit_passenger_manager form-horizontal">
    <div class="row-fluid ">
        <div class="span12">

            <div class="vm-page-nav text-center text-uppercase">
                <h3 class="text-uppercase"><?php echo JText::_('Passenger manager') ?></h3>
            </div>
            <table class="adminlist table table-striped edit_passenger_manager" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_order_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('first_name', 'First name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('middle_name', 'Middle name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('last_name', 'Last name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('title', 'Title'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('gender', 'Gender'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('nationality', 'Nationality'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('dob', 'Dob'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('passport_no', 'Passport no'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('issue_date', 'Issue date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('expiry_date', 'Expiry date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('action', 'Action'); ?>
                    </th>
                </tr>
                </thead>

                <?php
                $render_tr=function($row,$index){
                    $checked = JHtml::_('grid.id', $index, $row->tsmart_passenger_id);
                    $editlink ="javascript:void(0)";
                    $edit = $this->gridEdit($row, $index, 'tsmart_cityarea_id', $editlink);
                    $delete = $this->grid_delete_in_line($row, $index, 'tsmart_cityarea_id');
                    ob_start();
                    ?>
                    <tr class="item-passenger" data-tsmart_passenger_id="<?php echo $row->tsmart_passenger_id ?>">
                        <td><div class="tsmart_passenger_id"><?php echo $checked ?></div></td>
                        <td ><span class="first_name"><?php echo $row->first_name ?></span></td>
                        <td ><span class="middle_name"><?php echo $row->middle_name  ?></span></td>
                        <td><span class=" last_name"><?php echo $row->last_name  ?></span></td>
                        <td><span class=" title"><?php echo $row->title  ?></span></td>
                        <td><span class=" gender"><?php echo $row->gender  ?></span></td>
                        <td><span class=" nationality"><?php echo $row->nationality  ?></span></td>
                        <td><span class=" date_of_birth"><?php echo JHtml::_('date', $row->date_of_birth, tsmConfig::$date_format);  ?></span></td>
                        <td><span class=" passport_no"><?php echo $row->passport_no?$row->passport_no:"N/A"  ?></span></td>
                        <td><span class=" issue_date"><?php echo $row->issue_date?$row->issue_date:"N/A"  ?></span></td>
                        <td><span class=" expiry_date"><?php echo $row->expiry_date?$row->expiry_date:"N/A"  ?></span></td>
                        <td align="center">
                            <?php echo $edit; ?>
                            <?php echo $delete; ?>

                        </td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                for ($i = 0, $n = count($this->list_passenger); $i < $n; $i++) {
                    $row = $this->list_passenger[$i];
                    $passenger_index = $row->passenger_index;
                    $row->booking = $this->item->tsmart_order_id;
                    $row->service_name = $this->tour->product_name;
                    $row->service_start_date = JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format);
                    $row->service_end_date = JHtml::_('date', $this->departure->departure_date_end, tsmConfig::$date_format);
                    $row->status = "CFM";
                    echo $render_tr($row, $i);
                }
                ?>
                </tbody>

            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

