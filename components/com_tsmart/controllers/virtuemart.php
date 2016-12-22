<?php
/**
*
* Base controller Frontend
*
* @package		tsmart
* @subpackage
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2011-2014 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: tsmart.php 8850 2015-05-13 14:06:11Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * tsmart Component Controller
 *
 * @package		tsmart
 */
class TsmartControllertsmart extends JControllerLegacy
{

	function __construct() {
		parent::__construct();
		if (tsmConfig::get('shop_is_offline') == '1') {
		    vRequest::setVar( 'layout', 'off_line' );
	    }
	    else {
		    vRequest::setVar( 'layout', 'default' );
	    }
	}

	/**
	 * Override of display to prevent caching
	 *
	 * @return  JController  A JController object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false){

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = vRequest::getCmd('view', 'tsmart');
		$viewLayout = vRequest::getCmd('layout', 'default');

		//vmdebug('basePath is NOT VMPATH_SITE',$this->basePath,VMPATH_SITE);
		$view = $this->getView($viewName, $viewType);
		$view->assignRef('document', $document);

		$view->display();

		return $this;
	}

	public function feed(){

		if(!class_exists( 'vmRSS' )) require(VMPATH_ADMIN.'/helpers/tsmrss.php');

		$this->tsmartFeed = vmRSS::gettsmartRssFeed();
		$this->extensionsFeed = vmRSS::getExtensionsRssFeed();

		$document = JFactory::getDocument();
		$headData = $document->getHeadData();
		$headData['scripts'] = array();
		$document->setHeadData($headData);

		ob_clean();
		ob_start();
		include(VMPATH_SITE.DS.'views'.DS.'tsmart'.DS.'tmpl'.DS.'feed.php');
		echo ob_get_clean();
		jExit();
	}

	public function keepalive(){
		jExit();
	}
}
 //pure php no closing tag
