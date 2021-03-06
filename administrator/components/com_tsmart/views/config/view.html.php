<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Config
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 9035 2015-11-03 10:37:57Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for the configuration maintenance
 *
 * @package		tsmart
 * @subpackage 	Config
 * @author 		RickG
 */
class TsmartViewConfig extends tsmViewAdmin {

	function display($tpl = null) {

		if (!class_exists('VmImage'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();
		$usermodel = tmsModel::getModel('user');

		JToolBarHelper::title( tsmText::_('com_tsmart_CONFIG') , 'head vm_config_48');

		$this->addStandardEditViewCommands();

		$this->config = tsmConfig::loadConfig();
		if(!empty($this->config->_params)){
			unset ($this->config->_params['pdf_invoice']); // parameter remove and replaced by inv_os
		}

		$this->userparams = JComponentHelper::getParams('com_users');

		$this->jTemplateList = ShopFunctions::renderTemplateList(tsmText::_('com_tsmart_ADMIN_CFG_JOOMLA_TEMPLATE_DEFAULT'));

		$this->vmLayoutList = $model->getLayoutList('tsmart');

		$this->cartLayoutList = $model->getLayoutList('cart',array('padded.php','perror.php'));
		$this->categoryLayoutList = $model->getLayoutList('category');

		$this->productLayoutList = $model->getLayoutList('productdetails');

		$this->productsFieldList  = $model->getFieldList('products');

		$this->noimagelist = $model->getNoImageList();

		$this->orderStatusModel= tmsModel::getModel('orderstatus');

		$this->os_Options = $this->osWoP_Options = $this->osDel_Options = $this->orderStatusModel->getOrderStatusNames();
		$emptyOption = JHtml::_ ('select.option', -1, tsmText::_ ('com_tsmart_NONE'), 'order_status_code', 'order_status_name');

		array_unshift ($this->os_Options, $emptyOption);

		unset($this->osWoP_Options['P']);
		array_unshift ($this->osWoP_Options, $emptyOption);

		$deldate_inv = JHtml::_ ('select.option', 'm', tsmText::_ ('com_tsmart_DELDATE_INV'), 'order_status_code', 'order_status_name');
		unset($this->osDel_Options['P']);
		array_unshift ($this->osDel_Options, $deldate_inv);
		array_unshift ($this->osDel_Options, $emptyOption);

		//vmdebug('my $this->os_Options',$this->osWoP_Options);

		$this->currConverterList = $model->getCurrencyConverterList();

		$this->activeLanguages = $model->getActiveLanguages( tsmConfig::get('active_languages') );

		$this->orderByFieldsProduct = $model->getProductFilterFields('browse_orderby_fields');

		tmsModel::getModel('category');

		foreach (tsmartModelCategory::$_validOrderingFields as $key => $field ) {
			if($field=='c.category_shared') continue;
			$fieldWithoutPrefix = $field;
			$dotps = strrpos($fieldWithoutPrefix, '.');
			if($dotps!==false){
				$prefix = substr($field, 0,$dotps+1);
				$fieldWithoutPrefix = substr($field, $dotps+1);
			}

			$text = tsmText::_('com_tsmart_'.strtoupper(str_replace(array(',',' '),array('_',''),$fieldWithoutPrefix))) ;
			$orderByFieldsCat[] =  JHtml::_('select.option', $field, $text) ;
		}

		$this->orderByFieldsCat = $orderByFieldsCat;

		$this->searchFields = $model->getProductFilterFields( 'browse_search_fields');

		$this->aclGroups = $usermodel->getAclGroupIndentedTree();

		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		$this->vmtemplate = VmTemplate::loadVmTemplateStyle();
		$this->imagePath = shopFunctions::getAvailabilityIconUrl($this->vmtemplate);

		$this->listShipment = $this -> listIt('shipment');
		$this->listPayment = $this -> listIt('payment');

		shopFunctions::checkSafePath();
		$this -> checkTCPDFinstalled();
		$this -> checkVmUserVendor();
		//$this -> checkClientIP();
		parent::display($tpl);
	}

	private function listIt($ps){
		$db = JFactory::getDBO();
		$q = 'SELECT tsmart_'.$ps.'method_id,'.$ps.'_name
FROM #__tsmart_'.$ps.'methods
INNER JOIN #__tsmart_'.$ps.'methods_'.tsmConfig::$vmlang.' USING (tsmart_'.$ps.'method_id)
WHERE published="1"';
		$db->setQuery($q);

		try {
			$options = $db->loadAssocList();
		} catch (Exception $e){
			return array();
		}
		if(empty($options)) $options = array();
		$emptyOption = JHtml::_('select.option', '0', tsmText::_('com_tsmart_NOPREF'),'tsmart_'.$ps.'method_id',$ps.'_name');
		array_unshift($options,$emptyOption);
		$emptyOption = JHtml::_('select.option', '-1', tsmText::_('com_tsmart_NONE'),'tsmart_'.$ps.'method_id',$ps.'_name');
		array_unshift($options,$emptyOption);
		return $options;
	}

	private function checkVmUserVendor(){

		$db = JFactory::getDBO();
		$multix = tsmConfig::get('multix','none');

		$q = 'select * from #__tsmart_vmusers where user_is_vendor = 1';// and tsmart_vendor_id '.$vendorWhere.' limit 1';
		$db->setQuery($q);
		$r = $db->loadAssocList();

		if (empty($r)){
			vmWarn('Your tsmart installation contains an error: No user as marked as vendor. Please fix this in your phpMyAdmin and set #__tsmart_vmusers.user_is_vendor = 1 and #__tsmart_vmusers.tsmart_vendor_id = 1 to one of your administrator users. Please update all users to be associated with tsmart_vendor_id 1.');
		} else {
			if($multix=='none' and count($r)!=1){
				vmWarn('You are using single vendor mode, but it seems more than one user is set as vendor');
			}
			foreach($r as $entry){
				if(empty($entry['tsmart_vendor_id'])){
					vmWarn('The user with tsmart_user_id = '.$entry['tsmart_user_id'].' is set as vendor, but has no referencing vendorId.');
				}
			}
		}
	}

	private function checkTCPDFinstalled(){

		if(!file_exists(VMPATH_LIBS.DS.'tcpdf'.DS.'tcpdf.php')){
			vmWarn('com_tsmart_TCPDF_NINSTALLED');
		}
	}

	private function checkClientIP(){
		$revproxvar = tsmConfig::get('revproxvar','');
		if(!empty($revproxvar)) vmdebug('My server variable ',$_SERVER);
	}
}
// pure php no closing tag
