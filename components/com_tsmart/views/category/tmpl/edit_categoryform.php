<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage Category
 * @author RickG, jseros
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


$mainframe = JFactory::getApplication();
?>
<table class="adminform">
	<tr>
		<td valign="top" colspan="2">
			<fieldset>
				<legend><?php echo tsmText::_('com_tsmart_FORM_GENERAL'); ?></legend>
				<table width="100%" border="0">
					<!-- Commented out for future use
				<tr>
					<td class="key">
						<label for="shared">
							<?php echo tsmText::_('com_tsmart_CATEGORY_FORM_SHARED'); ?>:
						</label>
					</td>
					<td>
						<?php
							$categoryShared = isset($this->relationInfo->category_shared) ? $this->relationInfo->category_shared : 1;
							echo JHtml::_('select.booleanlist', 'shared', $categoryShared, $categoryShared);
						?>
					</td>
				</tr>
				-->
					<?php echo VmHTML::row('input','com_tsmart_CATEGORY_NAME','category_name',$this->category->category_name,'class="required"'); ?>
					<?php echo VmHTML::row('booleanlist','com_tsmart_PUBLISHED','published',$this->category->published); ?>
					<?php echo VmHTML::row('input','com_tsmart_SLUG','slug',$this->category->slug); ?>
					<?php echo VmHTML::row('editor','com_tsmart_DESCRIPTION','category_description',$this->category->category_description); ?>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td valign="top" style="width: 50%;">
			<fieldset>
				<legend><?php echo tsmText::_('com_tsmart_DETAILS'); ?></legend>
				<table>
					<?php echo VmHTML::row('raw','com_tsmart_CATEGORY_ORDERING', ShopFunctions::getEnumeratedCategories(true, true, $this->parent->tsmart_category_id, 'ordering', '', 'ordering', 'category_name', $this->category->ordering) ); ?>
					<?php $categorylist = '
						<select name="category_parent_id" id="category_parent_id" class="inputbox">
							<option value="">'.tsmText::_('com_tsmart_CATEGORY_FORM_TOP_LEVEL').'</option>
							'.$this->categorylist.'
						</select>';
					echo VmHTML::row('raw','com_tsmart_CATEGORY_FORM_PARENT', $categorylist ); ?>
					<?php echo VmHTML::row('input','com_tsmart_CATEGORY_FORM_PRODUCTS_PER_ROW','products_per_row',$this->category->products_per_row,'','',4); ?>
					<?php echo VmHTML::row('input','com_tsmart_CATEGORY_FORM_LIMIT_LIST_STEP','limit_list_step',$this->category->limit_list_step,'','',4); ?>
					<?php echo VmHTML::row('input','com_tsmart_CATEGORY_FORM_INITIAL_DISPLAY_RECORDS','limit_list_initial',$this->category->limit_list_initial,'','',4); ?>
					<?php echo VmHTML::row('select','com_tsmart_CATEGORY_FORM_TEMPLATE', 'category_template', $this->jTemplateList ,$this->category->category_template,'','directory', 'name',false) ; ?>
					<?php echo VmHTML::row('select','com_tsmart_CATEGORY_FORM_BROWSE_LAYOUT', 'category_layout', $this->categoryLayouts ,$this->category->category_layout,'','value', 'text',false) ; ?>
					<?php echo VmHTML::row('select','com_tsmart_CATEGORY_FORM_FLYPAGE', 'category_product_layout', $this->productLayouts ,$this->category->category_product_layout,'','value', 'text',false) ; ?>
				</table>
			</fieldset>
		</td>
		<td valign="top" style="width: 50%;">
			<fieldset>
				<legend><?php echo tsmText::_('com_tsmart_METAINFO'); ?></legend>
				<?php echo shopFunctions::renderMetaEdit($this->category); ?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<?php if($this->showVendors() ){
			echo VmHTML::row('raw','com_tsmart_VENDOR', $this->vendorList );
		} ?>
	</tr>
</table>