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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_payment_default.less');
$doc->addScript(JUri::root().'administrator/components/com_tsmart/assets/js/view_payment_default.js');
$input = JFactory::getApplication()->input;
$task=$input->get('task','');
AdminUIHelper::startAdminArea($this);

?>
    <div class="view-payment-default">
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <div id="editcell">
                <div class="vm-page-nav">
                    <?php echo AdminUIHelper::render_pagination($this->pagination) ?>
                </div>
                <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="admin-checkbox">
                            <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                           onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_transfer_addon_id', 'Id'); ?>
                            </label>

                        </th>
                        <th>
                            <?php echo $this->sort('title', 'Payment rule'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('created_on', 'Creation'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('Currency', 'currency'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('cancel_fee', 'c.c Fee'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('amount', 'com.mode'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('dep_term', 'deposit term'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('Balance 1') ?>
                        </th>
                        <th>
                            <?php echo JText::_('Balance 2') ?>
                        </th>
                        <th>
                            <?php echo tsmText::_('Application'); ?>
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

                        $checked = JHtml::_('grid.id', $i, $row->tsmart_payment_id);
                        $published = $this->gridPublished($row, $i);

                        $delete = $this->grid_delete_in_line($row, $i, 'tsmart_payment_id');
                        $editlink = JROUTE::_('index.php?option=com_tsmart&view=payment&task=edit&cid[]=' . $row->tsmart_payment_id);
                        $edit = $this->gridEdit($row, $i, 'tsmart_payment_id', $editlink);

                        ?>
                        <tr class="row<?php echo $k; ?>">
                            <td class="admin-checkbox">
                                <?php echo $checked; ?>
                            </td>
                            <td align="left">
                                <a href="<?php echo $editlink; ?>"><?php echo $row->payment_name; ?></a>
                            </td>
                            <td align="left">
                                <?php echo JHtml::_('date', $row->created_on,tsmConfig::$date_format); ?>
                            </td>
                            <td align="left">
                                <?php echo $row->currency_symbol; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->cancel_fee; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->mode ; ?>
                            </td>
                            <td align="left">
                                <?php
                                $deposit_amount_type=$row->deposit_amount_type=="number"?$row->currency_symbol:'%';
                                ?>
                                <?php echo JText::sprintf('%s%s %s days',$row->amount,$deposit_amount_type,$row->deposit_of_day); ?>
                            </td>
                            <td align="left">

                                <?php echo JText::sprintf('%s %s days',$row->percent_balance_of_day_1.'%',$row->balance_of_day_1); ?>
                            </td>
                            <td align="left">
                                <?php echo JText::sprintf('%s %s days',$row->percent_balance_of_day_2.'%',$row->balance_of_day_2); ?>
                            </td>
                            <td align="left">
                                <?php echo $row->list_tour; ?>
                            </td>
                            <td align="center">
                                <?php echo $published; ?>
                                <?php echo $edit; ?>
                                <?php echo $delete; ?>
                            </td>
                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>

                </table>
            </div>

            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>" />
            <input type="hidden" value="" name="task">
            <input type="hidden" value="com_tsmart" name="option">
            <input type="hidden" value="payment" name="controller">
            <input type="hidden" value="payment" name="view">
            <?php echo JHtml::_('form.token'); ?>
        </form>
        <?php
        if ($task == 'edit' || $task == 'add_new_item') {
            echo $this->loadTemplate('edit');
        }
        ?>
    </div>
<?php AdminUIHelper::endAdminArea(); ?>
<?php
    $js_content = '';
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-payment-default').view_payment_default({
                task: "<?php echo $task ?>"
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

