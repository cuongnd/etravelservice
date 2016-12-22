<?php
/**
 *
 * Show Notify page
 *
 * @package	tsmart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_reviews.php 5428 2012-02-12 04:41:22Z electrocity $
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );
// Implement Joomla's form validation
JHTML::_('behavior.formvalidation');
?>
<div class="vm-wrap">
  <h1><?php echo tsmText::_('com_tsmart_CART_NOTIFY') ?></h1>
  <p><?php echo tsmText::sprintf('com_tsmart_CART_NOTIFY_DESC', $this->product->product_name); ?></p>
  <form class="form-validate" method="post" action="<?php echo JRoute::_('index.php?option=com_tsmart&view=productdetails&tsmart_product_id='.$this->product->tsmart_product_id.'&tsmart_category_id='.$this->product->tsmart_category_id, FALSE) ; ?>" name="notifyform" id="notifyform">
    <label for="notify_email" class="vm-nodisplay"><?php echo tsmText::_('com_tsmart_EMAIL') ?></label>
    <input class="required validate-email" id="notify_email" type="email" name="notify_email" value="<?php echo $this->user->email; ?>" placeholder="<?php echo tsmText::_('com_tsmart_EMAIL') ?>" title="<?php echo tsmText::_('com_tsmart_ENTER_A_VALID_EMAIL_ADDRESS') ?>" />
    <input type="submit" name="notifycustomer" class="notify-button highlight-button validate" value="<?php echo tsmText::_('com_tsmart_CART_NOTIFY') ?>" title="<?php echo tsmText::_('com_tsmart_CART_NOTIFY') ?>" />
    <input type="hidden" name="tsmart_product_id" value="<?php echo $this->product->tsmart_product_id; ?>" />
    <input type="hidden" name="option" value="com_tsmart" />
    <input type="hidden" name="tsmart_category_id" value="<?php echo vRequest::getInt('tsmart_category_id'); ?>" />
    <input type="hidden" name="tsmart_user_id" value="<?php echo $this->user->id; ?>" />
    <input type="hidden" name="task" value="notifycustomer" />
    <input type="hidden" name="controller" value="productdetails" />
    <?php echo JHtml::_( 'form.token' ); ?>
  </form>
</div>