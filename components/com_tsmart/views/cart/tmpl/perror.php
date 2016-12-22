<?php
/**
*
* Error Layout for the add to cart popup
*
* @package	tsmart
* @subpackage Cart
* @author Max Milbers
*
* @link http://www.tsmart.net
* @copyright Copyright (c) 2013 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

echo '<a class="continue" href="' . $this->continue_link . '" >' . tsmText::_('com_tsmart_CONTINUE_SHOPPING') . '</a>';
if(!empty($this->errorMsg)){
	echo '<div>'.$this->errorMsg.'</div>';
}

$messageQueue = JFactory::getApplication()->getMessageQueue();
foreach ($messageQueue as $message) {
	echo '<div>'.$message['message'].'</div>';
}

if($this->product_name) {
	echo '<br><h4>'.$this->product_name.'</h4>';
}

?>
<br style="clear:both">
