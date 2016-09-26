<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Userfields
* @author Oscar van Eijk
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 8770 2015-03-03 14:51:42Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);

?>

<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm" id="adminForm">
	<div id="header">
	<div id="filterbox">
		<table>
			<tr>
				<td width="100%">
					<?php echo tsmText::_('com_tsmart_FILTER'); ?>:
					<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
					<button class="btn btn-small" onclick="this.form.submit();"><?php echo tsmText::_('com_tsmart_GO'); ?></button>
					<button class="btn btn-small" onclick="document.adminForm.search.value='';this.form.submit();"><?php echo tsmText::_('com_tsmart_RESET'); ?></button>
				</td>
			</tr>
		</table>
	</div>
	<div id="resultscounter"><?php echo $this->pagination->getResultsCounter();?></div>

	</div>

	<div id="editcell">
		<table class="adminlist jgrid table table-striped" cellspacing="0" cellpadding="0">
		<thead>
		<tr>
			<th class="admin-checkbox">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>

			<th>
			<?php  echo $this->sort('name','com_tsmart_FIELDMANAGER_NAME')  ?>
			</th>
			<th>
			<?php echo tsmText::_('com_tsmart_FIELDMANAGER_TITLE'); ?>
			</th>
			<th>
			<?php echo $this->sort('type','com_tsmart_FIELDMANAGER_TYPE') ?>
			</th>
			<th width="20">
				<?php echo tsmText::_('com_tsmart_FIELDMANAGER_REQUIRED'); ?>
			</th>
			<th width="20">
				<?php echo tsmText::_('com_tsmart_PUBLISHED'); ?>
			</th>
			<th width="20">
				<?php echo tsmText::_('com_tsmart_FIELDMANAGER_SHOW_ON_CART'); ?>
			</th>
			<th width="20">
				<?php echo tsmText::_('com_tsmart_FIELDMANAGER_SHOW_ON_SHIPPING'); ?>
			</th>
			<th width="20">
				<?php echo tsmText::_('com_tsmart_FIELDMANAGER_SHOW_ON_ACCOUNT'); ?>
			</th>
			<th>
			<?php echo $this->sort('ordering','com_tsmart_FIELDMANAGER_REORDER') ?>
			<?php echo JHtml::_('grid.order',  $this->userfieldsList ); ?>
			</th>
			 <th><?php echo $this->sort('tsmart_userfield_id', 'com_tsmart_ID')  ?></th>
		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i = 0, $n = count($this->userfieldsList); $i < $n; $i++) {
			$row = $this->userfieldsList[$i];

			$coreField = (in_array($row->name, $this->lists['coreFields']));
			$image = 'admin/checked_out.png';
			$image = JHtml::_('image', $image, tsmText::_('com_tsmart_FIELDMANAGER_COREFIELD'),null,true);
			//$checked = '<div style="position: relative;">'.JHtml::_('grid.id', $i, null,$row->tsmart_userfield_id);
			$checked = JHtml::_('grid.id', $i ,$row->tsmart_userfield_id,null,'tsmart_userfield_id');
			if ($coreField) $checked.='<span class="hasTip" style="position: absolute; margin-left:-3px;" title="'. tsmText::_('com_tsmart_FIELDMANAGER_COREFIELD').'">'. $image .'</span>';
			$checked .= '</div>';
			$checked_out = $coreField ? 'style="position: relative;"' : '';
			// There is no reason not to allow moving of the core fields. We only need to disable deletion of them
			// ($coreField) ?
			// 	'<span class="hasTip" title="'. vmText::_('com_tsmart_FIELDMANAGER_COREFIELD').'">'. $image .'</span>' :
				
			$editlink = JROUTE::_('index.php?option=com_tsmart&view=userfields&task=edit&tsmart_userfield_id=' . $row->tsmart_userfield_id);
			$required = $this->toggle($row->required, $i, 'toggle.required','tick.png','publish_x.png',$coreField );
//			$published = JHtml::_('grid.published', $row, $i);
			$published = $this->toggle($row->published, $i, 'toggle.published','tick.png','publish_x.png', $coreField);
			$registration = $this->toggle($row->cart, $i, 'toggle.cart','tick.png','publish_x.png', $coreField);
			$shipment = $this->toggle($row->shipment, $i, 'toggle.shipment','tick.png','publish_x.png', $coreField);
			$account = $this->toggle($row->account, $i, 'toggle.account','tick.png','publish_x.png', $coreField);
			$ordering = ($this->lists['filter_order'] == 'ordering');
			$disabled = ($ordering ?  '' : 'disabled="disabled"');
		?>
			<tr class="row<?php echo $k ; ?>">
				<td class="admin-checkbox" <?php echo $checked_out; ?>>
					<?php echo $checked; ?>
				</td>

				<td align="left">
					<a href="<?php echo $editlink; ?>"><?php echo tsmText::_($row->name); ?></a>
				</td>
				<td align="left">
					<?php echo tsmText::_($row->title); ?>
				</td>
				<td align="left">
					<?php echo tsmText::_($row->type); ?>
				</td>
				<td align="center">
					<?php echo $required; ?>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<td align="center">
					<?php echo $registration; ?>
				</td>
				<td align="center">
					<?php echo $shipment; ?>
				</td>
				<td align="center">
					<?php echo $account; ?>
				</td>
				<td class="order">
					<span><?php echo $this->pagination->vmOrderUpIcon( $i, $ordering, 'orderup', tsmText::_('com_tsmart_MOVE_UP')  ); ?></span>
					<span><?php echo $this->pagination->vmOrderDownIcon( $i, $ordering, $n, true, 'orderdown', tsmText::_('com_tsmart_MOVE_DOWN') ); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="ordering" style="text-align: center" />
			</td>
			<td width="10">
					<?php echo $row->tsmart_userfield_id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

	<?php echo $this->addStandardHiddenToForm(); ?>
</form>

<?php AdminUIHelper::endAdminArea(); ?>
