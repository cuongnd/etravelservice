<?php
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists('tsmConfig')) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');

/**
 * Creates dropdown for selecting a string customfield
 */
class JFormFieldScustom extends JFormField {

	var $type = 'scustom';

	function getInput() {
		tsmConfig::loadConfig();
		return JHtml::_('select.genericlist',  $this->_getStringCustoms(), $this->name, 'class="inputbox"   ', 'value', 'text', $this->value, $this->id);
	}

	private function _getStringCustoms() {
		if (!class_exists('tmsModel'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');
		$cModel = tmsModel::getModel('custom');
		$cModel->_noLimit = true;
		$q = 'SELECT `tsmart_custom_id` AS value, custom_title AS text FROM `#__tsmart_customs` WHERE custom_parent_id="0" AND field_type = "S" ';
		$q .= ' AND `published`=1';
		$db = JFactory::getDBO();
		$db->setQuery ($q);
		$l = $db->loadObjectList ();
		$eOpt = JHtml::_('select.option', '0', tsmText::_('com_tsmart_NONE'));
		array_unshift($l,$eOpt);

		return $l;

	}

}