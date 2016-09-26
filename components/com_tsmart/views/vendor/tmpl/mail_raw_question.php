<?php
defined('_JEXEC') or die('');


echo tsmText::sprintf('COM_VIRTUEMART_WELCOME_VENDOR', $this->vendor->vendor_store_name) . "\n" . "\n";
echo tsmText::_('COM_VIRTUEMART_QUESTION_ABOUT') . ' '. $this->product->product_name."\n" . "\n";
echo tsmText::sprintf('COM_VIRTUEMART_QUESTION_MAIL_FROM', $this->user->name, $this->user->email) . "\n";
 
echo $this->comment. "\n";
