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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_edit_form_add_passenger_to_transfer_add_on.less')
// set row counter
?>
<div class="view_edit_form_add_passenger_to_transfer_add_on form-horizontal">
    <div class="row-fluid ">
        <h3 class="text-uppercase"><span class="pull-right"><?php echo JText::sprintf('service name : %s',$this->tour->product_name) ?></span></h3>
        <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span8"><?php echo JText::_('Select number passenger') ?></div>
                    <div class="span4">
                        <select disable_chosen="true" class="total_passenger_transfer">

                        </select>
                    </div>
                </div>
            </div>
            <div class="span6">
            </div>
        </div>
        <div class="table-add-passenger-to-transfer">
            <div class="head-table">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="span6">
                                <span class="select-name"><?php echo JText::_('Select name') ?></span>
                            </div>
                            <div class="span3"><?php echo JText::_('Surcharge') ?></div>
                            <div class="span3"><?php echo JText::_('Discount') ?></div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="span3"><?php echo JText::_('Calculation') ?></div>
                            <div class="span9"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body-table-add-passenger-to-transfer">
                <div class="passenger-item">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="wrapper-select-passenger-item"><select disable_chosen="true" class="select-passenger-item"></select></div>
                                </div>
                                <div class="span3">
                                    <input type="text" class="surcharge">
                                </div>
                                <div class="span3">
                                    <input type="text" class="discount">

                                </div>
                            </div>

                        </div>
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="span5"><span class="cost">N/A</span></div>
                                <div class="span1">X</div>
                                <div class="span2"><?php echo JText::_('1 pers') ?></div>
                                <div class="span1">=</div>
                                <div class="span3"><span class="pull-right total_cost_per_persion">N/A</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="pull-right">
                    <?php echo JText::_('Total') ?> : <span class="total-for-all-passenger">N/A</span>
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
<!-- Product pricing -->


<div class="clear"></div>

