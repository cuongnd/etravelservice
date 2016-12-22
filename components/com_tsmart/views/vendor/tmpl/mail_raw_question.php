<?php
defined('_JEXEC') or die('');


echo tsmText::sprintf('com_tsmart_WELCOME_VENDOR', $this->vendor->vendor_store_name) . "\n" . "\n";
echo tsmText::_('com_tsmart_QUESTION_ABOUT') . ' '. $this->product->product_name."\n" . "\n";
echo tsmText::sprintf('com_tsmart_QUESTION_MAIL_FROM', $this->user->name, $this->user->email) . "\n";
 
echo $this->comment. "\n";
