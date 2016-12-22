<?php
defined('_JEXEC') or die('');


/**
 * Renders the email for the vendor send in the registration process
 * @package	tsmart
 * @subpackage User
 * @author Max Milbers
 * @author Valérie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 2459 2010-07-02 17:30:23Z milbo $
 */
$li = "\n";
?>


<?php echo tsmText::sprintf('com_tsmart_WELCOME_VENDOR', $this->vendor->vendor_store_name) . $li. $li ?>
<?php echo tsmText::_('com_tsmart_VENDOR_REGISTRATION_DATA') . " " . $li; ?>
<?php echo tsmText::_('com_tsmart_USERNAME')   . $this->user->username . $li; ?>
<?php echo tsmText::_('com_tsmart_DISPLAYED_NAME')   . $this->user->name . $li. $li; ?>
<?php echo tsmText::_('com_tsmart_ENTERED_ADDRESS')   . $li ?>


<?php

foreach ($this->userFields['fields'] as $userField) {
    if (!empty($userField['value']) && $userField['type'] != 'delimiter' && $userField['type'] != 'BT') {
	echo $userField['title'] . ' ' . $userField['value'] . $li;
    }
}

echo $li;

echo JURI::root() . 'index.php?option=com_tsmart&view=user' . $li;

echo $li;
//echo JURI::root() . 'index.php?option=com_tsmart&view=user&tsmart_user_id=' . $this->_models['user']->_id . ' ' . $li;
//echo JURI::root() . 'index.php?option=com_tsmart&view=vendor&tsmart_vendor_id=' . $this->vendor->tsmart_vendor_id . ' ' . $li;
?>
