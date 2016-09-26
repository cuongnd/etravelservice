<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Config
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_product_order.php 7388 2013-11-18 13:32:17Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>
<table>
	<tr>
		<td valign="top">
			<fieldset>
				<legend><?php echo tsmText::_('com_tsmart_BROWSE_ORDERBY_DEFAULT_FIELD_TITLE'); ?></legend>
				<table class="admintable">
					<tr>
						<td class="key">
								<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_BROWSE_ORDERBY_DEFAULT_FIELD_LBL_TIP'); ?>">
									<?php echo tsmText::_('com_tsmart_BROWSE_ORDERBY_DEFAULT_FIELD_LBL'); ?>
								</span>
						</td>
						<td>
							<?php echo JHtml::_('Select.genericlist', $this->orderByFieldsProduct->select, 'browse_orderby_field', 'size=1', 'value', 'text', tsmConfig::get('browse_orderby_field', 'product_name'), 'product_name');
							$orderDirs[] = JHtml::_('select.option', 'ASC' , tsmText::_('Ascending')) ;
							$orderDirs[] = JHtml::_('select.option', 'DESC' , tsmText::_('Descending')) ;

							echo JHtml::_('select.genericlist', $orderDirs, 'prd_brws_orderby_dir', 'size=10', 'value', 'text', tsmConfig::get('prd_brws_orderby_dir', 'ASC') ); ?>
							<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL_TIP'); ?>">
									<?php echo tsmText::_('com_tsmart_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL'); ?>
								</span>
							<?php //Fallback, if someone used an old ordering: "ordering"
							$ordering = tsmConfig::get('browse_cat_orderby_field', 'c.ordering,category_name');
							if(!in_array($ordering,tsmartModelCategory::$_validOrderingFields)){
								$ordering = 'c.ordering,category_name';
							}
							echo JHtml::_('Select.genericlist', $this->orderByFieldsCat, 'browse_cat_orderby_field', 'size=1', 'value', 'text', $ordering, 'category_name');
							echo JHtml::_('select.genericlist', $orderDirs, 'cat_brws_orderby_dir', 'size=10', 'value', 'text', tsmConfig::get('cat_brws_orderby_dir', 'ASC') ); ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_BROWSE_ORDERBY_FIELDS_LBL_TIP'); ?>">
									<?php echo tsmText::_('com_tsmart_BROWSE_ORDERBY_FIELDS_LBL'); ?>
								</span>
						</td>
						<td>
							<fieldset class="checkbox">
								<?php echo $this->orderByFieldsProduct->checkbox; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key">
								<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_BROWSE_SEARCH_FIELDS_LBL_TIP'); ?>">
									<?php echo tsmText::_('com_tsmart_BROWSE_SEARCH_FIELDS_LBL'); ?>
								</span>
						</td>
						<td>
							<fieldset class="checkbox">
								<?php echo $this->searchFields->checkbox; ?>
							</fieldset>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>