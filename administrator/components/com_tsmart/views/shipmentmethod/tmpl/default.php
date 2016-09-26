<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Shipment
* @author RickG
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

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
		<thead>
		<tr>
			<th class="admin-checkbox">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
			<th>
				<?php echo $this->sort('shipment_name', 'com_tsmart_SHIPMENT_NAME_LBL'); ?>
			</th>
                        <th>
				<?php echo tsmText::_('com_tsmart_SHIPMENT_LIST_DESCRIPTION_LBL'); ?>
			</th>
                        <th width="20">
				<?php echo tsmText::_('com_tsmart_SHIPPING_SHOPPERGROUPS'); ?>
			</th>
                        <th>
				<?php echo $this->sort('shipment_element', 'com_tsmart_SHIPMENTMETHOD'); ?>
			</th>
			<th>
				<?php echo $this->sort('ordering', 'com_tsmart_LIST_ORDER'); ?>
			</th>
			<th width="20"><?php echo $this->sort('published', 'com_tsmart_PUBLISHED'); ?></th>
			<?php if($this->showVendors()){ ?>
				<th width="20">
				<?php echo tsmText::_( 'com_tsmart_SHARED')  ?>
				</th><?php }  ?>
			 <th><?php echo $this->sort('tsmart_shipmentmethod_id', 'com_tsmart_ID')  ?></th>
		</tr>
		</thead>
		<?php
		$k = 0;
		$set_automatic_shipment = VmConfig::get('set_automatic_shipment',false);
		for ($i=0, $n=count( $this->shipments ); $i < $n; $i++) {
			$row = $this->shipments[$i];
			$published = $this->gridPublished($row, $i);
			//$row->published = 1;
			$checked = JHtml::_('grid.id', $i, $row->tsmart_shipmentmethod_id);
			if ($this->showVendors) {
				$shared = $this->toggle($row->shared, $i, 'toggle.shared');
			}
			$editlink = JROUTE::_('index.php?option=com_tsmart&view=shipmentmethod&task=edit&cid[]='.$row->tsmart_shipmentmethod_id);
	?>
			<tr class="row<?php echo $k; ?>">
				<td class="admin-checkbox">
					<?php echo $checked; ?>
				</td>
				<td align="left">
					<?php echo JHtml::_('link', $editlink, tsmText::_($row->shipment_name)); ?>
					<?php if ($set_automatic_shipment == $row->tsmart_shipmentmethod_id) {
						?><i class="icon-featured"></i><?php
					}
					?>
				</td>
                                <td align="left">
					<?php echo $row->shipment_desc; ?>
				</td>
                                <td>
					<?php echo $row->shipmentShoppersList; ?>
				</td>
                                <td align="left">
					<?php echo $row->shipment_element; //JHtml::_('link', $editlink, vmText::_($row->shipment_element)); ?>
				</td>
				<td align="left">
					<?php echo tsmText::_($row->ordering); ?>
				</td>
				<td><?php echo $published; ?></td>
				<?php
				if($this->showVendors) {
				?><td align="center">
				<?php echo $shared; ?>
				</td>
				<?php }?>
				<td align="center">
					<?php echo $row->tsmart_shipmentmethod_id; ?>
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
				</td>
			</tr>
		</tfoot>
	</table>
</div>

	<?php echo $this->addStandardHiddenToForm(); ?>
</form>



<?php AdminUIHelper::endAdminArea(); ?>