<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Paymentmethod
* @author Max Milbers
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
			<th >
				<?php echo $this->sort('payment_name', 'com_tsmart_PAYMENT_LIST_NAME'); ?>
			</th>
			 <th>
				<?php echo tsmText::_('com_tsmart_PAYMENT_LIST_DESCRIPTION_LBL'); ?>
			</th>
			<?php if($this->showVendors()){ ?>
			<th >
				<?php echo $this->sort('virtuemart_vendor_id', 'com_tsmart_VENDOR');  ?>
			</th><?php }?>

			<th  >
				<?php echo tsmText::_('com_tsmart_PAYMENT_SHOPPERGROUPS'); ?>
			</th>
			<th >
				<?php echo $this->sort('payment_element', 'com_tsmart_PAYMENT_ELEMENT'); ?>
			</th>
			<th  >
				<?php echo $this->sort('ordering', 'com_tsmart_LIST_ORDER'); ?>
			</th>
			<th >
				<?php echo $this->sort('published', 'com_tsmart_PUBLISHED'); ?>
			</th>
			<?php if($this->showVendors){ ?>
			<th width="10">
				<?php echo tsmText::_('com_tsmart_SHARED'); ?>
			</th>
			<?php } ?>
			 <th><?php echo $this->sort('virtuemart_paymentmethod_id', 'com_tsmart_ID')  ?></th>
		</tr>
		</thead>
		<?php
		$k = 0;

		for ($i=0, $n=count( $this->payments ); $i < $n; $i++) {

			$row = $this->payments[$i];
			$checked = JHtml::_('grid.id', $i, $row->virtuemart_paymentmethod_id);
			$published = $this->gridPublished( $row, $i );
			if($this->showVendors){
				$shared = $this->toggle($row->shared, $i, 'toggle.shared');
			}
			$editlink = JROUTE::_('index.php?option=com_tsmart&view=paymentmethod&task=&task=show_parent_popup&cid[]=' . $row->virtuemart_paymentmethod_id);
			?>
			<tr class="<?php echo "row".$k; ?>">

				<td class="admin-checkbox">
					<?php echo $checked; ?>
				</td>
				<td align="left">
					<a href="<?php echo $editlink; ?>"><?php echo $row->payment_name; ?></a>
				</td>
				 <td align="left">
					<?php echo $row->payment_desc; ?>
				</td>
				<?php if($this->showVendors()){?>
				<td align="left">
					<?php echo tsmText::_($row->virtuemart_vendor_id); ?>
				</td>
				<?php } ?>

				<td>
					<?php echo $row->paymShoppersList; ?>
				</td>
				<td>
					<?php echo $row->payment_element; ?>
				</td>
				<td>
					<?php echo $row->ordering; ?>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<?php if($this->showVendors){ ?>
				<td align="center">
					<?php echo $shared; ?>
				</td>
				<?php } ?>
				<td align="center">
					<?php echo $row->virtuemart_paymentmethod_id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tfoot>
			<tr>
				<td colspan="21">
					<?php echo $this->pagination->getListFooter(); ?>
					<?php echo $this->pagination->getLimitBox(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php AdminUIHelper::endAdminArea(); ?>