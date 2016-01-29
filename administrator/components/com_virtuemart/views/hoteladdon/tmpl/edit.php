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
                <fieldset>
                    <legend><?php echo vmText::_('Current hotel'); ?></legend>
                    <div class="admintable row-fluid">
                        <div class="span4">
                            <?php echo VmHTML::row_control('input', 'hotel name', 'title', $this->item->title, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'hotel address', 'address', $this->item->address, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'City/area', 'city_id', $this->item->city_id, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'Phone no', 'phone', $this->item->phone, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'Website', 'website', $this->item->website, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'Star rating', 'star_rating', $this->item->star_rating, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'Good map', 'google_map', $this->item->google_map, 'class="required"'); ?>

                        </div>
                        <div class="span4">
                            <?php echo VmHTML::row_control('textarea', 'Overview', 'overview', $this->item->overview, 'class="required"', 28, 4); ?>
                            <?php echo VmHTML::row_control('textarea', 'Room info', 'room_info', $this->item->room_info, 'class="required"', 28, 4); ?>
                            <?php echo VmHTML::row_control('textarea', 'Facility info', 'facility_info', $this->item->facility_info, 'class="required"', 28, 4); ?>

                        </div>
                        <div class="span4">
                            <?php echo VmHTML::row_control('range_of_date', 'vail', 'vail', $this->item->vail, '', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('list_option', 'Price type', 'price_type', array('group_price', 'flat_price'), $this->item->price_type, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'Flat price', 'flat_price', $this->item->flat_price, 'class="required"'); ?>
                            <?php
                            $item = new stdClass();
                            $item->name='single_room';
                            $item->text='Single room';
                            $item->value=$this->item->single_room;
                            $list_room_price[]=$item;

                            $item = new stdClass();
                            $item->name='double_room';
                            $item->text='Double room';
                            $item->value=$this->item->double_room;
                            $list_room_price[]=$item;

                            $item = new stdClass();
                            $item->name='triple_room';
                            $item->text='Triple room';
                            $item->value=$this->item->triple_room;
                            $list_room_price[]=$item;


                            ?>
                            <?php echo VmHTML::row_control('group_price','Room price' ,$list_room_price, true, 3); ?>



                            <?php echo VmHTML::image('hotel photo 1', 'hotel_photo1', $this->item->hotel_photo1, 'class="required"'); ?>
                            <?php echo VmHTML::image('hotel photo 2', 'hotel_photo2', $this->item->hotel_photo2, 'class="required"'); ?>
                            <?php echo VmHTML::image('facility photo 1', 'facility_photo1', $this->item->facility_photo1, 'class="required"'); ?>
                            <?php echo VmHTML::image('facility photo 1', 'facility_photo2', $this->item->facility_photo2, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('booleanlist', 'COM_VIRTUEMART_PUBLISHED', 'published', $this->item->published); ?>


                        </div>

                    </div>
                </fieldset>

            </div>
            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_hoteladdon_id" value="<?php echo $this->item->virtuemart_hoteladdon_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>

    </div>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>