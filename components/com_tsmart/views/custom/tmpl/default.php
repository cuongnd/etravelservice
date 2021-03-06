<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 2978 2011-04-06 14:21:19Z alatak $
*/

AdminUIHelper::startAdminArea($this);

jimport('joomla.filesystem.file');

/* Get the component name */
$option = vRequest::getCmd('option');

/* Load some variables */
$keyword = vRequest::getCmd('keyword', null);
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="header">
	<div>
		<?php
			if (vRequest::getInt('tsmart_product_id', false)) echo JHtml::_('link', JRoute::_('index.php?option='.$option.'&view=custom',FALSE), tsmText::_('com_tsmart_PRODUCT_FILES_LIST_RETURN'));
		echo $this->customsSelect ;
		echo tsmText::_('com_tsmart_SEARCH_LBL') .' '.tsmText::_('com_tsmart_TITLE') ?>&nbsp;
		<input type="text" value="<?php echo $keyword; ?>" name="keyword" size="25" class="inputbox" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="view" value="custom" />

		<input class="button btn btn-small" type="submit" name="search" value="<?php echo tsmText::_('com_tsmart_SEARCH_TITLE')?>" />
	</div>
</div>
<?php
$customs = $this->customs->items;
//$roles = $this->customlistsroles;

?>

	<table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th class="admin-checkbox"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" /></th>
		<th width="10%"><?php echo tsmText::_('com_tsmart_CUSTOM_GROUP'); ?></th>
		<th width="30%"><?php echo tsmText::_('com_tsmart_TITLE'); ?></th>
		<th width="35%"><?php echo tsmText::_('com_tsmart_CUSTOM_FIELD_DESCRIPTION'); ?></th>
		<th><?php echo tsmText::_('com_tsmart_CUSTOM_FIELD_TYPE'); ?></th>
		<th><?php echo tsmText::_('com_tsmart_CUSTOM_IS_CART_ATTRIBUTE'); ?></th>
		<th><?php echo tsmText::_('com_tsmart_CUSTOM_ADMIN_ONLY'); ?></th>
		<th><?php echo tsmText::_('com_tsmart_CUSTOM_IS_HIDDEN'); ?></th>
		<?php if(!empty($this->custom_parent_id)){
			echo '<th style="min-width:80px;width:8%;align:center;" >'.$this->sort('ordering');
			echo JHtml::_('grid.order',  $customs ).'</th>';
		}
		?>
		<th style="max-width:80px;align:center;" ><?php echo tsmText::_('com_tsmart_PUBLISHED'); ?></th>
		  <th min-width="8px"><?php echo $this->sort('tsmart_custom_id', 'com_tsmart_ID')  ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($n = count($customs)) {

		$i = 0;
		$k = 0;
		foreach ($customs as $key => $custom) {

			$checked = JHtml::_('grid.id', $i , $custom->tsmart_custom_id,false,'tsmart_custom_id');
			if (!is_null($custom->tsmart_custom_id))
			{
				$published = $this->gridPublished( $custom, $i );
			}
			else $published = '';
			?>
			<tr class="row<?php echo $k ; ?>">
				<!-- Checkbox -->
				<td class="admin-checkbox"><?php echo $checked; ?></td>
				<?php
				$link = "index.php?view=custom&keyword=".urlencode($keyword)."&custom_parent_id=".$custom->custom_parent_id."&option=".$option;
				?>
				<td><?php

                            $lang = JFactory::getLanguage();
                            $text = $lang->hasKey($custom->group_title) ? tsmText::_($custom->group_title) : $custom->group_title;

                            echo JHtml::_('link', JRoute::_($link,FALSE),$text, array('title' => tsmText::_('com_tsmart_FILTER_BY').' '.htmlentities($text))); ?></td>

				<!-- Product name -->
				<?php
				$link = "index.php?option=com_tsmart&view=custom&task=edit&tsmart_custom_id=".$custom->tsmart_custom_id;
				if ($custom->is_cart_attribute) $cartIcon=  'default';
							 else  $cartIcon= 'default-off';
				?>
				<td><?php echo JHtml::_('link', JRoute::_($link, FALSE), tsmText::_($custom->custom_title), array('title' => tsmText::_('com_tsmart_EDIT').' '.htmlentities($custom->custom_title))); ?></td>
				<td><?php echo tsmText::_($custom->custom_desc); ?></td>
				<td><?php echo tsmText::_($custom->field_type_display); ?></td>
				<td><span class="vmicon vmicon-16-<?php echo $cartIcon ?>"></span></td>
				<td>
					<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','toggle.admin_only')" title="<?php echo ($custom->admin_only ) ? tsmText::_('com_tsmart_YES') : tsmText::_('com_tsmart_NO');?>">
					<span class="vmicon <?php echo ( $custom->admin_only  ? 'vmicon-16-checkin' : 'vmicon-16-bug' );?>"></span></a></td>
				<td><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','toggle.is_hidden')" title="<?php echo ($custom->is_hidden ) ? tsmText::_('com_tsmart_YES') : tsmText::_('com_tsmart_NO');?>">
					<span class="vmicon <?php echo ( $custom->is_hidden  ? 'vmicon-16-checkin' : 'vmicon-16-bug' );?>"></span></a></td>

					<?php
					if(!empty($this->custom_parent_id)){
					?>
						<td align="center" class="order">
							<span class="vmicon vmicon-16-move"></span>
						<!--span><?php echo $this->pagination->vmOrderUpIcon($i, $custom->ordering, 'orderUp', tsmText::_('com_tsmart_MOVE_UP')); ?></span>
						<span><?php echo $this->pagination->vmOrderDownIcon( $i, $custom->ordering, $n, true, 'orderDown', tsmText::_('com_tsmart_MOVE_DOWN')); ?></span-->
						<input class="ordering" type="text" name="order[<?php echo $i?>]" id="order[<?php echo $i?>]" size="5" value="<?php echo $custom->ordering; ?>" style="text-align: center" />
						</td>
					<?php
					}
					?>


				<td style="align:center;" ><?php echo $published; ?></td>
				<td><?php echo $custom->tsmart_custom_id; ?></td>
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
<!-- Hidden Fields -->
<input type="hidden" name="task" value="" />
<?php if (vRequest::getInt('tsmart_product_id', false)) { ?>
	<input type="hidden" name="tsmart_product_id" value="<?php echo vRequest::getInt('tsmart_product_id',0); ?>" />
<?php } ?>
<input type="hidden" name="option" value="com_tsmart" />
<input type="hidden" name="view" value="custom" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php //echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php //echo $this->lists['order_Dir']; ?>" />

<?php echo JHtml::_( 'form.token' ); ?>
</form>
<?php AdminUIHelper::endAdminArea();
/// DRAG AND DROP PRODUCT ORDER HACK
if(!empty($this->custom_parent_id)){ ?>
	<script>
		jQuery(function() {

			jQuery( ".adminlist" ).sortable({
				handle: ".vmicon-16-move",
				items: 'tr:not(:first,:last)',
				opacity: 0.8,
				update: function() {
					var i = 1;
					jQuery(function updatenr(){
						jQuery('input.ordering').each(function(idx) {
							jQuery(this).val(idx);
						});
					});

					jQuery(function updaterows() {
						jQuery(".order").each(function(index){
							var row = jQuery(this).parent('td').parent('tr').prevAll().length;
							jQuery(this).val(row);
							i++;
						});

					});
				}
			});
		});
	</script>

<?php } ?>