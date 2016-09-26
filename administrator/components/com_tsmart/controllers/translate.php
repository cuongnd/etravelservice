<?php
/**
*
* Translate controller
*
* @package	tsmart
* @subpackage Translate
* @author Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: translate.php
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Translate Controller
 *
 * @package    tsmart
 * @subpackage Translate
 * @author Patrick Kohl
 */
class TsmartControllerTranslate extends TsmController {

	var $check 	= null;
	var $fields = null;


	function __construct() {
		parent::__construct();

	}


	/**
	 * Paste the table  in json format
	 *
	 */
	public function paste() {

		// TODO Test user ?
		$json= array();
		$json['fields'] = 'error' ;
		$json['msg'] = 'Invalid Token';
		$json['structure'] = 'empty' ;
		if (!vRequest::vmCheckToken(-1)) {
			echo json_encode($json) ;
			jexit(  );
		}

		$lang = vRequest::getvar('lg');
		$langs = VmConfig::get('active_languages',array()) ;
		$language=JFactory::getLanguage();

		if (!in_array($lang, $langs) ) {
			$json['msg'] = 'Invalid language ! '.$lang;
			$json['langs'] = $langs ;
			echo json_encode($json) ;
			jexit( );
		}
		$lang = strtolower( $lang);
		// Remove tag if defaut or
		// if ($language->getDefault() == $lang ) $dblang ='';

		$dblang= strtr($lang,'-','_');
		VmConfig::$vmlang = $dblang;
		$id = vRequest::getInt('id',0);

		$viewKey = vRequest::getCmd('editView');
		// TODO temp trick for vendor
		if ($viewKey == 'vendor') $id = 1 ;

		$tables = array ('category' =>'categories','product' =>'products','manufacturer' =>'manufacturers','manufacturercategories' =>'manufacturercategories','vendor' =>'vendors', 'paymentmethod' =>'paymentmethods', 'shipmentmethod' =>'shipmentmethods');

		if ( !array_key_exists($viewKey, $tables) ) {
			$json['msg'] ="Invalid view ". $viewKey;
			echo json_encode($json);
			jExit();
		}
		$tableName = '#__tsmart_'.$tables[$viewKey].'_'.$dblang;

		$m = tmsModel::getModel('coupon');
		$table = $m->getTable($tables[$viewKey]);

		//Todo create method to load lang fields only
		$table->load($id);
		$vs = $table->loadFieldValues();
		$lf = $table->getTranslatableFields();

		$json['fields'] = array();
		foreach($lf as $v){
			if(isset($vs[$v])){
				$json['fields'][$v] = $vs[$v];
			}
		}

		//if ($json['fields'] = $db->loadAssoc()) {
		if ($table->getLoaded()) {
			$json['structure'] = 'filled' ;
			$json['msg'] = tsmText::_('com_tsmart_SELECTED_LANG').':'.$lang;

		} else {
			$db =JFactory::getDBO();

			$json['structure'] = 'empty' ;
			$db->setQuery('SHOW COLUMNS FROM '.$tableName);
			$tableDescribe = $db->loadAssocList();
			array_shift($tableDescribe);
			$fields=array();
			foreach ($tableDescribe as $key =>$val) $fields[$val['Field']] = $val['Field'] ;
			$json['fields'] = $fields;
			$json['msg'] = tsmText::sprintf('com_tsmart_LANG_IS_EMPTY',$lang ,tsmText::_('com_tsmart_'.strtoupper( $viewKey)) ) ;
		}
		echo vmJsApi::safe_json_encode($json);
		jExit();

	}


}

//pure php no tag
