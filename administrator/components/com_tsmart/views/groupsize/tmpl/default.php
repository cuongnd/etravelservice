<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$input = $app->input;
$show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
$cid = $input->get('cid', array(), 'array');
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_groupsize_default.js');
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-groupsize-default').view_groupsize_default({
                list_group_size:<?php echo json_encode($this->items) ?>
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

AdminUIHelper::startAdminArea($this);

?>
<div class="view-groupsize-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('virtuemart_group_size_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('group_name', 'group_name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('type', 'type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('from', 'From'); ?>

                    </th>
                    <th>
                        <?php echo $this->sort('to', 'To'); ?>
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
                $add_new = $show_edit_in_line == 1 && count($cid) == 0;
                if ($add_new) {
                    $item = new stdClass();
                    $item->published = 1;
                    array_unshift($this->items, $item);
                }

                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_group_size_id);
                    $published = $this->gridPublished($row, $i);


                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=groupsize&task=edit_in_line&cid[]=' . $row->virtuemart_group_size_id);

                    $edit = $this->gridEdit($row, $i, 'virtuemart_group_size_id', $editlink);
                    $save = $this->grid_save_in_line($row, $i, 'virtuemart_group_size_id');
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_group_size_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'virtuemart_group_size_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->virtuemart_group_size_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                        <?php if( $row->type!='flat_price'){ ?>
                                <?php if ($show_edit) { ?>
                                    <?php echo VmHTML::inputHidden(array(virtuemart_group_size_id => $row->virtuemart_group_size_id)); ?>
                                    <?php echo $checked ?>
                                <?php } else { ?>
                                    <?php echo $checked ?>
                                <?php } ?>
                        <?php }else{ ?>

                        <?php } ?>
                        </td>
                        <td align="left">
                            <?php if( $row->type!='flat_price'){ ?>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('group_name', $row->group_name, 'class="required"'); ?>
                            <?php } else { ?>

                                <a href="<?php echo $editlink; ?>"><?php echo $row->group_name; ?>
                                    (<?php echo $row->from ?>,<?php echo $row->to ?>)</a>
                            <?php } ?>
                            <?php }else{ ?>
                                <?php echo JText::_('flat price') ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if( $row->type!='flat_price'){ ?>
                            <?php if ($show_edit) { ?>

                            <?php } else { ?>
                                <a href="<?php echo $editlink; ?>"><?php echo $row->type; ?></a>
                            <?php } ?>
                            <?php }else{ ?>
                                <?php echo JText::_('flat price') ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php
                                $option=array(

                                )
                                ?>
                                <?php echo VmHTML::input_number('from', $row->from, 'required','',0,100,'',$option); ?>
                            <?php } else { ?>
                                <?php echo $row->type=='flat_price'?'none':$row->from ?>
                            <?php } ?>


                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input_number('to', $row->to, 'required'); ?>
                            <?php } else { ?>
                                <?php echo $row->type=='flat_price'?'none':$row->to ?>
                            <?php } ?>
                        </td>

                        <td width="10%" align="center">
                            <?php if ($show_edit && $row->type!='flat_price') { ?>
                                <?php echo $add_new ? '' : $published; ?>
                                <?php echo $save; ?>
                                <?php echo $cancel; ?>
                                <?php echo VmHTML::inputHidden(array(published => $row->published)); ?>

                            <?php } else { ?>
                                <?php if($row->type!='flat_price'){ ?>
                                    <?php echo $published; ?>
                                    <?php echo $edit; ?>
                                    <?php echo $delete; ?>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="10">
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