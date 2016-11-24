<?php

/**
 *
 * updatesMigration controller
 *
 * @package	tsmart
 * @subpackage updatesMigration
 * @author Max Milbers, RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: updatesmigration.php 9008 2015-10-04 20:41:08Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))
require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmcontroller.php');

/**
 * updatesMigration Controller
 *
 * @package    tsmart
 * @subpackage updatesMigration
 * @author Max Milbers
 */
class TsmartControllerUpdatesMigration extends TsmController{

	private $installer;

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct(){
		parent::__construct();

	}

	/**
	 * Call at begin of every task to check if the permission is high enough.
	 * Atm the standard is at least vm admin
	 * @author Max Milbers
	 */
	private function checkPermissionForTools(){
		vRequest::vmCheckToken();
		//Hardcore Block, we may do that better later
		$user = JFactory::getUser();
		if(!vmAccess::manager('core') ){
			$msg = 'Forget IT';
			$this->setRedirect('index.php?option=com_tsmart', $msg);
		}

		return true;
	}

	/**
	 * Akeeba release system tasks
	 * Update
	 * @author Max Milbers
	 */
	function liveUpdate(){

		$this->setRedirect('index.php?option=com_tsmart&view=liveupdate.', 'Akeeba release system');
	}

	/**
	 * Install sample data into the database
	 *
	 * @author RickG
	 */
	function checkForLatestVersion(){
		$model = $this->getModel('updatesMigration');
		vRequest::setVar('latestverison', $model->getLatestVersion());
		vRequest::setVar('view', 'updatesMigration');

		parent::display();
	}

	/**
	 * Install sample data into the database
	 *
	 * @author Max Milbers
	 */
	function installSampleData(){

		$this->checkPermissionForTools();

		$model = $this->getModel('updatesMigration');

		$msg = $model->installSampleData();

		$this->setRedirect($this->redirectPath, $msg);
	}

	/**
	 * Sets the storeowner to the currently logged in user
	 * He needs admin rights
	 *
	 * @author Max Milbers
	 */
	function setStoreOwner(){

		$this->checkPermissionForTools();

		$model = $this->getModel('updatesMigration');

		$storeOwnerId =vRequest::getInt('storeOwnerId');
		$msg = $model->setStoreOwner($storeOwnerId);

		$this->setRedirect($this->redirectPath, $msg);
	}

	/**
	 * Install sample data into the database
	 *
	 * @author RickG
	 * @author Max Milbers
	 */
	function restoreSystemDefaults(){

		$this->checkPermissionForTools();

		if(tsmConfig::get('dangeroustools', false)){

			$model = $this->getModel('updatesMigration');
			$model->restoreSystemDefaults();

			$msg = tsmText::_('com_tsmart_SYSTEM_DEFAULTS_RESTORED');
			$msg .= ' User id of the main vendor is ' . $model->setStoreOwner();
			$this->setDangerousToolsOff();
		} else {
			$msg = $this->_getMsgDangerousTools();
		}

		$this->setRedirect($this->redirectPath, $msg);
	}

	public function fixCustomsParams(){
		$this->checkPermissionForTools();
		$q = 'SELECT `tsmart_customfield_id` FROM `#__tsmart_product_customfields` LEFT JOIN `#__tsmart_customs` USING (`tsmart_custom_id`) ';
		$q = 'SELECT `tsmart_customfield_id`,`customfield_params` FROM `#__tsmart_product_customfields` ';
		$q .= ' WHERE `customfield_params`!="" ';
		$db = JFactory::getDbo();
		$db->setQuery($q);

		$rows = $db->loadAssocList();

		foreach($rows as $fields){
			$store = '';
			if(empty($fields['customfield_params'])) continue;

			$json = @json_decode($fields['customfield_params']);

			if($json){

				$vars = get_object_vars($json);

				foreach($vars as $key=>$value){
					if(!empty($key)){
						$store .= $key . '=' . vmJsApi::safe_json_encode($value) . '|';
					}

				}

				if(!empty($store)){
					$q = 'UPDATE `#__tsmart_product_customfields` SET `customfield_params` = "'.$db->escape($store).'" WHERE `tsmart_customfield_id` = "'.$fields['tsmart_customfield_id'].'" ';
					$db->setQuery($q);
					$db->execute();

				}

			}

		}
		$msg = 'Executed';
		$this->setredirect($this->redirectPath, $msg);
	}

