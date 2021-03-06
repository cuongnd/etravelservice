<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Calculation tool
* @author Max Milbers
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
	<div id="header">
		<div id="filterbox">
		<table>
		  <tr>
			 <td align="left">
				<?php echo $this->displayDefaultViewSearch() ?>
			 </td>
		  </tr>
		</table>
		</div>
		<div id="resultscounter" ><?php echo $this->pagination->getResultsCounter();?></div>
	</div>
	<div id="editcell">
		<table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
		<thead>
		<tr>
			<th class="admin-checkbox">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
			<th width="20%"><?php echo $this->sort('calc_name', 'com_tsmart_NAME') ; ?></th>
			<?php if($this->showVendors){ ?>
			<th width="10px">
				<?php echo tsmText::_('com_tsmart_VENDOR');  ?>
			</th><?php }  ?>
			<th width="25%"><?php echo $this->sort('calc_descr' , 'com_tsmart_DESCRIPTION'); ?></th>
			<th><?php echo $this->sort('ordering') ; ?></th>
			<th style="min-width:120px;width:5%;" ><?php echo $this->sort('calc_kind') ; ?></th>
			<th><?php echo tsmText::_('com_tsmart_CALC_VALUE_MATHOP'); ?></th>
			<th><?php echo $this->sort('calc_value' , 'com_tsmart_VALUE'); ?></th>
			<th><?php echo $this->sort('calc_currency' , 'com_tsmart_CURRENCY'); ?></th>
			<th><?php echo tsmText::_('com_tsmart_CATEGORY_S'); ?></th>
			<th><?php echo tsmText::_('com_tsmart_MANUFACTURER'); // Mod. <mediaDESIGN> St.Kraft 2013-02-24  ?></th>
			<th><?php echo tsmText::_('com_tsmart_SHOPPERGROUP_IDS'); ?></th>
			<?php /*		<th><?php echo vmText::_('com_tsmart_CALC_VIS_SHOPPER'); ?></th>
			<th width="10"><?php echo vmText::_('com_tsmart_CALC_VIS_VENDOR'); ?></th> */  ?>
			<th><?php echo $this->sort('publish_up' , 'com_tsmart_START_DATE'); ?></th>
			<th><?php echo $this->sort('publish_down' , 'com_tsmart_END_DATE'); ?></th>
<?php /*	<th width="20"><?php echo vmText::_('com_tsmart_CALC_AMOUNT_COND'); ?></th>
			<th width="10"><?php echo vmText::_('com_tsmart_CALC_AMOUNT_DIMUNIT'); ?></th> */  ?>
			<th><?php echo tsmText::_('com_tsmart_COUNTRY_S'); ?></th>
			<th><?php echo tsmText::_('com_tsmart_STATE_IDS'); ?></th>
			<th><?php echo tsmText::_('com_tsmart_PUBLISHED'); ?></th>
			<?php if($this->showVendors){ ?>
			<th width="20">
				<?php echo tsmText::_( 'com_tsmart_SHARED')  ?>
			</th><?php }  ?>
			<th><?php echo $this->sort('tsmart_calc_id', 'com_tsmart_ID')  ?></th>
		<?php /*	<th width="10">
				<?php echo vmText::_('com_tsmart_SHARED'); ?>
			</th> */ ?>
		</tr>
		</thead>
		<?php
		$k = 0;

		for ($i=0, $n=count( $this->calcs ); $i < $n; $i++) {

			$row = $this->calcs[$i];
			$checked = JHtml::_('grid.id', $i, $row->tsmart_calc_id);
			$published = $this->toggle($row->published, $i, 'toggle.published');

			$editlink = JROUTE::_('index.php?option=com_tsmart&view=calc&task=edit&cid[]=' . $row->tsmart_calc_id);
			?>
			<tr class="<?php echo "row".$k; ?>">

				<td class="admin-checkbox">
					<?php echo $checked; ?>
				</td>
				<td align="left">
					<a href="<?php echo $editlink; ?>"><?php echo $row->calc_name; ?></a>
				</td>
				<?php  if($this->showVendors){ ?>
				<td align="left">
					<?php echo $row->tsmart_vendor_id; ?>
				</td>
				<?php } ?>
				<td>
					<?php echo $row->calc_descr; ?>
				</td>
				<td>
					<?php echo $row->ordering; ?>
				</td>
				<td align="center" >
					<?php echo $row->calc_kind; ?>
				</td>
				<td align="center" >
					<?php echo $row->calc_value_mathop; ?>
				</td>
				<td>
					<?php echo $row->calc_value; ?>
				</td>
				<td>
					<?php echo $row->currencyName; ?>
				</td>
				<td>
					<?php echo $row->calcCategoriesList; ?>
				</td>
				<td>
					<?php echo $row->calcManufacturersList; /* Mod. <mediaDESIGN> St.Kraft 2013-02-24 Herstellerrabatt */ ?>
				</td>
				<td>
					<?php echo $row->calcShoppersList; ?>
				</td>
				<?php /*				<td align="center">
					<a href="#" onclick="return listItemTask('cb<?php echo $i;?>', 'toggle.calc_shopper_published')" title="<?php echo ( $row->calc_shopper_published == '1' ) ? vmText::_('com_tsmart_YES') : vmText::_('com_tsmart_NO');?>">
						<?php echo JHtml::_('image.administrator', ((JVM_VERSION===1) ? '' : 'admin/') . ($row->calc_shopper_published ? 'tick.png' : 'publish_x.png')); ?>
					</a>
				</td>
				<td align="center">
					<a href="#" onclick="return listItemTask('cb<?php echo $i;?>', 'toggle.calc_vendor_published')" title="<?php echo ( $row->calc_vendor_published == '1' ) ? vmText::_('com_tsmart_YES') : vmText::_('com_tsmart_NO');?>">
						<?php echo JHtml::_('image.administrator', ((JVM_VERSION===1) ? '' : 'admin/') . ($row->calc_vendor_published ? 'tick.png' : 'publish_x.png')); ?>
					</a>
				</td> */  ?>
				<td>
					<?php
						echo vmJsApi::date( $row->publish_up, 'LC4',true);
					?>
				</td>
				<td>
					<?php
							echo vmJsApi::date( $row->publish_down, 'LC4',true);
					?>
				</td>
<?php /*				<td>
					<?php echo $row->calc_amount_cond; ?>
				</td>
				<td>
					<?php echo vmText::_($row->calc_amount_dimunit); ?>
				</td> */  ?>
				<td>
					<?php echo tsmText::_($row->calcCountriesList); ?>
				</td>
				<td>
					<?php echo tsmText::_($row->calcStatesList); ?>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>

				<?php
				if($this->showVendors){
				?><td align="center">
					   <?php echo $this->toggle($row->shared, $i, 'toggle.shared'); ?>
			        </td>
				<?php
				}
			?>
				<td align="right">
					<?php echo $row->tsmart_calc_id; ?>
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
				</td>
			</tr>
		</tfoot>
	</table>
</div>
	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php AdminUIHelper::endAdminArea(); ?>
