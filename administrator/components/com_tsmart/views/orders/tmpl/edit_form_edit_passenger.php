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
$i = 0;
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
                        <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?php echo $i ?>"><?php echo JText::sprintf("%s pers",$i)?></option>
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
                            <?php for($i=1;$i<count($this->list_passenger_not_in_room);$i++){ ?>
                                <?php
                                $passenger=$this->list_passenger_not_in_room[$i];
                                ?>
                                <option value="<?php echo $passenger->tsmart_passenger_id ?>"><?php echo TSMUtility::get_full_name($passenger) ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="span8">
                        <div class="span3"><input class="passenger-type" type="text" disabled value="Adult"></div>
                        <div class="span3"><input class="discount" type="text"  value=""></div>
                        <div class="span3">
                            <select class="bed-type" disable_chosen="true">
                                <option value="private_bed"><?php echo JText::_("Private beb") ?></option>
                                <option value="sharing_bed"><?php echo JText::_("Sharing beb") ?></option>
                            </select>
                        </div>
                        <div class="span3"><input class="extra-fee" type="text"  value=""></div>
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