	/**
	 * Quite unsophisticated, but it does it jobs if there are not too much products/customfields.
	 *
	 */
	public function deleteInheritedCustoms () {

		$msg = '';
		$this->checkPermissionForTools();
		if(tsmConfig::get('dangeroustools', false)){

			$db = JFactory::getDbo();

			/*$q = 'SELECT customfield_id ';
			$q .= 'FROM `#__tsmart_product_customfields` as pc WHERE
					LEFT JOIN `#__tsmart_products` as c using (`tsmart_product_id`) ';
			$q .= 'WHERE c.product_parent_id =';*/
			$q = ' SELECT `product_parent_id` FROM `#__tsmart_products`
					INNER JOIN `#__tsmart_product_customfields` as pc using (`tsmart_product_id`)
					WHERE `product_parent_id` != "0" GROUP BY `product_parent_id` ';
			$db->setQuery($q);
			$childs = $db->loadColumn();

			$toDelete = array();
			foreach($childs as $child_id){

				$q = ' SELECT pc.tsmart_customfield_id,pc.tsmart_custom_id,pc.customfield_value,pc.customfield_price,pc.customfield_params
					FROM `#__tsmart_product_customfields` as pc
					LEFT JOIN `#__tsmart_products` as c using (`tsmart_product_id`) ';
				$q .= ' WHERE c.tsmart_product_id = "'.$child_id.'" ';
				$db->setQuery($q);
				$pcfs = $db->loadAssocList();
				vmdebug('load PCFS '.$q);
				if($pcfs){
					vmdebug('There are PCFS');
					$q = ' SELECT pc.tsmart_customfield_id,pc.tsmart_custom_id,pc.customfield_value,pc.customfield_price,pc.customfield_params
					FROM `#__tsmart_product_customfields` as pc
					LEFT JOIN `#__tsmart_products` as c using (`tsmart_product_id`) ';
					$q .= ' WHERE c.product_parent_id = "'.$child_id.'" ';

					$db->setQuery($q);
					$cfs = $db->loadAssocList();

					foreach($cfs as $cf){
						foreach($pcfs as $pcf){
							if($cf['tsmart_custom_id'] == $pcf['tsmart_custom_id']){
									vmdebug('tsmart_custom_id same');
								if($cf['customfield_value'] == $pcf['customfield_value'] and
								$cf['customfield_price'] == $pcf['customfield_price'] and
								$cf['customfield_params'] == $pcf['customfield_params']){
									$toDelete[] = $cf['tsmart_customfield_id'];
								}
							}
						}
					}
				}

			}

			if(count($toDelete)>0){
				$toDelete = array_unique($toDelete,SORT_NUMERIC);
				$toDeleteString = implode(',',$toDelete);
				$q = 'DELETE FROM `#__tsmart_product_customfields` WHERE tsmart_customfield_id IN ('.$toDeleteString.') ';
				$db->setQuery($q);
				$db->execute();
			}

			/*$q = 'SELECT `tsmart_customfield_id`
					FROM `#__tsmart_product_customfields` as pc
					LEFT JOIN `#__tsmart_products` as c using (`tsmart_product_id`)';
			$q .= ' WHERE c.product_parent_id != "0" AND ';*/
		} else {
			$msg = $this->_getMsgDangerousTools();
		}
		$this->setredirect($this->redirectPath, $msg);
	}

	/**
	 * Remove all the tsmart tables from the database.
	 *
	 * @author Max Milbers
	 */
	function deleteVmTables(){

		$this->checkPermissionForTools();

		$msg = tsmText::_('com_tsmart_SYSTEM_VMTABLES_DELETED');
		if(tsmConfig::get('dangeroustools', false)){
			$model = $this->getModel('updatesMigration');

			if(!$model->removeAllVMTables()){
				$this->setDangerousToolsOff();
				$this->setRedirect('index.php?option=com_tsmart');
			}
		}else {
			$msg = $this->_getMsgDangerousTools();
		}
		$this->setRedirect('index.php?option=com_installer', $msg);
	}

