<?php
/**
 *
 * Layout for the shopper form to change the current shopper
 *
 * @package	tsmart
 * @subpackage Cart
 * @author Maik K�nnemann
 *
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2013 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2458 2013-07-16 18:23:28Z kkmediaproduction $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>


<h3><?php echo tsmText::_ ('com_tsmart_CART_CHANGE_SHOPPER'); ?></h3>

<form action="<?php echo JRoute::_ ('index.php'); ?>" method="post" class="inline">
	<table cellspacing="0" cellpadding="0" border="0" style="border:0px !important;">
		<tr style="border:0px;">
			<td  style="border:0px;">
				<input type="text" name="usersearch" size="20" maxlength="50">
				<input type="submit" name="searchShopper" title="<?php echo tsmText::_('com_tsmart_SEARCH'); ?>" value="<?php echo tsmText::_('com_tsmart_SEARCH'); ?>" class="button" style="margin-left: 10px;"/>
			</td>
			<td style="border:0px; width: 5%;"></td>
			<td style="border:0px;">
				<?php 
				if (!class_exists ('tsmartModelUser')) {
					require(VMPATH_ADMIN . DS . 'models' . DS . 'user.php');
				}

				$currentUser = $this->cart->user->tsmart_user_id;
				echo JHtml::_('Select.genericlist', $this->userList, 'userID', 'class="vm-chzn-select" style="width: 200px"', 'id', 'displayedName', $currentUser,'userIDcart');
				?>
			</td>
			<td style="border:0px;">
				<input type="submit" name="changeShopper" title="<?php echo tsmText::_('com_tsmart_SAVE'); ?>" value="<?php echo tsmText::_('com_tsmart_SAVE'); ?>" class="button" style="margin-left: 10px;"/>
				<input type="hidden" name="view" value="cart"/>
				<input type="hidden" name="task" value="changeShopper"/>
			</td>
		</tr>
		<tr style="border:0px;">
			<td colspan="2" style="border:0px;"></td>
			<td colspan="2" style="border:0px;">
				<?php if($this->adminID && $currentUser != $this->adminID) { ?>
					<b><?php echo tsmText::_('com_tsmart_CART_ACTIVE_ADMIN') .' '.JFactory::getUser($this->adminID)->name; ?></b>
				<?php } ?>
				<?php echo JHtml::_( 'form.token' ); ?>
			</td>
		</tr>
	</table>
</form>
<br />
