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
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc=JFactory::getDocument();
AdminUIHelper::startAdminArea($this);

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-relation-default').view_relation_default({});
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_relation_default.js');

$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=relation&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'tour_type_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


?>
    <div class="view-relation-default">
        <?php echo tsmproduct::get_html_tour_information($this, $this->tsmart_product_id); ?>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <div id="editcell">
                <div class="vm-page-nav">

                </div>
                <table id="tour_type_list" class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>
                            <?php echo JText::_('Related style') ?>
                        </th>
                        <th>
                            <?php echo JText::_('Related activity') ?>
                        </th>
                        <th>
                            <?php echo JText::_('Related country') ?>
                        </th>
                        <th>
                            <?php echo JText::_('Promotion') ?>
                        </th>
                        <th>
                            <?php echo JText::_('Customized listing') ?>
                        </th>
                        <th>
                            <?php echo JText::_('Display') ?>
                        </th>
                    </tr>
                    </thead>
                    <tr >
                        <td align="left">
                            <input <?php echo $this->item->related_style?'checked':'' ?>  type="checkbox" value="1" name="related_style"  >
                        </td>
                        <td align="left">
                            <input type="checkbox" <?php echo $this->item->related_activity?'checked':'' ?> value="1" name="related_activity"  >
                        </td>
                        <td align="left">
                            <input type="checkbox" <?php echo $this->item->related_country?'checked':'' ?> value="1" name="related_country"  >
                        </td>
                        <td align="left">
                            <input type="checkbox" <?php echo $this->item->related_promotion?'checked':'' ?> value="1" name="related_promotion"  >
                        </td>
                        <td align="left">
                            <?php echo VmHTML::select_tour('list_tsmart_product_id[]', $this->item->list_tsmart_product_id,' multiple="multiple" '); ?>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::product_display('product_display', $this->item->product_display,''); ?>

                        </td>


                    </tr>


                </table>
            </div>
            <?php echo VmHTML::inputHidden(array(
                'key[tsmart_product_id]'=>$this->tsmart_product_id
            )); ?>
            <input type="hidden" name="tsmart_product_id" value="<?php echo $this->tsmart_product_id ?>">
            <input type="hidden" name="tsmart_related_id" value="<?php echo $this->item->tsmart_related_id ?>">
            <?php echo $this->addStandardHiddenToForm(); ?>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>


<?php AdminUIHelper::endAdminArea(); ?>