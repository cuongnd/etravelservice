<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Currency
* @author Max Milbers, RickG
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
AdminUIHelper::imitateTabs('start',"Activity");
?>

<form action="index.php" method="post" name="adminForm" class="form-horizontal" id="adminForm">


<div class="col50">
	<fieldset>
	<legend><?php echo tsmText::_('Current activity'); ?></legend>
	<table class="admintable">
		<?php echo VmHTML::row_control('input','Activity name','title',$this->item->title,'class="required"'); ?>
		<?php echo VmHTML::image('Icon', 'icon', $this->item->icon, 'class="required"'); ?>
		<?php echo VmHTML::row_control('textarea','Meta title','meta_title',$this->item->meta_title); ?>
		<?php echo VmHTML::row_control('textarea','Key Word','key_word',$this->item->key_word); ?>
		<?php echo VmHTML::row_control('editor','Description','description',$this->item->description); ?>
		<?php echo VmHTML::row_control('booleanlist','com_tsmart_PUBLISHED','published',$this->item->published); ?>

	</table>
	</fieldset>

</div>
	<input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>" />
	<input type="hidden" name="tsmart_activity_id" value="<?php echo $this->item->tsmart_activity_id; ?>" />
	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>