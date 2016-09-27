<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
*
* @version $Id: admin.tsmart.php 7256 2013-09-29 18:42:44Z Milbo $
* @package tsmart
* @subpackage core
* @copyright Copyright (C) tsmart Team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
*
* http://tsmart.net
*/
$doc=JFactory::getDocument();
JHtml::_('jquery.framework');
$doc->addScript(JUri::root().'/media/system/js/jquery.utility.js');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists('tsmConfig')) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
tsmConfig::loadConfig();

if (!class_exists( 'TsmController' )) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');
if (!class_exists('tmsModel')) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

vmRam('Start');
vmSetStartTime('Start');

$_controller = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

tsmConfig::loadJLang('com_tsmart');


// Require specific controller if requested
if($_controller) {
	if (file_exists(VMPATH_ADMIN.DS.'controllers'.DS.$_controller.'.php')) {
		// Only if the file exists, since it might be a Joomla view we're requesting...
		require (VMPATH_ADMIN.DS.'controllers'.DS.$_controller.'.php');
	} else {
		// try plugins
		JPluginHelper::importPlugin('vmextended');
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger('onVmAdminController', array($_controller));
		if (empty($results)) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Fatal Error in maincontroller admin.tsmart.php: Couldnt find file '.$_controller);
			$app->redirect('index.php?option=com_tsmart');
		}
	}
}


vmJsApi::jQuery(0);

// Create the controller
$_class = 'TsmartController'.ucfirst($_controller);
$controller = new $_class();

// Perform the Request task
$controller->execute(vRequest::getCmd('task', $_controller));
vmTime($_class.' Finished task '.$_controller,'Start');
vmRam('End');
vmRamPeak('Peak');
$controller->redirect();

// pure php no closing tag