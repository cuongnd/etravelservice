<?php
if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @version $Id$
 * @package tsmart
 * @subpackage core
 * @author Max Milbers
 * @copyright Copyright (C) 2009-14 by the authors of the tsmart Team listed at /administrator/com_tsmart/copyright.php - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://tsmart.net
 */
require_once JPATH_ROOT.DS.'components/com_tsmart/helpers/jquery.php';
TSMHtmlJquery::framework();
/* Require the config */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
ob_start();
if (!class_exists('tsmConfig')) require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_tsmart' . DS . 'helpers' . DS . 'config.php');
tsmConfig::loadConfig();
vmRam('Start');
vmSetStartTime('Start');
tsmConfig::loadJLang('com_tsmart', true);
require_once JPATH_ROOT . DS . 'administrator/components/com_tsmart/helpers/utility.php';
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/etravelservice.less');
if (tsmConfig::get('shop_is_offline', 0)) {
    //$cache->setCaching (1);
    $_controller = 'tsmart';
    require(VMPATH_SITE . DS . 'controllers' . DS . 'tsmart.php');
    vRequest::setVar('view', 'tsmart');
    $task = '';
    $basePath = VMPATH_SITE;
} else {
    // Front-end helpers
    if (!class_exists('VmImage')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php'); //dont remove that file it is actually in every view except the state view
    $_controller = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));
    $trigger = 'onVmSiteController';
// 	$task = vRequest::getCmd('task',vRequest::getCmd('layout',$_controller) );		$this makes trouble!
    $task = vRequest::getCmd('task', '');
    $session = JFactory::getSession();
    $manage = vRequest::getCmd('manage', $session->get('manage', false, 'vm'));
    if (!$manage) $session->set('manage', 0, 'vm');
    $feViews = array('askquestion', 'cart', 'invoice', 'pdf', 'pluginresponse', 'productdetails', 'recommend', 'vendor', 'vmplg');
    $app = JFactory::getApplication();
    if ($manage and $task != 'feed' and !in_array($_controller, $feViews)) {
        if (shopFunctionsF::isFEmanager()) {
            $session->set('manage', 1, 'vm');
            vRequest::setVar('manage', '1');
            vRequest::setVar('tmpl', 'component');
            tsmConfig::loadJLang('com_tsmart');
            $jlang = JFactory::getLanguage();
            $tag = $jlang->getTag();
            $jlang->load('', JPATH_ADMINISTRATOR, $tag, true);
            tsmConfig::loadJLang('com_tsmart');
            $basePath = VMPATH_ADMIN;
            $trigger = 'onVmAdminController';
            vmJsApi::jQuery(false);
            vmJsApi::loadBECSS();
            $router = $app->getRouter();
            $router->setMode(0);

        } else {
            $session->set('manage', 0, 'vm');
            vRequest::setVar('manage', 0);
            $basePath = VMPATH_SITE;
            $app->redirect('index.php?option=com_tsmart', vmText::_('com_tsmart_RESTRICTED_ACCESS'));
        }

    } elseif ($_controller) {
        if ($_controller != 'productdetails') {
            $session->set('manage', 0, 'vm');
            vRequest::setVar('manage', '0');
        }
        vmJsApi::jQuery();
        vmJsApi::jSite();
        vmJsApi::cssSite();
        $basePath = VMPATH_SITE;
    }
}
// controller alias
if ($_controller == 'pluginresponse') {
    $_controller = 'vmplg';
}
/* Create the controller name */
$_class = 'tsmartController' . ucfirst($_controller);
if (file_exists($basePath . DS . 'controllers' . DS . $_controller . '.php')) {
    if (!class_exists($_class)) {
        require($basePath . DS . 'controllers' . DS . $_controller . '.php');
    }
} else {
    // try plugins
    JPluginHelper::importPlugin('vmextended');
    $dispatcher = JDispatcher::getInstance();
    $rets = $dispatcher->trigger($trigger, array($_controller));
    foreach ($rets as $ret) {
        if ($ret) return true;
    }
}
if (class_exists($_class)) {
    $controller = new $_class();
    $controller->execute($task);
    //vmTime($_class.' Finished task '.$task,'Start');
    vmRam('End');
    vmRamPeak('Peak');
    /* Redirect if set by the controller */
    $controller->redirect();
} else {
    vmDebug('tsmart controller not found: ' . $_class);
    if (tsmConfig::get('handle_404', 1)) {
        $mainframe = Jfactory::getApplication();
        $mainframe->redirect(JRoute::_('index.php?option=com_tsmart&view=tsmart', FALSE));
    } else {
        JError::raise(E_ERROR, '404', 'Not found');
    }
}
$html=ob_get_clean();
?>
<div class="component-tsmart">
    <div class="div-loading"></div>
    <?php echo $html ?>
</div>
<style type="text/css">
    .div-loading {
        display: none;
        background: url("<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif") center center no-repeat;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%
    }
</style>