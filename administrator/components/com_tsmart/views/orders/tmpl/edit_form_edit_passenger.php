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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_edit_passenger.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// set row counter
?>
<div class="view_orders_edit_form_edit_passenger form-horizontal">
    <div class="row-fluid ">
        <h3 class="text-uppercase"><span class="pull-right"><?php echo JText::sprintf('service name : %s',$this->tour->product_name) ?></span></h3>
        <div class="span12">
            <div class="row-fluid">
                <div class="span4">
                    <?php echo JText::_('Select number of passenger') ?>
                </div>
                <div class="span8">
                    <select class="number-passenger" disable_chosen="true">
                        <?php for($i=0;$i<count($this->list_passenger_not_in_room)-1;$i++){ ?>
                            <option value="<?php echo $i+1 ?>"><?php echo JText::sprintf("%s pers",$i+1)?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <?php echo JText::_("select name") ?>
                </div>
                <div class="span8">
                    <div class="row-fluid">
                        <div class="span3"><?php echo JText::_('passenger type') ?></div>
                        <div class="span3"><?php echo JText::_('Discount') ?></div>
                        <div class="span3"><?php echo JText::_('Select room') ?></div>
                        <div class="span3"><?php echo JText::_('Extra fee') ?></div>
                    </div>
                </div>
            </div>
            <div class="wrapper-passenger">
                <div class="row-fluid passenger-item">
                    <div class="span4">
                        <select class="list-passenger" disable_chosen="true">
                            <option value="0"><?php echo JText::_("Select passenger") ?></option>
                            <?php for($i=0;$i<count($this->list_passenger_in_temporary);$i++){ ?>
                                <?php
                                $passenger=$this->list_passenger_in_temporary[$i];

                                $year_old=TSMUtility::get_year_old_by_date($passenger->date_of_birth);
                                ?>
                                <option value="<?php echo $passenger->tsmart_passenger_id ?>"><?php echo TSMUtility::get_full_name($passenger) ?> (<?php echo JText::sprintf("%s years",$year_old) ?>)</option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="span8">
                        <div class="row-fluid">
                            <div class="span3"><input class="passenger-type" type="text" disabled value="Adult"></div>
                            <div class="span3"><input class="discount cost" type="text"  value=""></div>
                            <div class="span3">
                                <select class="bed-type" disable_chosen="true">
                                    <option value="private_bed"><?php echo JText::_("Private beb") ?></option>
                                    <option value="sharing_bed"><?php echo JText::_("Sharing beb") ?></option>
                                </select>
                            </div>
                            <div class="span3"><input class="extra-fee cost" type="text"  value=""></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper-calculator">
                <div class="row-fluid ">
                    <div class="span4">
                        <h4><?php echo JText::_('calculator') ?></h4>
                    </div>
                    <div class="span8">
                        <div class="row-fluid passenger-item-calculator">
                            <div class="span5"><span class="person-name">N/A</span> : (<span class="cost tour-cost">N/A</span></div><div class=" span1 text-center">x</div><div class="span4">1 per)+ <span class="cost extra-fee">N/A</span> - <span class="cost discount">N/A</span></div><div class="span2">=<div class="pull-right"><span class="cost passenger-total-cost">N/A</span></div></div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid ">
                    <div class="span4"></div>
                    <div class="span8">
                        <div class="pull-right"><?php echo JText::_('Total') ?>: <span class="cost full-total">N/A</span></div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary save"><?php echo JText::_('Save') ?></button>
                        <button type="button" class="btn btn-primary cancel"><?php echo JText::_('Cancel') ?></button>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

