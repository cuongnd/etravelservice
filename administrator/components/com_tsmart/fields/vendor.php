<?php

/**
 *
 * @package	tsmart
 * @subpackage   Models Fields
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
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
if (!class_exists('ShopFunctions'))
    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');

if(!class_exists('TableManufacturers')) require(VMPATH_ADMIN.DS.'tables'.DS.'vendors.php');
if (!class_exists( 'tsmartModelVendor' ))
   JLoader::import( 'vendor', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_tsmart' . DS . 'models' );
jimport('joomla.form.formfield');

/**
 * Supports a modal product picker.
 *
 *
 */
class JFormFieldVendor extends JFormField {

	var $type = 'vendor';
	
	function getInput() {
		VmConfig::loadConfig();
		VmConfig::loadJLang('com_tsmart');
		$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		$model = tmsModel::getModel('vendor');

		$vendors = $model->getVendors(true, true, false);
		return JHtml::_('select.genericlist', $vendors, $this->name, 'class="inputbox"  size="1"', 'tsmart_vendor_id', 'vendor_name', $this->value, $this->id);
	}
}