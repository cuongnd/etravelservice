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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_add_and_remove_passenger.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$date=JFactory::getDate();
// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_add_and_remove_passenger form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="row-fluid">
                <div class="span12">
                    <fieldset class="general">
                        <legend><?php echo JText::_('Information') ?></legend>
                        <div class="row-fluid ">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Title*'), 'title', $this->passenger->hotel_name,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('First name*'), 'first_name', $this->passenger->first_name,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Middle name*'), 'middle_name', $this->passenger->middle_name,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Last name*'), 'last_name', $this->passenger->last_name,' required="required" '); ?>
                                <?php echo VmHTML::row_control('list_checkbox',JText::_('Gender*'), 'gender',$this->list_gender, $this->passenger->gender,"","value","text",2); ?>
                                <?php echo VmHTML::row_control('select_date',JText::_('Date of birth*'), 'date_of_birth', $this->passenger->date_of_birth,"mm/dd/yy","mm/dd/yy","",$date->format("mm/dd/yy")); ?>
                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Nationality*'), 'nationality', $this->passenger->nationality,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Passport no*'), 'passport_no', $this->passenger->passport_no); ?>
                                <?php echo VmHTML::row_control('select_date',JText::_('P. Issue date*'), 'issue_date', $this->passenger->issue_date,"mm/dd/yy","mm/dd/yy","",$date->format("mm/dd/yy")); ?>
                                <?php echo VmHTML::row_control('select_date',JText::_('P. Expiry date*'), 'expiry_date', $this->passenger->expiry_date,"mm/dd/yy","mm/dd/yy","",$date->format("mm/dd/yy")); ?>
                            </div>
                        </div>
                        <h3><?php echo JText::_("Contact detail") ?></h3>
                        <div class="row-fluid ">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Phone no*'), 'phone_no', $this->passenger->phone_no,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Email address*'), 'email_address', $this->passenger->email_address,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Confirm email*'), 'confirm_email', $this->passenger->confirm_email,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Street address*'), 'street_address', $this->passenger->street_address,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Suburb/town*'), 'suburb_town', $this->passenger->suburb_town,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('State/province*'), 'state_province', $this->passenger->state_province,' required="required" '); ?>
                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Postcode/Zip*'), 'postcode_zip', $this->passenger->postcode_zip,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Res. country*'), 'res_country', $this->passenger->res_country,' required="required" '); ?>
                                <h4><?php echo JText::_("Emergency contact") ?></h4>
                                <?php echo VmHTML::row_control('input',JText::_('Contact name*'), 'emergency_contact_name', $this->passenger->emergency_contact_name,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Email address*'), 'emergency_contact_email', $this->passenger->emergency_contact_email,' required="required" '); ?>
                                <?php echo VmHTML::row_control('input',JText::_('Phone no*'), 'emergency_contact_phone_no', $this->passenger->emergency_contact_phone_no,' required="required" '); ?>

                            </div>
                        </div>
                        <div class="addition_information">
                            <h3><?php echo JText::_("addition information") ?></h3>
                            <div class="row-fluid ">
                                <div class="span12">

                                </div>
                            </div>
                        </div>
                        <input  type="hidden" name="tsmart_passenger_id" value="">
                    </fieldset>

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