	/**
	 * Deletes all dynamical created data and leaves a "fresh" installation without sampledata
	 * OUTDATED
	 * @author Max Milbers
	 *
	 */
	function deleteVmData(){

		$this->checkPermissionForTools();

		$msg = tsmText::_('com_tsmart_SYSTEM_VMDATA_DELETED');
		if(tsmConfig::get('dangeroustools', false)){
			$model = $this->getModel('updatesMigration');

			if(!$model->removeAllVMData()){
				$this->setDangerousToolsOff();
				$this->setRedirect('index.php?option=com_tsmart');
			}
		}else {
			$msg = $this->_getMsgDangerousTools();
		}

		$this->setRedirect($this->redirectPath, $msg);
	}

	function deleteAll(){

		$this->checkPermissionForTools();

		$msg = tsmText::_('com_tsmart_SYSTEM_ALLVMDATA_DELETED');
		if(tsmConfig::get('dangeroustools', false)){

			$this->installer->populateVmDatabase("delete_essential.sql");
			$this->installer->populateVmDatabase("delete_data.sql");
			$this->setDangerousToolsOff();
		}else {
			$msg = $this->_getMsgDangerousTools();
		}

		$this->setRedirect($this->redirectPath, $msg);
	}

	function deleteRestorable(){

		$this->checkPermissionForTools();

		$msg = tsmText::_('com_tsmart_SYSTEM_RESTVMDATA_DELETED');
		if(tsmConfig::get('dangeroustools', false)){
			$this->installer->populateVmDatabase("delete_restoreable.sql");
			$this->setDangerousToolsOff();
		}else {
			$msg = $this->_getMsgDangerousTools();
		}


		$this->setRedirect($this->redirectPath, $msg);
	}

	function refreshCompleteInstallAndSample(){

		$this->refreshCompleteInstall(true);
	}


	function refreshCompleteInstall($sample=false){

		$this->checkPermissionForTools();

		if(tsmConfig::get('dangeroustools', true)){

			$model = $this->getModel('updatesMigration');

			$model->restoreSystemTablesCompletly();

			$sid = $model->setStoreOwner();

			$sampletxt = '';
			if($sample){
				$model->installSampleData($sid);
				$sampletxt = ' and sampledata installed';
			}

			$msg = '';
			if(empty($errors)){
				$msg = 'System succesfull restored'.$sampletxt.', user id of the mainvendor is ' . $sid;
			} else {
				foreach($errors as $error){
					$msg .= ( $error) . '<br />';
				}
			}

			$this->setDangerousToolsOff();
		}else {
			$msg = $this->_getMsgDangerousTools();
		}

		$this->setRedirect($this->redirectPath, $msg);
	}

	function installCompleteSamples(){
		$this->installComplete(true);
	}

