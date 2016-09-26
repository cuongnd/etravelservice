<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage State
* @author RickG, Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 8802 2015-03-18 17:12:44Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col50">
	<fieldset>
<?php /*	<legend><?php echo vmText::_('com_tsmart_STATE_DETAILS'); ?></legend> */?>
	<legend><?php echo JHtml::_('link','index.php?option=com_tsmart&view=state&tsmart_country_id='.$this->tsmart_country_id,tsmText::sprintf('com_tsmart_STATE_COUNTRY',$this->country_name).' '. tsmText::_('com_tsmart_DETAILS') ); ?></legend>
	<table class="admintable">
		<?php
		echo VmHTML::row('input', 'com_tsmart_STATE_NAME', 'state_name', $this->state->state_name,'class="required" size="50"');
		echo VmHTML::row('booleanlist', 'com_tsmart_PUBLISHED', 'published', $this->state->published);
		?>
		<tr>
		<td width="110" class="key">
				<label for="tsmart_worldzone_id">
					<?php echo tsmText::_('com_tsmart_WORLDZONE'); ?>
				</label>
			</td>
			<td>
				<?php echo JHtml::_('Select.genericlist', $this->worldZones, 'tsmart_worldzone_id', '', 'tsmart_worldzone_id', 'zone_name', $this->state->tsmart_worldzone_id); ?>
			</td>
		</tr>
		<?php
		echo VmHTML::row('input', 'com_tsmart_STATE_3_CODE', 'state_3_code', $this->state->state_3_code,'size="10"');
		echo VmHTML::row('input', 'com_tsmart_STATE_2_CODE', 'state_2_code', $this->state->state_2_code,'size="10"');
		?>
	</table>
	</fieldset>
</div>

	<input type="hidden" name="tsmart_country_id" value="<?php echo $this->tsmart_country_id; ?>" />
	<input type="hidden" name="tsmart_state_id" value="<?php echo $this->state->tsmart_state_id; ?>" />

	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php AdminUIHelper::endAdminArea(); ?>