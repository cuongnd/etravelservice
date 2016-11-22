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
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_supplier_default.js');
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_supplier_default.less');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn  = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=supplier&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'supplierList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$user=JFactory::getUser();
$userId    = $user->get('id');

AdminUIHelper::startAdminArea($this);

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-supplier-default').view_supplier_default({});
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);





?>

<div class="view-supplier-default">

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" id="supplierList" cellspacing="0" cellpadding="0">
                <thead>

                <tr>

                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('tsmart_supplier_id','Id') ; ?></label>
                    </th>
                    <th>
                        <?php echo $this->sort('ordering', 'Supplier name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'location'); ?>
                    </th>

                    <th>
                        <?php echo $this->sort('address', 'Address'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('service_filed', 'Service filed'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('contact person', 'Contact person'); ?>
                    </th>
                    <th>
                        tel/fax
                    </th>
                    <th>
                        <?php echo $this->sort('email_address', 'Email address'); ?>
                    </th>

                    <th width="10">
                        <?php echo tsmText::_('Action'); ?>
                    </th>
                    <?php /*	<th width="10">
				<?php echo vmText::_('com_tsmart_SHARED'); ?>
			</th> */ ?>
                </tr>
                </thead>
                <?php
                $k = 0;
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_supplier_id);
                    $published = $this->gridPublished($row, $i);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_supplier_id');
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=supplier&task=show_parent_popup&cid[]=' . $row->tsmart_supplier_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_supplier_id', $editlink);
                    ?>
                    <tr class="row<?php echo $k; ?>">


                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->supplier_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo $row->location; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->address; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->service_field; ?>
                        </td>

                        <td align="left">
                            <?php echo $row->contact_person; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->telephone; ?><br/><?php echo $row->fax_number; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->email_address; ?>
                        </td>
                      <td align="center" width="70">
                            <?php echo $published; ?>
                            <?php echo $edit; ?>
                            <?php echo $delete; ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="13">
                        <?php echo $this->pagination->getListFooter(); ?>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <?php echo $this->addStandardHiddenToForm(); ?>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>

<?php AdminUIHelper::endAdminArea(); ?>