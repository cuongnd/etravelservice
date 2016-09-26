<?php
defined('_JEXEC') or die();
/**
 *
 * @package    tsmart
 * @subpackage Plugins  - Elements
 * @author Valérie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (C) 2004-2015 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */
JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');
class JFormFieldVmOrderState extends JFormFieldList {

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	var $type = 'vmOrderState';

	protected function getOptions() {
		VmConfig::loadJLang('com_tsmart_orders', TRUE);

		$options = array();
		$db = JFactory::getDBO();

		$query = 'SELECT `order_status_code` AS value, `order_status_name` AS text
                 FROM `#__tsmart_orderstates`
                 WHERE `tsmart_vendor_id` = 1
                 ORDER BY `ordering` ASC ';

		$db->setQuery($query);
		$values = $db->loadObjectList();
		foreach ($values as $value) {
			$options[] = JHtml::_('select.option', $value->value, tsmText::_($value->text));
		}


		return $options;
	}

}