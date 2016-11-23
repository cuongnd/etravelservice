<?php
/**
 * @copyright	Copyright © 2015 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
// Include assets
$doc->addStyleSheet(JURI::root()."modules/mod_module_group_private_tours/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_module_group_private_tours/assets/js/script.js");
require_once JPATH_ROOT.'/modules/mod_module_group_private_tours/helpers/helper.php';
$list_tour=mod_module_group_private_tours_helper::get_list_tour();
require JModuleHelper::getLayoutPath('mod_module_group_private_tours', $params->get('layout', 'default'));