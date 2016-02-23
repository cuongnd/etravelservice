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
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_virtuemart/assets/less/view_itinerary_edit.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', "itinerary");
?>
<div class="view-itinerary-edit">
    <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


        <div class="col50">
            <div class="admintable row-fluid">
                <div class="span12">
                    <?php echo VmHTML::row_control('input', 'Day title', 'title', $this->item->title, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('select','overnight', 'overnight', $this->cities ,$this->item->overnight,'','virtuemart_cityarea_id', 'title',false) ; ?>
                    <?php echo VmHTML::row_control('editor', 'Short description', 'short_description', $this->item->brief_itinerary,'',90,4); ?>
                    <?php echo VmHTML::row_control('editor', 'Full description', 'full_description', $this->item->meta_title,'',90,4); ?>


                    <?php echo VmHTML::row_control('textarea', 'Trip note 1', 'trip_note1', $this->item->trip_note1,'',90,4); ?>
                    <?php echo VmHTML::row_control('textarea', 'Trip note 2', 'trip_note2', $this->item->trip_note2,'',90,4); ?>
                    <?php echo VmHTML::row_control_v_1('list_checkbox', 'Meal','list_meal_id', 'list_meal_id', $this->list_meal, $this->item->list_meal_id, '', 'virtuemart_meal_id', 'title', false); ?>
                    <?php echo VmHTML::image('Photo 1', 'photo1', $this->item->photo1, 'class="required"'); ?>
                    <?php echo VmHTML::image('Photo 2', 'photo2', $this->item->photo2, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('booleanlist', 'COM_VIRTUEMART_PUBLISHED', 'published', $this->item->published); ?>

                </div>


            </div>


        </div>
        <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
        <input type="hidden" name="virtuemart_itinerary_id"
               value="<?php echo $this->item->virtuemart_itinerary_id; ?>"/>
        <?php echo VmHTML::inputHidden(array(
            'key[virtuemart_product_id]'=>$this->virtuemart_product_id
        )); ?>

        <?php echo $this->addStandardHiddenToForm(); ?>
    </form>
</div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>