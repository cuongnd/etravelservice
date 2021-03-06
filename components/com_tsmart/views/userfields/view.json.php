<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage
* @author  Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 3006 2011-04-08 13:16:08Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * Json View class for the tsmart Component
 *
 * @package		tsmart
 * @author  Patrick Kohl
 */
class TsmartViewUserfields extends tsmViewAdmin {

	function display($tpl = null) {
		$db = JFactory::getDBO();
		if ( $field = vRequest::getVar('field') ) {
			if (strpos($field, 'plugin') !==false) {

				JForm::addFieldPath(VMPATH_ADMIN . DS . 'fields');

				$table = '#__extensions';

				$field = substr($field, 6);
				$q = 'SELECT `params`,`element`,`type` FROM `' . $table . '` WHERE `element` = "'.$field.'"';
				$db ->setQuery($q);
				$this->userField = $db ->loadObject();
				//$this->userField->element = substr($this->userField->type, 6);

				if (!class_exists ('vmPlugin')) require(VMPATH_PLUGINLIBS . DS . 'vmplugin.php');

				vmPlugin::loadJLang('plg_vmuserfield_'.$this->userField->element, 'vmuserfield',$this->userField->element);

				$path = VMPATH_ROOT .DS. 'plugins' .DS. 'vmuserfield' . DS . $this->userField->element . DS . $this->userField->element . '.xml';
				// Get the payment XML.
				$formFile	= vRequest::filterPath( $path );
				if (file_exists($formFile)){
					if (!class_exists( 'tsmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
					if (!class_exists ('tsmTable')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmtable.php');

					$this->userField->form = JForm::getInstance($this->userField->element, $formFile, array(),false, '//tsmConfig | //config[not(//tsmConfig)]');
					$this->userField->params = new stdClass();
					$varsToPush = vmPlugin::getVarsToPushFromForm($this->userField->form);
					/*
					$this->userField->params->userfield_params = $this->userField->params;
					VmTable::bindParameterable($this->userField->params,'userfield_params',$varsToPush);*/
					if(empty($this->userField->userfield_params)) $this->userField->userfield_params = '';
					tsmTable::bindParameterableToSubField($this->userField,$varsToPush);
					$this->userField->form->bind($this->userField);
				} else {
					$this->userField->form = false;
					vmdebug('renderUserfieldPlugin could not find xml for '.$this->userField->type.' at '.$path);
				}
				//vmdebug('renderUserfieldPlugin ',$this->userField->form);
				if ($this->userField->form) {
					$form = $this->userField->form;
					ob_start();
					include(VMPATH_ADMIN.DS.'fields'.DS.'formrenderer.php');
					$body = ob_get_contents();
					ob_end_clean();
					echo $body;
				}
			}
		}
		jExit();
	}

}
// pure php no closing tag
