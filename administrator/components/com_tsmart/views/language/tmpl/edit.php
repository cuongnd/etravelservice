<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage language
* @author Max Milbers, RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id$
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start',"state");
?>

<form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


<div class="col50">
	<fieldset>
	<legend><?php echo tsmText::_('Current state'); ?></legend>
	<table class="admintable">
		<?php echo VmHTML::row_control('input','state name','title',$this->item->title,'class="required"'); ?>
		<?php echo VmHTML::row_control('select','State', 'virtuemart_state_id', $this->states ,$this->item->virtuemart_state_id,'','virtuemart_state_id', 'state_name',false) ; ?>
		<?php echo VmHTML::row_control('booleanlist','com_tsmart_PUBLISHED','published',$this->item->published); ?>

	</table>
	</fieldset>

</div>
	<input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>" />
	<input type="hidden" name="virtuemart_state_id" value="<?php echo $this->item->virtuemart_state_id; ?>" />
	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>