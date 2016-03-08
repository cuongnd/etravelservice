<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.virtuemart.net
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
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_virtuemart/assets/less/view_hotel_edit.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', "hotel");
?>
    <div class="view-hotel-edit">
        <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">


            <div class="col50">
                <div class="main-info">
                    <h3>Hotel detail</h3>
                    <div class="row-fluid">
                        <div class="span6">
                            <?php echo VmHTML::input( 'title', $this->item->title, 'class="required" placeholder="hotel name" '); ?>
                            <?php echo VmHTML::input( 'title', $this->item->star_rating, 'class="required" placeholder="Star  rating"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->location, 'class="required" placeholder="location"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->address, 'class="required" placeholder="Address"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->google_map, 'class="required" placeholder="Google map"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->add_photo, 'class="required" placeholder="Add photo"'); ?>
                        </div>
                        <div class="span6">
                            <?php echo VmHTML::input( 'title', $this->item->tel_number, 'class="required" placeholder="Tell number"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->fax_number, 'class="required" placeholder="fax number"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->email, 'class="required" placeholder="E-mail add"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->website, 'class="required" placeholder="Website"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->reviews_api, 'class="required" placeholder="reviews API"'); ?>
                            <?php echo VmHTML::input( 'title', $this->item->hotel_code, 'class="required" placeholder="Hotel code"'); ?>

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
            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_hotel_id" value="<?php echo $this->item->virtuemart_hotel_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>

    </div>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>