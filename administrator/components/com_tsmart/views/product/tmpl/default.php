<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage
 * @author
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
AdminUIHelper::startAdminArea($this);
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_product_default.less');
/* Load some variables */
$search_date = vRequest::getVar('search_date', null); // Changed search by date
$now = getdate();
$nowstring = $now["hours"] . ":" . substr('0' . $now["minutes"], -2) . ' ' . $now["mday"] . "." . $now["mon"] . "." . $now["year"];
$search_order = vRequest::getVar('search_order', '>');
$search_type = vRequest::getVar('search_type', 'product');
// OSP in view.html.php $tsmart_category_id = vRequest::getInt('tsmart_category_id', false);
if ($product_parent_id = vRequest::getInt('product_parent_id', false)) $col_product_name = 'com_tsmart_PRODUCT_CHILDREN_LIST'; else $col_product_name = 'com_tsmart_PRODUCT_NAME';

?>
    <div class="view-product-default">
        <form action="index.php"  method="post" name="adminForm" id="adminForm">

            <div class="product table_product" style="text-align: left;">
                <?php
                // $this->productlist
                $mediaLimit = (int)tsmConfig::get('mediaLimit', 20);
                $totalList = count($this->productlist);
                if ($this->pagination->limit <= $mediaLimit or $totalList <= $mediaLimit) {
                    $imgWidth = tsmConfig::get('img_width');
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
                        <th style="min-width:40px;"><?php echo tsmText::_('tour code'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('tour type'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('tour style'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('Start end city'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('price'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('hotel'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('add ons'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('Payment'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('Added'); ?></th>
                        <th style="min-width:40px;"><?php echo tsmText::_('assigns'); ?></th>
                        <th width="40px"><?php echo $this->sort('product_special', 'com_tsmart_PRODUCT_FORM_SPECIAL'); ?> </th>
                        <th width="40px"><?php echo $this->sort('published'); ?></th>
                        <th><?php echo $this->sort('p.tsmart_product_id', 'com_tsmart_ID') ?></th>
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
                            $checked = JHtml::_('grid.id', $i, $product->tsmart_product_id, null, 'tsmart_product_id');
                            $published = JHtml::_('grid.published', $product, $i);
                            $published = $this->gridPublished($product, $i);

                            $is_featured = $this->toggle($product->product_special, $i, 'toggle.product_special');
                            $link = 'index.php?option=com_tsmart&view=product&task=edit&tsmart_product_id=' . $product->tsmart_product_id;
                            ?>
                            <tr class="row<?php echo $k; ?>">
                                <!-- Checkbox -->
                                <td class="admin-checkbox"><?php echo $checked; ?></td>

                                <td align="left>">
                                    <!--<span style="float:left; clear:left"> -->
                                    <?php
                                    if (empty($product->product_name)) {
                                        $product->product_name = 'Language Missing id ' . $product->tsmart_product_id;
                                    }
                                    echo JHtml::_('link', JRoute::_($link), $product->product_name, array('title' => tsmText::_('com_tsmart_EDIT') . ' ' . htmlentities($product->product_name))); ?>
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
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=price&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=hotel&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=addons&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=payment&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=departure&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=departure&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=discount&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
                                            class="icon-edit"></span></a><span class="icon-eye"></span></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_tsmart&view=asign&tsmart_product_id=' . $product->tsmart_product_id) ?>"><span
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
if ($this->tsmart_category_id) { ?>
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