	function installComplete($sample=false){

		$this->checkPermissionForTools();

		if(tsmConfig::get('dangeroustools', true)){

			if(!class_exists('com_tsmartInstallerScript')) require(VMPATH_ADMIN . DS . 'install' . DS . 'script.tsmart.php');
			$updater = new com_tsmartInstallerScript();
			$updater->install(true);

			$model = $this->getModel('updatesMigration');
			$sid = $model->setStoreOwner();

			$msg = 'System and sampledata succesfull installed, user id of the mainvendor is ' . $sid;

			if(!class_exists('com_tsmart_allinoneInstallerScript')) require(VMPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart_allinone' . DS . 'script.vmallinone.php');
			$updater = new com_tsmart_allinoneInstallerScript(false);
			$updater->vmInstall(true);

			if($sample) $model->installSampleData($sid);

			if(!class_exists('tsmConfig')) require_once(VMPATH_ADMIN .'/models/config.php');
			tsmartModelConfig::installVMconfigTable();

			//Now lets set some joomla variables
			//Caching should be enabled, set to files and for 15 minutes
			if(JVM_VERSION>2){
				if (!class_exists( 'ConfigModelCms' )) require(VMPATH_ROOT.DS.'components'.DS.'com_config'.DS.'model'.DS.'cms.php');
				if (!class_exists( 'ConfigModelForm' )) require(VMPATH_ROOT.DS.'components'.DS.'com_config'.DS.'model'.DS.'application.php');
				if (!class_exists( 'ConfigModelApplication' )) require(VMPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_config'.DS.'model'.DS.'application.php');
			} else {
				if (!class_exists( 'ConfigModelApplication' )) require(VMPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_config'.DS.'models'.DS.'application.php');
			}

			$jConfModel = new ConfigModelApplication();
			$jConfig = $jConfModel->getData();

			$jConfig['caching'] = 0;
			$jConfig['lifetime'] = 60;
			$jConfig['list_limit'] = 25;
			$jConfig['MetaDesc'] = 'tsmart works with Joomla! - the dynamic portal engine and content management system';
			$jConfig['MetaKeys'] = 'tsmart, vm2, joomla, Joomla';

			$app = JFactory::getApplication();
			$return = $jConfModel->save($jConfig);

			// Check the return value.
			if ($return === false) {
				// Save the data in the session.
				$app->setUserState('com_config.config.global.data', $jConfig);
				vmError(tsmText::sprintf('JERROR_SAVE_FAILED', 'installComplete'));
				//return false;
			} else {
				// Set the success message.
				//vmInfo('COM_CONFIG_SAVE_SUCCESS');
			}
		}else {
			$msg = $this->_getMsgDangerousTools();
		}

		$this->setRedirect('index.php?option=com_tsmart&view=updatesmigration&layout=insfinished', $msg);
	}

	/**
	 * This is executing the update table commands to adjust tables to the latest layout
	 * @author Max Milbers
	 */
	function updateDatabase(){
		vRequest::vmCheckToken();
		if(!class_exists('com_tsmartInstallerScript')) require(VMPATH_ADMIN . DS . 'install' . DS . 'script.tsmart.php');
		$updater = new com_tsmartInstallerScript();
		$updater->update(false);
		$this->setRedirect($this->redirectPath, 'Database updated');
	}

	/**
	 * This is executing the update table commands to adjust joomla tables to the latest layout
	 * @author Max Milbers
	 */
	function updateDatabaseJoomla(){
		vRequest::vmCheckToken();
		if(JVM_VERSION<3){
			$p = VMPATH_ADMIN.DS.'install'.DS.'joomla2.sql';
		} else {
			$p = '';
		}
		//$p = VMPATH_ROOT.DS.'installation'.DS.'sql'.DS.'mysql'.DS.'joomla.sql';
		$msg = 'You are using joomla 3, or File '.$p.' not found';
		if(file_exists($p)){
			if(!class_exists('GenericTableUpdater')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tableupdater.php');
			$updater = new GenericTableUpdater();
			$updater->updateMyVmTables($p,'_');
			$msg = 'Joomla Database updated';
		}
		$this->setRedirect($this->redirectPath, $msg);
	}

	/**
	 * Delete the config stored in the database and renews it using the file
	 *
	 * @auhtor Max Milbers
	 */
	function renewConfig(){

		$this->checkPermissionForTools();

		//if(VmConfig::get('dangeroustools', true)){
			$model = $this->getModel('config');
			$model -> deleteConfig();
	//	}
		$this->setRedirect($this->redirectPath, 'Configuration is now restored by file');
	}

	/**
	 * This function resets the flag in the config that dangerous tools can't be executed anylonger
	 * This is a security feature
	 *
	 * @author Max Milbers
	 */
	function setDangerousToolsOff(){

		if(!class_exists('tsmartModelConfig')) require(VMPATH_ADMIN .'/models/config.php');
		$res  = tsmartModelConfig::checkConfigTableExists();
		if(!empty($res)){
			$model = $this->getModel('config');
			$model->setDangerousToolsOff();
		}

	}

	/**
	 * Sends the message to the user that the tools are disabled.
	 *
	 * @author Max Milbers
	 */
	function _getMsgDangerousTools(){
		tsmConfig::loadJLang('com_tsmart_config');
		$link = JURI::root() . 'administrator/index.php?option=com_tsmart&view=config';
		$msg = tsmText::sprintf('com_tsmart_SYSTEM_DANGEROUS_TOOL_DISABLED', tsmText::_('com_tsmart_ADMIN_CFG_DANGEROUS_TOOLS'), $link);
		return $msg;
	}

	function portMedia(){

		$this->checkPermissionForTools();

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->portMedia();

		$this->setRedirect($this->redirectPath, $result);
	}

	function migrateGeneralFromVmOne(){

		$this->checkPermissionForTools();

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->migrateGeneral();
		if($result){
			$msg = 'Migration general finished';
		} else {
			$msg = 'Migration general was interrupted by max_execution time, please restart';
		}
		$this->setRedirect($this->redirectPath, $result);

	}

	function migrateUsersFromVmOne(){

		$this->checkPermissionForTools();

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->migrateUsers();
		if($result){
			$msg = 'Migration users finished';
		} else {
			$msg = 'Migration users was interrupted by max_execution time, please restart';
		}

		$this->setRedirect($this->redirectPath, $result);

	}

	function migrateProductsFromVmOne(){

		$this->checkPermissionForTools();

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->migrateProducts();
		if($result){
			$msg = 'Migration products finished';
		} else {
			$msg = 'Migration products was interrupted by max_execution time, please restart';
		}
		$this->setRedirect($this->redirectPath, $result);

	}

	function migrateOrdersFromVmOne(){

		$this->checkPermissionForTools();

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->migrateOrders();
		if($result){
			$msg = 'Migration orders finished';
		} else {
			$msg = 'Migration orders was interrupted by max_execution time, please restart';
		}
		$this->setRedirect($this->redirectPath, $result);

	}

	/**
	 * Is doing all migrator steps in one row
	 *
	 * @author Max Milbers
	 */
	function migrateAllInOne(){

		$this->checkPermissionForTools();

		if(!tsmConfig::get('dangeroustools', true)){
			$msg = $this->_getMsgDangerousTools();
			$this->setRedirect($this->redirectPath, $msg);
			return false;
		}

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->migrateAllInOne();
		if($result){
			$msg = 'Migration finished';
		} else {
			$msg = 'Migration was interrupted by max_execution time, please restart';
		}
		$this->setRedirect($this->redirectPath, $msg);
	}

	function portVmAttributes(){

		$this->checkPermissionForTools();

		if(!tsmConfig::get('dangeroustools', true)){
			$msg = $this->_getMsgDangerousTools();
			$this->setRedirect($this->redirectPath, $msg);
			return false;
		}

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->portVm1Attributes();
		if($result){
			$msg = 'Migration Vm2 attributes finished';
		} else {
			$msg = 'Migration was interrupted by max_execution time, please restart';
		}
		$this->setRedirect($this->redirectPath, $msg);
	}

	function portVmRelatedProducts(){

		$this->checkPermissionForTools();

		if(!tsmConfig::get('dangeroustools', true)){
			$msg = $this->_getMsgDangerousTools();
			$this->setRedirect($this->redirectPath, $msg);
			return false;
		}

		$this->storeMigrationOptionsInSession();
		if(!class_exists('Migrator')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'migrator.php');
		$migrator = new Migrator();
		$result = $migrator->portVm1RelatedProducts();
		if($result){
			$msg = 'Migration Vm2 related products finished';
		} else {
			$msg = 'Migration was interrupted by max_execution time, please restart';
		}
		$this->setRedirect($this->redirectPath, $msg);
	}

	function reOrderChilds(){

		$this->checkPermissionForTools();

		if(!tsmConfig::get('dangeroustools', true)){
			$msg = $this->_getMsgDangerousTools();
			$this->setRedirect($this->redirectPath, $msg);
			return false;
		}

		$this->storeMigrationOptionsInSession();
		if(!class_exists('GenericTableUpdater')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tableupdater.php');
		$updater = new GenericTableUpdater();
		$result = $updater->reOrderChilds();

		$this->setRedirect($this->redirectPath, $result);
	}

	function storeMigrationOptionsInSession(){

		$session = JFactory::getSession();

		$session->set('migration_task', vRequest::getString('task',''), 'vm');
		$session->set('migration_default_category_browse', vRequest::getString('migration_default_category_browse',''), 'vm');
		$session->set('migration_default_category_fly', vRequest::getString('migration_default_category_fly',''), 'vm');
	}


	function resetThumbs(){

		$this->checkPermissionForTools();

		if(!tsmConfig::get('dangeroustools', true)){
			$msg = $this->_getMsgDangerousTools();
			$this->setRedirect($this->redirectPath, $msg);
			return false;
		}

		$model = tmsModel::getModel('updatesMigration');
		$result = $model->resetThumbs();
		$this->setRedirect($this->redirectPath, $result);
	}
}
