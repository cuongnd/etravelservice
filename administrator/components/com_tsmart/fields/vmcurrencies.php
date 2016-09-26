<?php
defined('JPATH_PLATFORM') or die;

/**
 *
 * @package    tsmart
 * @subpackage Plugins  - Elements
 * @author Valérie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: $
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists('tsmConfig')) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
/*
 * This class is used by tsmart Payment or Shipment Plugins
 * So It should be an extension of JFormField
 * Those plugins cannot be configured througth the Plugin Manager anyway.
 */

JFormHelper::loadFieldClass('list');

class JFormFieldVmCurrencies extends JFormFieldList {

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	var $type = 'vmCurrencies';

	protected function getOptions() {
		$options = array();

		if (!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN . DS . 'models' . DS . 'vendor.php');
		$vendor_id = tsmartModelVendor::getLoggedVendor();
		// set currency_id to logged vendor
		if (empty($this->value)) {
			$currency = tsmartModelVendor::getVendorCurrency($vendor_id);
			$this->value = $currency->tsmart_currency_id;
		}
		// why not logged vendor? shared is missing
		$db = JFactory::getDBO();
		$query = 'SELECT `tsmart_currency_id` AS value, `currency_name` AS text
			FROM `#__tsmart_currencies`
			WHERE `tsmart_vendor_id` = "1"  AND `published` = "1" ORDER BY `currency_name` ASC ';
		// default value should be vendor currency
		$db->setQuery($query);
		$values = $db->loadObjectList();
		foreach ($values as $v) {
			$options[] = JHtml::_('select.option', $v->value, $v->text);
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}