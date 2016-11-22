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
 * @version $Id$
 */

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists('tsmConfig')) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
/*
 * This class is used by tsmart Payment or Shipment Plugins
 * So It should be an extension of JFormField
 * Those plugins cannot be configured through the Plugin Manager anyway.
 */
JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');

class JFormFieldVmAcceptedCurrency extends JFormFieldList {

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	var $type = 'vmacceptedcurrency';

	protected function getOptions() {

		tsmConfig::loadConfig();
		tsmConfig::loadJLang('com_tsmart', false);

		$cModel = tmsModel::getModel('currency');
		$values = $cModel->getVendorAcceptedCurrrenciesList();

		$options[] = JHtml::_('select.option', 0, tsmText::_('com_tsmart_DEFAULT_VENDOR_CURRENCY'));
		$options[] = JHtml::_('select.option', -1, tsmText::_('com_tsmart_SELECTED_MODULE_CURRENCY'));
		foreach ($values as $v) {
			$options[] = JHtml::_('select.option', $v->tsmart_currency_id, $v->currency_txt);
		}
		return $options;
	}

}