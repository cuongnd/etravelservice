<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Manufacturer Category
* @author Patrick Kohl
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
				<?php echo  tsmText::_('com_tsmart_MANUFACTURER_CATEGORY_NAME'); ?>
			</th>
			<th>
				<?php echo  tsmText::_('com_tsmart_MANUFACTURER_CATEGORY_DESCRIPTION'); ?>
			</th>
			<th>
				<?php echo  tsmText::_('com_tsmart_MANUFACTURER_CATEGORY_LIST'); ?>
			</th>
			<th width="20">
				<?php echo tsmText::_('com_tsmart_PUBLISHED'); ?>
			</th>
			   <th><?php echo $this->sort('tsmart_manufacturercategories_id', 'com_tsmart_ID')  ?></th>
		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->manufacturerCategories ); $i < $n; $i++) {
			$row = $this->manufacturerCategories[$i];

			$checked = JHtml::_('grid.id', $i, $row->tsmart_manufacturercategories_id);
			$published = $this->gridPublished( $row, $i );

			$editlink = JROUTE::_('index.php?option=com_tsmart&view=manufacturercategories&task=edit&tsmart_manufacturercategories_id=' . $row->tsmart_manufacturercategories_id);
			$manufacturersList = JROUTE::_('index.php?option=com_tsmart&view=manufacturer&tsmart_manufacturercategories_id=' . $row->tsmart_manufacturercategories_id);

			?>
			<tr class="row<?php echo $k ; ?>">
				<td class="admin-checkbox">
					<?php echo $checked; ?>
				</td>
				<td align="left">
					<a href="<?php echo $editlink; ?>"><?php echo $row->mf_category_name; ?></a>

				</td>
				<td>
					<?php echo tsmText::_($row->mf_category_desc); ?>
				</td>
				<td>
					<a title="<?php echo tsmText::_('com_tsmart_MANUFACTURER_SHOW'); ?>" href="<?php echo $manufacturersList; ?>"><?php echo tsmText::_('com_tsmart_SHOW'); ?></a>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<td align="right">
		    <?php echo $row->tsmart_manufacturercategories_id; ?>
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