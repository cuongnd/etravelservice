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
$app=JFactory::getApplication();
$input=$app->input;

$tsmart_product_id=$input->getInt('tsmart_product_id',0);
$key=$input->get('key',array(),'array');
$tsmart_product_id=$key['tsmart_product_id'];
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_itinerary_edit.less');
?>
<div class="view-itinerary-edit">
    <form action="index.php" method="post" class="form-horizontal" name="edit_admin_form" id="edit_admin_form">
        <div class="col50">
            <div class="admintable row-fluid">
                <div class="span12">
                    <?php echo VmHTML::row_control('input', 'Day title', 'title', $this->item->title, 'class="required"'); ?>
<<<<<<< master
                    <?php echo VmHTML::row_control('location_city','overnight', 'tsmart_cityarea_id',$this->item->tsmart_cityarea_id,'') ; ?>



                    <?php echo VmHTML::row_control('editor', 'Short description', 'short_description', $this->item->brief_itinerary,'40%', 20, 10, 20, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                    <?php echo VmHTML::row_control('editor', 'Full description', 'full_description', $this->item->meta_title,'40%', 20, 10, 20, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>


                    <?php echo VmHTML::row_control('textarea', 'Trip note 1', 'trip_note1', $this->item->trip_note1,'',90,4); ?>
                    <?php echo VmHTML::row_control('textarea', 'Trip note 2', 'trip_note2', $this->item->trip_note2,'',90,4); ?>
                    <?php echo VmHTML::row_control_v_1('list_checkbox', 'Meal','list_meal_id', 'list_meal_id', $this->list_meal, $this->item->list_meal_id, '', 'tsmart_meal_id', 'title', false); ?>
                    <?php echo VmHTML::image('Photo 1', 'photo1', $this->item->photo1, 'class="required"'); ?>
                    <?php echo VmHTML::image('Photo 2', 'photo2', $this->item->photo2, 'class="required"'); ?>
=======
                    <?php echo VmHTML::row_control('location_city','Overnight', 'tsmart_cityarea_id',$this->item->tsmart_cityarea_id,'') ; ?>
                    <?php echo VmHTML::row_control('editor', 'Short description', 'short_description', $this->item->short_description,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                    <?php echo VmHTML::row_control('editor', 'Full description', 'full_description', $this->item->full_description,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                    <?php echo VmHTML::row_control('editor', JText::_('Write day trip note'), 'trip_note', $this->item->trip_note,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                    <?php echo VmHTML::row_control_v_1('list_checkbox', 'Meal','list_meal_id','list_meal_id', $this->list_meal, $this->item->list_meal_id, '', 'tsmart_meal_id', 'meal_name', 3); ?>
                    <?php echo VmHTML::row_control('image', 'Photo 1', 'photo1', $this->item->photo1,'class="select-image"'); ?>
                    <?php echo VmHTML::row_control('image', 'Photo 2', 'photo2', $this->item->photo2,'class="select-image"'); ?>
                    <?php echo VmHTML::row_control('select_activity',JText::_('Select activity'), 'list_activity_id[]',$this->item->list_activity_id,'multiple="multiple"') ; ?>
>>>>>>> local
                    <?php echo VmHTML::row_control('booleanlist', 'com_tsmart_PUBLISHED', 'published', $this->item->published); ?>
                </div>


            </div>


        </div>
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="itinerary" name="controller">
        <input type="hidden" value="itinerary" name="view">
        <input type="hidden" value="" name="task">
        <input type="hidden" value="<?php echo $this->item->tsmart_product_id?$this->item->tsmart_product_id:$tsmart_product_id ?>" name="tsmart_product_id">
        <input type="hidden" value="<?php echo $this->item->tsmart_itinerary_id ?>" name="tsmart_itinerary_id">
        <input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>"/>
        <?php
        JHtml::_('form.token');
        ?>


    </form>
    <div class="pull-right">
        <?php
        $bar =  clone JToolbar::getInstance('toolbar');
        $bar->reset();
        $bar->appendButton('Standard', 'save', 'save', 'save', false,'edit_admin_form');
        $bar->appendButton('Standard', 'cancel', 'cancel', 'cancel', false);
        echo $bar->render();
        ?>
    </div>
</div>

