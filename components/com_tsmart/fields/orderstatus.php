<?php
defined ('_JEXEC') or die();
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
 * @version $Id:$
 */

class JFormFieldOrderstatus extends JFormField {
	var $type = 'orderstatus';
	function getInput () {

		defined('DS') or define('DS', DIRECTORY_SEPARATOR);
		if (!class_exists('tsmConfig')) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');

		if (!class_exists ('tmsModel')) {
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');
		}
		tsmConfig::loadConfig ();
		tsmConfig::loadJLang('com_tsmart');
		$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		$model = tmsModel::getModel ('Orderstatus');
		$orderStatus = $model->getOrderStatusList (true);
		foreach ($orderStatus as $orderState) {
			$orderState->order_status_name = tsmText::_ ($orderState->order_status_name);
		}
		return JHtml::_ ('select.genericlist', $orderStatus, $this->name, 'class="inputbox" multiple="true" size="1"', 'order_status_code', 'order_status_name', $this->value, $this->id);
	}

}