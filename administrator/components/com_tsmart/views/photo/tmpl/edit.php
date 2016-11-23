<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage photo
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
AdminUIHelper::imitateTabs('start','com_tsmart_photo_DETAILS');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">


<div class="col50">
	<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_photo_DETAILS'); ?></legend>
	<table class="admintable">
		<?php
		$lang = JFactory::getLanguage();
		$prefix="com_tsmart_photo_";
		$photo_string = $lang->hasKey($prefix.$this->photo->photo_3_code) ? ' (' . tsmText::_($prefix.$this->photo->photo_3_code) . ')' : ' ';
        ?>
		<?php echo VmHTML::row('input','com_tsmart_photo_REFERENCE_NAME','photo_name',$this->photo->photo_name,'class="required"', '', 50, 50, $photo_string); ?>

		<?php echo VmHTML::row('booleanlist','com_tsmart_PUBLISHED','published',$this->photo->published); ?>
<?php /* TODO not implemented		<tr>
			<td width="110" class="key">
				<label for="title">
					<?php echo vmText::_('com_tsmart_WORLDZONE'); ?>:
				</label>
			</td>
			<td>
				<?php echo JHtml::_('Select.genericlist', $this->worldZones, 'tsmart_worldzone_id', '', 'tsmart_worldzone_id', 'zone_name', $this->photo->tsmart_worldzone_id); ?>
			</td>
		</tr>*/ ?>
		<?php echo VmHTML::row('input','com_tsmart_photo_3_CODE','photo_3_code',$this->photo->photo_3_code,'class="required"'); ?>
		<?php echo VmHTML::row('input','com_tsmart_photo_2_CODE','photo_2_code',$this->photo->photo_2_code,'class="required"'); ?>
	</table>
	</fieldset>
</div>

	<input type="hidden" name="tsmart_photo_id" value="<?php echo $this->photo->tsmart_photo_id; ?>" />

	<?php echo $this->addStandardHiddenToForm(); ?>
</form>

<?php 
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>