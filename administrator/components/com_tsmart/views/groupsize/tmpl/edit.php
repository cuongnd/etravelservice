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
AdminUIHelper::imitateTabs('start',"groupsize");
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">


<div class="col50">
	<fieldset>
	<legend><?php echo tsmText::_('Current groupsize'); ?></legend>
	<table class="admintable">
		<?php echo VmHTML::row('input','group_name','group_name',$this->item->group_name,''); ?>
		<?php echo VmHTML::row('input','from','from',$this->item->from,'class="required"'); ?>
		<?php echo VmHTML::row('input','to','to',$this->item->to,'class="required"'); ?>
		<?php echo VmHTML::row('booleanlist','com_tsmart_PUBLISHED','published',$this->item->published); ?>

	</table>
	</fieldset>

</div>
	<input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->item->tsmart_vendor_id; ?>" />
	<input type="hidden" name="tsmart_group_size_id" value="<?php echo $this->item->tsmart_group_size_id; ?>" />
	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>