<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Edit
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 8508 2014-10-22 18:57:14Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea($this);
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">

<?php // Loading Templates in Tabs
$tabarray = array();
$tabarray['edit'] = 'com_tsmart_ADMIN_PAYMENT_FORM';
$tabarray['config'] = 'com_tsmart_ADMIN_PAYMENT_CONFIGURATION';

AdminUIHelper::buildTabs ( $this, $tabarray,$this->payment->tsmart_paymentmethod_id );
// Loading Templates in Tabs END ?>


    <!-- Hidden Fields -->
<input type="hidden" name="option" value="com_tsmart" />
<input type="hidden" name="tsmart_paymentmethod_id" value="<?php echo $this->payment->tsmart_paymentmethod_id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>

    <input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="xxcontroller" value="paymentmethod" />
<input type="hidden" name="view" value="paymentmethod" />

<?php echo JHtml::_('form.token'); ?>
</form>
    <?php AdminUIHelper::endAdminArea(); ?>
