<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage OrderStatus
 * @author Oscar van Eijk
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit.php 8080 2014-06-29 07:31:28Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'com_tsmart_ORDERSTATUS_DETAILS');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">


    <div class="col50">
	<fieldset>
	    <legend><?php echo tsmText::_('com_tsmart_ORDERSTATUS_DETAILS'); ?></legend>
	    <?php
	    $editcoreStatus = (in_array($this->orderStatus->order_status_code, $this->lists['vmCoreStatusCode']));
	    $orderStatusCodeTip = ($editcoreStatus) ? 'com_tsmart_ORDER_STATUS_CODE_CORE' : 'com_tsmart_ORDER_STATUS_CODE_TIP';
	    if ($editcoreStatus) {
		$readonly = 'readonly';
	    } else {
		$readonly = '';
	    }
	    ?>
	    <table class="admintable">
		<?php
		$lang = JFactory::getLanguage();
		$text = $lang->hasKey($this->orderStatus->order_status_name) ? ' (' . tsmText::_($this->orderStatus->order_status_name) . ')' : ' ';

		echo VmHTML::row('input', 'com_tsmart_ORDER_STATUS_NAME', 'order_status_name', $this->orderStatus->order_status_name, 'class="inputbox"', '', 50, 50, $text);
		?>

		<?php echo VmHTML::row('select','com_tsmart_ORDER_STATUS_STOCK_HANDLE', 'order_stock_handle', $this->stockHandelList ,$this->orderStatus->order_stock_handle,'','value', 'text',false) ; ?>
		<?php echo VmHTML::row('input', 'com_tsmart_ORDER_STATUS_CODE', 'order_status_code', $this->orderStatus->order_status_code, 'class="inputbox '.$readonly.'" '.$readonly.'', '', 3, 1); ?>
		<?php echo VmHTML::row('editor', 'com_tsmart_DESCRIPTION', 'order_status_description', $this->orderStatus->order_status_description, '100%;', '250', array('image', 'pagebreak', 'readmore')); ?>
		<?php echo VmHTML::row('raw', 'com_tsmart_VENDOR', $this->lists['vendors']); ?>
		<?php echo VmHTML::row('raw', 'com_tsmart_ORDERING', $this->ordering); ?>

	    </table>
	</fieldset>
    </div>

    <input type="hidden" name="tsmart_orderstate_id" value="<?php echo $this->orderStatus->tsmart_orderstate_id; ?>" />
    <?php echo $this->addStandardHiddenToForm(); ?>
</form>


<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea();
?>
