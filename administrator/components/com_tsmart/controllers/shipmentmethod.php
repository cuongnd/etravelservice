<?php
/**
*
* Shipment  controller
*
* @package	tsmart
* @subpackage Shipment
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: shipmentmethod.php 8953 2015-08-19 10:30:52Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Shipment  Controller
 *
 * @package    tsmart
 * @subpackage Shipment
 * @author RickG, Max Milbers
 */
class TsmartControllerShipmentmethod extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		tsmConfig::loadJLang('com_tsmart_orders',TRUE);
		parent::__construct();
	}

	/**
	 * We want to allow html in the descriptions.
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = vRequest::getPost();

		if(vmAccess::manager('raw')){
			$data['shipment_name'] = vRequest::get('shipment_name','');
			$data['shipment_desc'] = vRequest::get('shipment_desc','');
			if(isset($data['params'])){
				$data['params'] = vRequest::get('params','');
			}
		} else {
			$data['shipment_name'] = vRequest::getHtml('shipment_name','');
			$data['shipment_desc'] = vRequest::getHtml('shipment_desc','');
			if(isset($data['params'])){
				$data['params'] = vRequest::getHtml('params','');
			}
		}

		parent::save($data);

	}
	/**
	 * Clone a shipment
	 *
	 * @author Valérie Isaksen
	 */
	public function CloneShipment() {
		$mainframe = Jfactory::getApplication();

		/* Load the view object */
		$view = $this->getView('shipmentmethod', 'html');

		$model = tmsModel::getModel('shipmentmethod');
		$msgtype = '';

		$cids = vRequest::getVar($this->_cidName, vRequest::getInt('tsmart_shipment_id'));

		foreach($cids as $cid){
			if ($model->createClone($cid)) $msg = tsmText::_('com_tsmart_SHIPMENT_CLONED_SUCCESSFULLY');
			else {
				$msg = tsmText::_('com_tsmart_SHIPMENT_NOT_CLONED_SUCCESSFULLY');
				$msgtype = 'error';
			}
		}

		$mainframe->redirect('index.php?option=com_tsmart&view=shipmentmethod', $msg, $msgtype);
	}
}
// pure php no closing tag
