<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author
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
AdminUIHelper::startAdminArea($this);
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_virtuemart/assets/less/view_product_default.less');
/* Load some variables */
$search_date = vRequest::getVar('search_date', null); // Changed search by date
$now = getdate();
$nowstring = $now["hours"] . ":" . substr('0' . $now["minutes"], -2) . ' ' . $now["mday"] . "." . $now["mon"] . "." . $now["year"];
$search_order = vRequest::getVar('search_order', '>');
$search_type = vRequest::getVar('search_type', 'product');
// OSP in view.html.php $virtuemart_category_id = vRequest::getInt('virtuemart_category_id', false);
if ($product_parent_id = vRequest::getInt('product_parent_id', false)) $col_product_name = 'COM_VIRTUEMART_PRODUCT_CHILDREN_LIST'; else $col_product_name = 'COM_VIRTUEMART_PRODUCT_NAME';

?>
    <div class="view-product-default">
        <form action="index.php"  method="post" name="adminForm" id="adminForm">
            <div id="header">
                <div id="filterbox">
                    <table class="">
                        <tr>
                            <td align="left">
                                <?php echo vmText::_('COM_VIRTUEMART_FILTER') ?>:
                                <select class="inputbox" id="virtuemart_category_id" name="virtuemart_category_id"
                                        onchange="this.form.submit(); return false;">
                                    <option
                                        value=""><?php echo vmText::sprintf('COM_VIRTUEMART_SELECT', vmText::_('COM_VIRTUEMART_CATEGORY')); ?></option>
                                    <?php echo $this->category_tree; ?>
                                </select>


                                <?php echo vmText::_('COM_VIRTUEMART_PRODUCT_LIST_SEARCH_BY_DATE') ?>&nbsp;
                                <input type="text" value="<?php echo vRequest::getVar('filter_product'); ?>"
                                       name="filter_product" size="25"/>
                                <?php
                                echo $this->lists['search_type'];
                                echo $this->lists['search_order'];
                                echo vmJsApi::jDate(vRequest::getVar('search_date', $nowstring), 'search_date');
                                echo $this->lists['vendors'];
                                ?>
                                <button class="btn btn-small"
                                        onclick="this.form.submit();"><?php echo vmText::_('COM_VIRTUEMART_GO'); ?></button>
                                <button class="btn btn-small"
                                        onclick="document.adminForm.filter_product.value=''; document.adminForm.search_type.options[0].selected = true;"><?php echo vmText::_('COM_VIRTUEMART_RESET'); ?></button>

                            </td>

                        </tr>
                    </table>
                </div>
                <div id="resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>

            </div>

            <div class="product table_product" style="text-align: left;">
                <?php
                // $this->productlist
                $mediaLimit = (int)VmConfig::get('mediaLimit', 20);
                $totalList = count($this->productlist);
                if ($this->pagination->limit <= $mediaLimit or $totalList <= $mediaLimit) {
                    $imgWidth = VmConfig::get('img_width');
                    if (empty($imgWidth)) $imgWidth = 80;
                } else {
                    $imgWidth = 30;
                }

                ?>
                <div class="vm-page-nav">

                </div>
                <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="admin-checkbox"><input type="checkbox" name="toggle" value=""
                                                          onclick="Joomla.checkAll(this)"/></th>

                        <th width="20%"><?php echo $this->sort('product_name', $col_product_name) ?> </th>
                        <th style="min-width:40px;"><?php echo vmText::_('tour code'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('tour type'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('tour style'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('Start end city'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('price'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('hotel'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('add ons'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('Payment'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('Added'); ?></th>
                        <th style="min-width:40px;"><?php echo vmText::_('assigns'); ?></th>
                        <th width="40px"><?php echo $this->sort('product_special', 'COM_VIRTUEMART_PRODUCT_FORM_SPECIAL'); ?> </th>
                        <th width="40px"><?php echo $this->sort('published'); ?></th>
                        <th><?php echo $this->sort('p.virtuemart_product_id', 'COM_VIRTUEMART_ID') ?></th>
                        <th align="right"><?php echo JText::_('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total = $this->pagination->total;

                    if ($totalList) {
                        $i = 0;
                        $k = 0;
                        $keyword = vRequest::getCmd('keyword');
                        foreach ($this->productlist as $key => $product) {
                            $checked = JHtml::_('grid.id', $i, $product->virtuemart_product_id, null, 'virtuemart_product_id');
                            $published = JHtml::_('grid.published', $product, $i);
                            $published = $this->gridPublished($product, $i);

                            $is_featured = $this->toggle($product->product_special, $i, 'toggle.product_special');
                            $link = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id=' . $product->virtuemart_product_id;
                            ?>
                            <tr class="row<?php echo $k; ?>">
                                <!-- Checkbox -->
                                <td class="admin-checkbox"><?php echo $checked; ?></td>

                                <td align="left>">
                                    <!--<span style="float:left; clear:left"> -->
                                    <?php
                                    if (empty($product->product_name)) {
                                        $product->product_name = 'Language Missing id ' . $product->virtuemart_product_id;
                                    }
                                    echo JHtml::_('link', JRoute::_($link), $product->product_name, array('title' => vmText::_('COM_VIRTUEMART_EDIT') . ' ' . htmlentities($product->product_name))); ?>
                                    <!-- </span>  -->
                                </td>
                                <td>
                                    <?php echo $product->product_code ?>
                                </td>
                                <td>
                                    <?php echo $product->tour_type ?></td>
                                <td>
                                    <?php echo $product->tour_style_name ?></td>
                                <td>
                                    <?php echo $product->start_end_cicty ?></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=price&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=hotel&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=addons&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=payment&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=departure&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=departure&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=discount&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=asign&virtuemart_product_id=' . $product->virtuemart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>


                                <!-- published -->
                                <td align="center"><?php echo $published; ?></td>
                                <!-- Vendor name -->
                                <td align="right"><?php echo JText::_('Action') ?></td>
                            </tr>
                            <?php
                            $k = 1 - $k;
                            $i++;
                        }
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="16">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Hidden Fields -->
            <input type="hidden" name="product_parent_id"
                   value="<?php echo vRequest::getInt('product_parent_id', 0); ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>
<?php AdminUIHelper::endAdminArea();

// DONE BY stephanbais
/// DRAG AND DROP PRODUCT ORDER HACK
if ($this->virtuemart_category_id) { ?>
    <script>
        jQuery(function () {

            jQuery(".adminlist").sortable({
                handle: ".vmicon-16-move",
                items: 'tr:not(:first,:last)',
                opacity: 0.8,
                update: function () {
                    var i = 1;
                    jQuery(function updatenr() {
                        jQuery('input.ordering').each(function (idx) {
                            jQuery(this).val(idx);
                        });
                    });

                    jQuery(function updaterows() {
                        jQuery(".order").each(function (index) {
                            var row = jQuery(this).parent('td').parent('tr').prevAll().length;
                            jQuery(this).val(row);
                            i++;
                        });

                    });
                }

            });
        });
    </script>

<?php }


/// END PRODUCT ORDER HACK
?>