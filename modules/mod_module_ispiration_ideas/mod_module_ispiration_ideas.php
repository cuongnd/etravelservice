<?php
/**
 * @copyright	Copyright © 2015 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
// Include assets
$doc->addStyleSheet(JURI::root()."modules/mod_module_ispiration_ideas/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_module_ispiration_ideas/assets/js/script.js");
// $width 			= $params->get("width");

/**
	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__mod_module_ispiration_ideas where del=0 and module_id=".$module->id);
	$objects = $db->loadAssocList();
*/
require JModuleHelper::getLayoutPath('mod_module_ispiration_ideas', $params->get('layout', 'default'));