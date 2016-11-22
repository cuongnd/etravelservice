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
                            <?php echo VmHTML::input( 'hotel_name', $this->item->hotel_name, ' placeholder="hotel name" '); ?>
                            <?php echo VmHTML::input( 'title', $this->item->star_rating, ' placeholder="Star  rating"'); ?>
                            <?php echo VmHTML::location_city('tsmart_cityarea_id',$this->item->tsmart_cityarea_id,'') ; ?>
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
<<<<<<< master
                            <?php echo VmHTML::row_control('editor', 'Overview', 'overview', $this->item->overview,'40%', 20, 10, 20, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                            <?php echo VmHTML::row_control('editor', 'Hotel info', 'facility_info', $this->item->description,'40%', 20, 10, 20, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                            <?php echo VmHTML::row_control('editor', 'Room info', 'room_info', $this->item->description,'40%', 20, 10, 20, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
=======
                            <?php echo VmHTML::row_control('editor', 'Overview', 'overview', $this->item->overview,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                            <?php echo VmHTML::row_control('editor', 'Hotel info', 'facility_info', $this->item->description,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                            <?php echo VmHTML::row_control('editor', 'Room info', 'room_info', $this->item->description,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
>>>>>>> local
                        </div>
                    </div>
                </div>


            </div>
            <input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>"/>
            <input type="hidden" name="tsmart_hotel_id" value="<?php echo $this->item->tsmart_hotel_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>

    </div>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>