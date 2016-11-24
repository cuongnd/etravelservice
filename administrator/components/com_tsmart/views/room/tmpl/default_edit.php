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
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_room_edit.js');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_room_edit.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'Room');
$app=JFactory::getApplication();
$tsmart_hotel_id=$app->input->getInt('tsmart_hotel_id',0);
if(!$tsmart_hotel_id)
{
    $app->redirect('index.php?option=com_tsmart&view=hotel');
}
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
    <div class="view-room-edit">
        <form action="index.php" method="post" class="form-horizontal" name="edit_admin_form" id="edit_admin_form">


            <div class="col50">
                <fieldset>
                    <legend><?php echo tsmText::_('Current room'); ?></legend>
                    <div class="row-fluid">
                        <div class="span6">
                            <?php echo VmHTML::row_control('input',JText::_( 'Room name') , 'room_name', $this->item->room_name, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_( 'Room view'), 'room_view', $this->item->room_view, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('select',  JText::_( 'Include meal'), 'list_meal_id[]', $this->list_meal, $this->item->list_meal_id, 'multiple', 'tsmart_meal_id', 'meal_name', false); ?>
                            <?php echo VmHTML::row_control('select',  JText::_( 'Bedding type'), 'list_bed_id[]', $this->list_bed, $this->item->list_bed_id, 'multiple', 'tsmart_bed_id', 'bed_name', false); ?>
                        </div>
                        <div class="span6">
                            <?php echo VmHTML::row_control('input',JText::_('Single room price') , 'single_room_price', $this->item->single_room_price, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input',JText::_('Double room price') , 'double_room_price', $this->item->double_room_price, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input',JText::_('Extra beb price') , 'extra_beb_price', $this->item->extra_beb_price, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('image', 'Image 1','image1', $this->item->image1, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('image', 'Image 2','image2', $this->item->image2, 'class="required"'); ?>
                        </div>

                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo VmHTML::row_control('editor', 'Description', 'description', $this->item->description, '100%', 100); ?>
                            <?php echo VmHTML::row_control('editor', 'Facilities', 'facilities', $this->item->facilities, '100%', 100); ?>
                            <?php echo VmHTML::row_control('booleanlist', 'com_tsmart_PUBLISHED', 'published', $this->item->published); ?>
                        </div>
                    </div>
                </fieldset>

            </div>
            <input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>"/>
            <input type="hidden" name="tsmart_room_id"
                   value="<?php echo $this->item->tsmart_room_id; ?>"/>
            <input type="hidden" value="com_tsmart" name="option">
            <input type="hidden" value="room" name="controller">
            <input type="hidden" value="<?php echo $tsmart_hotel_id ?>" name="tsmart_hotel_id">
            <input type="hidden" value="<?php echo $this->item->tsmart_room_id ?>" name="tsmart_room_id">
            <input type="hidden" value="room" name="view">
            <input type="hidden" value="" name="task">

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
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>