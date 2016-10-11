<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU company Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU company Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$input = $app->input;
$show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
$cid = $input->get('cid', array(), 'array');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=company&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'city_area_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$doc=JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_company_default.js');
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_company_default.less');
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');

AdminUIHelper::startAdminArea($this);
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-company-default').view_company_default({
                task: "<?php echo $task ?>"
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$list_default_type=array('senior','adult','teen','children_1','children_2','infant');

?>
    <div class="view-company-default">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">

            <h4 class="form-default-type"><?php echo JText::_('Company information') ?></h4>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('input', JText::_('Business name'), 'name', $this->config->name, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Business address'), 'address', $this->config->address, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('City name'), 'city', $this->config->city, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Zip code'), 'zip_code', $this->config->zip_code, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('State province'), 'state_province', $this->config->state_province, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Country'), 'country', $this->config->country, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Admin email'), 'admin_email', $this->config->admin_email, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Support email'), 'support_email', $this->config->support_email, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Sale Email'), 'sale_email', $this->config->sale_email, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('textarea', JText::_('Business description'), 'description', $this->item->description, 'class="required"',70,4); ?>

                    <?php echo VmHTML::row_control('image', JText::_('Portal logo'), 'logo', $this->config->logo, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', JText::_('Language setup'), 'language_id', $this->config->language_id, 'class="required"'); ?>
                </div>
                <div class="span6">
                    <?php echo VmHTML::row_control('input', JText::_('Policy file'), 'policy_file', $this->config->booking_policy, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('textarea', JText::_('Booking policy'), 'booking_policy', $this->config->booking_policy, 'class="required"',70,4); ?>
                    <?php echo VmHTML::row_control('textarea', JText::_('Child policy'), 'Child_policy', $this->config->Child_policy, 'class="required"',70,4); ?>
                    <?php echo VmHTML::row_control('textarea', JText::_('Modification policy'), 'modification_policy', $this->config->modification_policy, 'class="required"',70,4); ?>
                    <?php echo VmHTML::row_control('textarea', JText::_('Cancellation policy'), 'cancellation_policy', $this->config->cancellation_policy, 'class="required"',70,4); ?>
                </div>
            </div>

            <?php echo $this->addStandardHiddenToForm(); ?>
            <input type="hidden" value="<?php echo $this->config->tsmart_company_id ?>" name="tsmart_company_id">
            <?php echo JHtml::_('form.token'); ?>
        </form>
        <div class="footer"></div>
    </div>
<?php AdminUIHelper::endAdminArea(); ?>