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
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_room_edit.js');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'Room');

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-room-edit').view_room_edit({
                add_new_popup:<?php echo $this->add_new_popup ?>
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
    <div class="view-room-edit">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


            <div class="col50">
                <fieldset>
                    <legend><?php echo tsmText::_('Current room'); ?></legend>
                    <div class="admintable row-fluid">
                        <?php echo VmHTML::row_control('input', 'Room name', 'room_name', $this->item->room_name, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('image', 'Image 1','image1', $this->item->image1, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('image', 'Image 2','image2', $this->item->image2, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('editor', 'Description', 'description', $this->item->description, '100%', 100); ?>
                        <?php echo VmHTML::row_control('editor', 'Facilities', 'facilities', $this->item->facilities, '100%', 100); ?>
                        <?php echo VmHTML::row_control('booleanlist', 'com_tsmart_PUBLISHED', 'published', $this->item->published); ?>

                    </div>
                </fieldset>

            </div>
            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_room_id"
                   value="<?php echo $this->item->virtuemart_room_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>