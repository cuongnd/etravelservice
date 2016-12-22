<?php
/**
*
* Modify user form view
*
* @package	tsmart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 8927 2015-07-21 10:09:26Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Implement Joomla's form validation
JHtml::_('behavior.formvalidation');
JHtml::stylesheet('vmpanels.css', JURI::root().'components/com_tsmart/assets/css/'); // VM_THEMEURL
?>

<?php vmJsApi::vmValidator(); ?>

<h1><?php echo $this->page_title ?></h1>
<?php echo shopFunctionsF::getLoginForm(false); ?>

<?php if($this->userDetails->tsmart_user_id==0) {
	echo '<h2>'.tsmText::_('com_tsmart_YOUR_ACCOUNT_REG').'</h2>';
}?>
<form method="post" id="adminForm" name="userForm" action="<?php echo JRoute::_('index.php?option=com_tsmart&view=user',$this->useXHTML,$this->useSSL) ?>" class="form-validate">
<?php if($this->userDetails->user_is_vendor){ ?>
    <div class="buttonBar-right">
	<button class="button" type="submit" onclick="javascript:return myValidator(userForm, true);" ><?php echo $this->button_lbl ?></button>
	&nbsp;
<button class="button" type="reset" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_tsmart&view=user&task=cancel', FALSE); ?>'" ><?php echo tsmText::_('com_tsmart_CANCEL'); ?></button></div>
    <?php } ?>
<?php // Loading Templates in Tabs
if($this->userDetails->tsmart_user_id!=0) {
    $tabarray = array();

    $tabarray['shopper'] = 'com_tsmart_SHOPPER_FORM_LBL';

	if(!empty($this->manage_link)) {
		echo $this->manage_link;
	}

	if(!empty($this->add_product_link)) {
		echo $this->add_product_link;
	}

	if($this->userDetails->user_is_vendor){

		$tabarray['vendor'] = 'com_tsmart_VENDOR';
	}

    //$tabarray['user'] = 'com_tsmart_USER_FORM_TAB_GENERALINFO';
    if (!empty($this->shipto)) {
	    $tabarray['shipto'] = 'com_tsmart_USER_FORM_ADD_SHIPTO_LBL';
    }
    if (($_ordcnt = count($this->orderlist)) > 0) {
	    $tabarray['orderlist'] = 'com_tsmart_YOUR_ORDERS';
    }

    shopFunctionsF::buildTabs ( $this, $tabarray);

 } else {
    echo $this->loadTemplate ( 'shopper' );
 }

// captcha addition
if(tsmConfig::get ('reg_captcha')){
	JHTML::_('behavior.framework');
	JPluginHelper::importPlugin('captcha');
	$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
	?>
	<div id="dynamic_recaptcha_1"></div>
<?php
}
// end of captcha addition
?>
<input type="hidden" name="option" value="com_tsmart" />
<input type="hidden" name="controller" value="user" />
<?php echo JHtml::_( 'form.token' ); ?>
</form>

