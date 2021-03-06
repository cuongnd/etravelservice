<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Manufacturer
* @author Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 3617 2011-07-05 12:55:12Z enytheme $
*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>

<div class="col50">
	<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_MANUFACTURER_DETAILS'); ?></legend>
	<table class="admintable">

		<?php echo VmHTML::row('input','com_tsmart_MANUFACTURER_NAME','mf_name',$this->manufacturer->mf_name,'class="required"'); ?>
	    	<?php echo VmHTML::row('booleanlist','com_tsmart_PUBLISHED','published',$this->manufacturer->published); ?>
		<?php echo VmHTML::row('input',$this->viewName.' '. tsmText::_('com_tsmart_SLUG'),'slug',$this->manufacturer->slug); ?>
		<?php echo VmHTML::row('select','com_tsmart_MANUFACTURER_CATEGORY_NAME','tsmart_manufacturercategories_id',$this->manufacturerCategories,$this->manufacturer->tsmart_manufacturercategories_id,'','tsmart_manufacturercategories_id', 'mf_category_name',false); ?>
		<?php echo VmHTML::row('input','com_tsmart_MANUFACTURER_URL','mf_url',$this->manufacturer->mf_url); ?>
		<?php echo VmHTML::row('input','com_tsmart_MANUFACTURER_EMAIL','mf_email',$this->manufacturer->mf_email); ?>
		<?php echo VmHTML::row('editor','com_tsmart_MANUFACTURER_DESCRIPTION','mf_desc',$this->manufacturer->mf_desc); ?>


	</table>
	</fieldset>
</div>