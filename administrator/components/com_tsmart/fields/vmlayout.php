<?php

/**
 *
 * @package	tsmart
 * @subpackage   Models Fields
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: $
 */
defined('JPATH_BASE') or die;
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig();
if (!class_exists('ShopFunctions'))
    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
if (!class_exists('tsmartModelConfig'))
    require(VMPATH_ADMIN . DS . 'models' . DS . 'config.php');
jimport('joomla.form.formfield');

/**
 * Supports a modal product picker.
 *
 *
 */
class JFormFieldVmLayout extends JFormField
{
	var $type = 'layout';

	/**
	 * Method to get the field input markup. Use as name the view of the desired layout list + "layout".
	 * For example <field name="categorylayout" for all layouts of hte category view.
	 *
     * @author   Max Milbers
	 * @return	string	The field input markup.
	 * @since	2.0.24a
	 */
  
	function getInput() {

		VmConfig::loadJLang('com_tsmart');

		$this->view = (string) $this->element['view'];
		if(empty($this->view)){
			$view = substr($this->fieldname,0,-6);;
		} else {
			$view = $this->view;
		}

		$vmLayoutList = tsmartModelConfig::getLayoutList($view);
		$html = JHtml::_('Select.genericlist',$vmLayoutList, $this->name, 'size=1 width=200', 'value', 'text', array($this->value));

        return $html;

    }

}