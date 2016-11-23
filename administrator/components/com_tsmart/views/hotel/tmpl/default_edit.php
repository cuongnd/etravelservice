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
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_hotel_edit.less');


?>
    <div class="view-hotel-edit">
        <form action="index.php" method="post" class="form-vertical" name="edit_admin_form" id="edit_admin_form">


            <div class="col50">
                <div class="main-info">
                    <h3>Hotel detail</h3>
                    <div class="row-fluid">
                        <div class="span6">
                            <?php echo VmHTML::row_control('input',JText::_('write hotel name'), 'hotel_name', $this->item->hotel_name, ' placeholder="hotel name" '); ?>
                            <?php echo VmHTML::row_control('location_city',JText::_('City location'),'tsmart_cityarea_id',$this->item->tsmart_cityarea_id,'') ; ?>
                            <?php echo VmHTML::row_control( 'image',JText::_('Hotel photo 1'),'image1', $this->item->image1, ' placeholder="'.JText::_('Hotel photo 1').'"'); ?>
                            <?php echo VmHTML::row_control( 'image',JText::_('Hotel photo 2'),'image2', $this->item->image2, ' placeholder="'.JText::_('Hotel photo 2').'"'); ?>
                            <?php echo VmHTML::row_control( 'image',JText::_('Hotel photo 3'),'image3', $this->item->image3, ' placeholder="'.JText::_('Hotel photo 3').'"'); ?>
                            <?php echo VmHTML::row_control( 'image',JText::_('Hotel photo 4'),'image4', $this->item->image4, ' placeholder="'.JText::_('Hotel photo 4').'"'); ?>
                            <?php echo VmHTML::row_control( 'image',JText::_('Hotel photo 5'),'image5', $this->item->image5, ' placeholder="'.JText::_('Hotel photo 5').'"'); ?>
                        </div>
                        <div class="span6">
                            <?php echo VmHTML::row_control( 'input',JText::_('hotel address'),'address', $this->item->address, ' placeholder="Address"'); ?>
                            <?php echo VmHTML::row_control( 'input',JText::_('Star rating'),'star_rating', $this->item->star_rating, ' placeholder="'.JText::_('Star ratting').'"'); ?>
                            <?php echo VmHTML::row_control('input',JText::_('Hotel telephone'), 'tel_number', $this->item->tel_number, ' placeholder="Tell number"'); ?>
                            <?php echo VmHTML::row_control( 'input',JText::_('Hotel fax'),'fax_number', $this->item->fax_number, ' placeholder="fax number"'); ?>
                            <?php echo VmHTML::row_control( 'input',JText::_('Hotel email'),'email', $this->item->email, ' placeholder="E-mail add"'); ?>
                              <?php echo VmHTML::row_control('input',JText::_('Hotel website'), 'website', $this->item->website, ' placeholder="Website"'); ?>
                            <?php echo VmHTML::row_control('input',JText::_('Review API'), 'reviews_api', $this->item->reviews_api, ' placeholder="reviews API"'); ?>
                            <?php echo VmHTML::row_control('input',JText::_('Review rand'), 'review_rand', $this->item->review_rand, ' placeholder="Hotel review rand"'); ?>

                        </div>
                    </div>
                </div>

                <div class="addition-info">
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo VmHTML::row_control('editor', 'Overview', 'overview', $this->item->overview,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                            <?php echo VmHTML::row_control('editor', 'Hotel facility', 'facility_info', $this->item->facility_info,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                            <?php echo VmHTML::row_control('editor', 'Room facility', 'room_facility', $this->item->room_facility,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                        </div>
                    </div>
                </div>


            </div>
            <input type="hidden" value="com_tsmart" name="option">
            <input type="hidden" value="hotel" name="controller">
            <input type="hidden" value="hotel" name="view">
            <input type="hidden" value="" name="task">

            <input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>"/>
            <input type="hidden" name="tsmart_hotel_id" value="<?php echo $this->item->tsmart_hotel_id; ?>"/>
        </form>
        <div class="pull-right">
            <?php
            $bar =  clone JToolbar::getInstance('toolbar');
            $bar->reset();
            $bar->appendButton('Standard', 'save', 'save', 'save', false,'edit_admin_form');
            $bar->appendButton('Standard', 'cancel', 'cancel', 'cancel', false,'edit_admin_form');
            echo $bar->render();
            ?>
        </div>

    </div>
<?php
