<?php
/**
 *
 * Product controller
 *
 * @package	tsmart
 * @subpackage
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Product Controller
 *
 * @package    tsmart
 * @author
 */
class TsmartControllerProduct extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct('tsmart_product_id');
		$this->addViewPath( VMPATH_ADMIN . DS . 'views');
	}

	public function ajax_get_list_tour_id_by_tour_type_id(){
		$input=JFactory::getApplication()->input;
		$tsmart_tour_type_id=$input->getInt('tsmart_tour_type_id');
		require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmproduct.php';
		$list_tour_type = vmproduct::get_list_product_by_tour_type_id($tsmart_tour_type_id);
		echo json_encode($list_tour_type);
		die;

	}
	public function ajax_get_list_service_class_by_tour_id(){
		$input=JFactory::getApplication()->input;
		$tsmart_product_id=$input->getInt('tsmart_product_id');
		require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
		$list_service_class = vmserviceclass::get_list_service_class_by_tour_id($tsmart_product_id);
		echo json_encode($list_service_class);
		die;

	}
	public function ajax_get_list_departure_by_tour_id(){
		$input=JFactory::getApplication()->input;
		$tsmart_product_id=$input->getInt('tsmart_product_id');
		require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmdeparture.php';
		$list_departure = tsmDeparture::get_list_departure_by_tour_id($tsmart_product_id);
		echo json_encode($list_departure);
		die;

	}
	/**
	 * Shows the product add/edit screen
	 */
	public function edit($layout='edit') {
		parent::edit('product_edit');
	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		if($data===0)$data = vRequest::getRequest();

		if(vmAccess::manager('raw')){
			$data['product_desc'] = vRequest::get('product_desc','');
			$data['product_s_desc'] = vRequest::get('product_s_desc','');
			$data['customtitle'] = vRequest::get('customtitle','');

			if(isset($data['field'])){
				$data['field'] = vRequest::get('field');
			}

			if(isset($data['childs'])){
				foreach($data['childs'] as $k=>$v){
					if($n = vRequest::get('product_name',false,FILTER_UNSAFE_RAW,FILTER_FLAG_NO_ENCODE,$data['childs'][$k])){
						$data['childs'][$k]['product_name'] = $n;
					}
				}
			}

		} else  {
			if(vmAccess::manager('html')){
				$data['product_desc'] = vRequest::getHtml('product_desc','');
				$data['product_s_desc'] = vRequest::getHtml('product_s_desc','');
				$data['customtitle'] = vRequest::getHtml('customtitle','');

				if(isset($data['field'])){
					$data['field'] = vRequest::getHtml('field');
				}
			} else {
				$data['product_desc'] = vRequest::getString('product_desc','');
				$data['product_s_desc'] = vRequest::getString('product_s_desc','');
				$data['customtitle'] = vRequest::getString('customtitle','');

				if(isset($data['field'])){
					$data['field'] = vRequest::getString('field');
				}
			}

			//Why we have this?
			$multix = Vmconfig::get('multix','none');
			if( $multix != 'none' ){
				//in fact this shoudl be used, when the mode is administrated and the system is so that
				//every product must be approved by an admin.
				unset($data['published']);
				//unset($data['childs']);
			}

		}
		parent::save($data);
	}

	function saveJS(){

		vRequest::vmCheckToken();

		$model = VmModel::getModel($this->_cname);

		$data = vRequest::getRequest();
		$id = $model->store($data);

		$msg = 'failed';
		if(!empty($id)) {
			$msg = tsmText::sprintf('com_tsmart_STRING_SAVED',$this->mainLangKey);
			$type = 'message';
		}
		else $type = 'error';

		$json['msg'] = $msg;
		if ($id) {
			$json['product_id'] = $id;

			$json['ok'] = 1 ;
		} else {
			$json['ok'] = 0 ;

		}
		echo vmJsApi::safe_json_encode($json);
		jExit();

	}

	/**
	 * This task creates a child by a given product id
	 *
	 * @author Max Milbers
	 */
	public function createChild(){

		vRequest::vmCheckToken();

		$app = Jfactory::getApplication();

		$model = VmModel::getModel('product');

		$cids = vRequest::getInt($this->_cidName, vRequest::getint('tsmart_product_id',false));
		if(!is_array($cids) and $cids > 0){
			$cids = array($cids);
		}
		$target = vRequest::getCmd('target',false);

		$msgtype = 'info';
		foreach($cids as $cid){
			if ($id=$model->createChild($cid)){
				$msg = tsmText::_('com_tsmart_PRODUCT_CHILD_CREATED_SUCCESSFULLY');


				if($target=='parent'){
					vmdebug('toParent');
					$redirect = 'index.php?option=com_tsmart&view=product&task=edit&tsmart_product_id='.$cids[0];
				} else {
					$redirect = 'index.php?option=com_tsmart&view=product&task=edit&tsmart_product_id='.$id;
				}

			} else {
				$msg = tsmText::_('com_tsmart_PRODUCT_NO_CHILD_CREATED_SUCCESSFULLY');
				$msgtype = 'error';
				$redirect = 'index.php?option=com_tsmart&view=product';
			}
		}
		$app->redirect($redirect, $msg, $msgtype);

	}


	public function massxref_sgrps(){

		$this->massxref('massxref');
	}

	public function massxref_sgrps_exe(){

		$tsmart_shoppergroup_ids = vRequest::getInt('tsmart_shoppergroup_id');

		$session = JFactory::getSession();
		$cids = json_decode($session->get('vm_product_ids', array(), 'vm'),true);

		$productModel = VmModel::getModel('product');
		foreach($cids as $cid){
			$data = array('tsmart_product_id' => $cid, 'tsmart_shoppergroup_id' => $tsmart_shoppergroup_ids);
			$data = $productModel->updateXrefAndChildTables ($data, 'product_shoppergroups');
		}

		$this->massxref('massxref_sgrps');
	}

	public function massxref_cats(){
		$this->massxref('massxref');
	}

	public function massxref_cats_exe(){

		$tsmart_cat_ids = vRequest::getInt('cid', array() );

		$session = JFactory::getSession();
		$cids = json_decode($session->get('vm_product_ids', array(), 'vm'),true);

		$productModel = VmModel::getModel('product');
		foreach($cids as $cid){
			$data = array('tsmart_product_id' => $cid, 'tsmart_category_id' => $tsmart_cat_ids);
			$data = $productModel->updateXrefAndChildTables ($data, 'product_categories',TRUE);
		}

		$this->massxref('massxref_cats');
	}

	public function massxref($layoutName){

		vRequest::vmCheckToken();

		$cids = vRequest::getInt('tsmart_product_id');

		if(empty($cids)){
			$session = JFactory::getSession();
			$cids = json_decode($session->get('vm_product_ids', '', 'vm'),true);
		} else {
			$session = JFactory::getSession();
			$session->set('vm_product_ids', json_encode($cids),'vm');
			$session->set('reset_pag', true,'vm');

		}

		if(!empty($cids)){
			$q = 'SELECT `product_name` FROM `#__tsmart_products_' . VmConfig::$vmlang . '` ';
			$q .= ' WHERE `tsmart_product_id` IN (' . implode(',', $cids) . ')';

			$db = JFactory::getDbo();
			$db->setQuery($q);

			$productNames = $db->loadColumn();

			vmInfo('com_tsmart_PRODUCT_XREF_NAMES',implode(', ',$productNames));
		}

		$this->addViewPath(VMPATH_ADMIN . DS . 'views');
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView($this->_cname, $viewType);

		$view->setLayout($layoutName);

		$view->display();
	}

	/**
	 * Clone a product
	 *
	 * @author Max Milbers
	 */
	public function CloneProduct() {
		$mainframe = Jfactory::getApplication();

		$view = $this->getView('product', 'html');

		$model = VmModel::getModel('product');
		$msgtype = '';

		$cids = vRequest::getInt($this->_cidName, vRequest::getInt('tsmart_product_id'));

		foreach($cids as $cid){
			if ($model->createClone($cid)) {
				$msg = tsmText::_('com_tsmart_PRODUCT_CLONED_SUCCESSFULLY');
			} else {
				$msg = tsmText::_('com_tsmart_PRODUCT_NOT_CLONED_SUCCESSFULLY');
				$msgtype = 'error';
			}
		}

		$mainframe->redirect('index.php?option=com_tsmart&view=product', $msg, $msgtype);
	}


	/**
	 * Get a list of related products, categories
	 * or customfields
	 * @author Max Milbers
	 * @author Kohl Patrick
	 */
	public function getData() {
		$view = $this->getView('product', 'json');
		$view->display(NULL);
	}

	/**
	 * Add a product rating
	 * @author Max Milbers
	 */
	public function addRating() {
		$mainframe = Jfactory::getApplication();

		// Get the product ID
		$cids = vRequest::getInt($this->_cidName, vRequest::getInt('tsmart_product_id'));
		$mainframe->redirect('index.php?option=com_tsmart&view=ratings&task=add&tsmart_product_id='.$cids[0]);
	}


	public function ajax_notifyUsers(){

		$tsmart_product_id = vRequest::getInt('tsmart_product_id');
		if(is_array($tsmart_product_id) and count($tsmart_product_id) > 0){
			$tsmart_product_id = (int)$tsmart_product_id[0];
		} else {
			$tsmart_product_id = (int)$tsmart_product_id;
		}

		$subject = vRequest::getVar('subject', '');
		$mailbody = vRequest::getVar('mailbody',  '');
		$max_number = (int)vRequest::getVar('max_number', '');
		
		$waitinglist = VmModel::getModel('Waitinglist');
		$waitinglist->notifyList($tsmart_product_id,$subject,$mailbody,$max_number);
		exit;
	}
	
	public function ajax_waitinglist() {

		$tsmart_product_id = vRequest::getInt('tsmart_product_id');
		if(is_array($tsmart_product_id) && count($tsmart_product_id) > 0){
			$tsmart_product_id = (int)$tsmart_product_id[0];
		} else {
			$tsmart_product_id = (int)$tsmart_product_id;
		}

		$waitinglistmodel = VmModel::getModel('waitinglist');
		$waitinglist = $waitinglistmodel->getWaitingusers($tsmart_product_id);

		if(empty($waitinglist)) $waitinglist = array();
		
		echo vmJsApi::safe_json_encode($waitinglist);
		exit;

	}


}
// pure php no closing tag
