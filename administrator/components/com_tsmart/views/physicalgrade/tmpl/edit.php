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
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_physicalgrade_edit.js');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'Tour class');

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-physicalgrade-edit').view_physicalgrade_edit({
                add_new_popup:<?php echo $this->add_new_popup ?>
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
    <div class="view-physicalgrade-edit">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


            <div class="col50">
                <fieldset>
                    <legend><?php echo tsmText::_('Current tour class'); ?></legend>
                    <div class="admintable row-fluid">
                        <?php echo VmHTML::row_control('input', 'physical grade name', 'title', $this->item->title, 'class="required"'); ?>
                        <?php echo VmHTML::image('Icon', 'icon', $this->item->icon, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('textarea', 'Meta title', 'meta_title', $this->item->meta_title); ?>
                        <?php echo VmHTML::row_control('textarea', 'Key Word', 'key_word', $this->item->key_word); ?>
                        <?php echo VmHTML::row_control('editor', 'Description', 'description', $this->item->description, '100%', 100); ?>

                        <?php echo VmHTML::row_control('booleanlist', 'com_tsmart_PUBLISHED', 'published', $this->item->published); ?>

                    </div>
                </fieldset>

            </div>
            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_physicalgrade_id"
                   value="<?php echo $this->item->virtuemart_physicalgrade_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>