<?php
/**
*
* Set the descriptions for a product
*
* @package	tsmart
* @subpackage Product
* @author RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_description.php 8508 2014-10-22 18:57:14Z Milbo $
*/
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_PRODUCT_FORM_S_DESC') ?></legend>
	<textarea class="inputbox" name="product_s_desc" id="product_s_desc" cols="65" rows="3" ><?php echo $this->product->product_s_desc; ?></textarea>
</fieldset>
			
<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_PRODUCT_FORM_DESCRIPTION') ?></legend>
	<?php echo $this->editor->display('product_desc',  $this->product->product_desc, '100%;', '550', '75', '20', array('pagebreak', 'readmore') ) ; ?>
</fieldset>

<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_METAINFO') ?></legend>
	<?php echo shopFunctions::renderMetaEdit($this->product); ?>
</fieldset>
