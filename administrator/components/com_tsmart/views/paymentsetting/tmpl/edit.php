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
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_paymentsetting_edit.js');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'paymentsetting');

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-paymentsetting-edit').view_paymentsetting_edit({
                add_new_popup:<?php echo $this->add_new_popup ?>
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
    <div class="view-paymentsetting-edit">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


            <div class="col50">
                <fieldset>
                    <legend><?php echo tsmText::_('Current paymentsetting'); ?></legend>
                    <div class="admintable row-fluid">
                        <?php echo VmHTML::row_control('input', 'paymentsetting name', 'paymentsetting_name', $this->item->paymentsetting_name, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('image', 'Image 1','image1', $this->item->image1, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('image', 'Image 2','image2', $this->item->image2, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('editor', 'Description', 'description', $this->item->description, '100%', 100); ?>
                        <?php echo VmHTML::row_control('editor', 'Facilities', 'facilities', $this->item->facilities, '100%', 100); ?>
                        <?php echo VmHTML::row_control('booleanlist', 'com_tsmart_PUBLISHED', 'published', $this->item->published); ?>

                    </div>
                </fieldset>

            </div>
            <input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>"/>
            <input type="hidden" name="tsmart_paymentsetting_id"
                   value="<?php echo $this->item->tsmart_paymentsetting_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>