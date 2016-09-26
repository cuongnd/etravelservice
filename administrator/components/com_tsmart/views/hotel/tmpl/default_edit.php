<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
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
                            <?php echo VmHTML::input( 'hotel_name', $this->item->hotel_name, ' placeholder="hotel name" '); ?>
                            <?php echo VmHTML::input( 'title', $this->item->star_rating, ' placeholder="Star  rating"'); ?>
                            <?php echo VmHTML::location_city('virtuemart_cityarea_id',$this->item->virtuemart_cityarea_id,'') ; ?>
                            <?php echo VmHTML::input( 'address', $this->item->address, ' placeholder="Address"'); ?>
                            <?php echo VmHTML::input( 'google_map', $this->item->google_map, ' placeholder="Google map"'); ?>
                            <?php echo VmHTML::input( 'add_photo', $this->item->add_photo, ' placeholder="Add photo"'); ?>
                        </div>
                        <div class="span6">
                            <?php echo VmHTML::input( 'tel_number', $this->item->tel_number, ' placeholder="Tell number"'); ?>
                            <?php echo VmHTML::input( 'fax_number', $this->item->fax_number, ' placeholder="fax number"'); ?>
                            <?php echo VmHTML::input( 'email', $this->item->email, ' placeholder="E-mail add"'); ?>
                              <?php echo VmHTML::input( 'website', $this->item->website, ' placeholder="Website"'); ?>
                            <?php echo VmHTML::input( 'reviews_api', $this->item->reviews_api, ' placeholder="reviews API"'); ?>
                            <?php echo VmHTML::input( 'hotel_code', $this->item->hotel_code, ' placeholder="Hotel code"'); ?>

                        </div>
                    </div>
                </div>

                <div class="addition-info">
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo VmHTML::row_control('editor', 'Overview', 'overview', $this->item->overview); ?>
                            <?php echo VmHTML::row_control('editor', 'Hotel info', 'facility_info', $this->item->description); ?>
                            <?php echo VmHTML::row_control('editor', 'Room info', 'room_info', $this->item->description); ?>
                        </div>
                    </div>
                </div>


            </div>
            <input type="hidden" value="com_tsmart" name="option">
            <input type="hidden" value="hotel" name="controller">
            <input type="hidden" value="hotel" name="view">
            <input type="hidden" value="" name="task">

            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_hotel_id" value="<?php echo $this->item->virtuemart_hotel_id; ?>"/>
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
