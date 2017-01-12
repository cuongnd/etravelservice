<?php
defined('_JEXEC') or die('');
/**
 * abstract controller class containing get,store,delete,publish and pagination
 *
 *
 * This class provides the functions for the calculatoins
 *
 * @package	tsmart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2011 - 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://tsmart.net
 */
// Load the view framework
jimport( 'joomla.application.component.view');
// Load default helpers

class VmView extends JViewLegacy{

	var $isMail = false;
	var $isPdf = false;
	var $writeJs = true;

	public function display($tpl = null) {

		if($this->isMail or $this->isPdf){
			$this->writeJs = false;
		}
        if (!class_exists('VmHTML'))
        {
            require(VMPATH_SITE . DS . 'helpers' . DS . 'html.php');
        }
		$result = $this->loadTemplate($tpl);
		if ($result instanceof Exception) {
			return $result;
		}

		echo $result;
		if($this->writeJs){
			self::withKeepAlive();
			if(get_class($this)!='TsmartViewProductdetails'){
				echo vmJsApi::writeJS();
			}
		}
		vmTime('vm view Finished task ','Start');
	}

	public function withKeepAlive(){

		if (!class_exists('tsmartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = tsmartCart::getCart();
		if(!empty($cart->cartProductsData)){
			vmJsApi::keepAlive(1,4);
		}
	}

	/**
	 * Renders sublayouts
	 *
	 * @author Max Milbers
	 * @param $name
	 * @param int $viewData viewdata for the rendered sublayout, do not remove
	 * @return string
	 */
	public function renderVmSubLayout($name=0,$viewData=0){

		if ($name === 0) {
			$name = $this->_name;
		}

		$lPath = self::getVmSubLayoutPath ($name);

		if($lPath){
			if($viewData!==0 and is_array($viewData)){
				foreach($viewData as $k => $v){
					if ('_' != substr($k, 0, 1) and !isset($this->$k)) {
						$this->$k = $v;
					}
				}
			}
			ob_start ();
			include ($lPath);
			return ob_get_clean();
		} else {
			vmdebug('renderVmSubLayout layout not found '.$name);
		}

	}

	static public function getVmSubLayoutPath($name){
		$lPath = false;
		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		$vmStyle = VmTemplate::loadVmTemplateStyle();
		$template = $vmStyle['template'];
		// get the template and default paths for the layout if the site template has a layout override, use it
		$templatePath = JPATH_SITE . DS . 'templates' . DS . $template . DS . 'html' . DS . 'com_tsmart' . DS . 'sublayouts' . DS . $name . '.php';

		if(!class_exists('JFile')) require(VMPATH_LIBS.DS.'joomla'.DS.'filesystem'.DS.'file.php');
		if (JFile::exists ($templatePath)) {
			$lPath =  $templatePath;
		} else {
			if (JFile::exists (VMPATH_SITE . DS . 'sublayouts' . DS . $name . '.php')) {
				$lPath = VMPATH_SITE . DS . 'sublayouts' . DS . $name . '.php';
			}
		}
		return $lPath;
	}

	function prepareContinueLink(){

		$tsmart_category_id = shopFunctionsF::getLastVisitedCategoryId ();
		$categoryStr = '';
		if ($tsmart_category_id) {
			$categoryStr = '&tsmart_category_id=' . $tsmart_category_id;
		}

		$ItemidStr = '';
		$Itemid = shopFunctionsF::getLastVisitedItemId();
		if(!empty($Itemid)){
			$ItemidStr = '&Itemid='.$Itemid;
		}

		$lang = '';
		if(tsmConfig::$langCount>1 and !empty(tsmConfig::$vmlangSef)){
			$lang = '&lang='.tsmConfig::$vmlangSef;
		}

		$this->continue_link = JURI::root() .'index.php?option=com_tsmart&view=category' . $categoryStr.$lang.$ItemidStr;
		$this->continue_link_html = '<a class="continue_link" href="' . $this->continue_link . '">' . tsmText::_ ('com_tsmart_CONTINUE_SHOPPING') . '</a>';

		$this->cart_link = JURI::root().'index.php?option=com_tsmart&view=cart'.$lang;

		return;
	}

	function linkIcon($link,$altText ='',$boutonName,$verifyConfigValue=false, $modal = true, $use_icon=true,$use_text=false,$class = ''){
		if ($verifyConfigValue) {
			if ( !tsmConfig::get($verifyConfigValue, 0) ) return '';
		}
		$folder = 'media/system/images/'; //shouldn't be root slash before media, as it automatically tells to look in root directory, for media/system/ which is wrong it should append to root directory.
		$text='';
		if ( $use_icon ) $text .= JHtml::_('image', $folder.$boutonName.'.png',  tsmText::_($altText), null, false, false); //$folder shouldn't be as alt text, here it is: image(string $file, string $alt, mixed $attribs = null, boolean $relative = false, mixed $path_rel = false) : string, you should change first false to true if images are in templates media folder
		if ( $use_text ) $text .= '&nbsp;'. tsmText::_($altText);
		if ( $text=='' )  $text .= '&nbsp;'. tsmText::_($altText);
		if ($modal) return '<a '.$class.' class="modal" rel="{handler: \'iframe\', size: {x: 700, y: 550}}" title="'. tsmText::_($altText).'" href="'.JRoute::_($link, FALSE).'">'.$text.'</a>';
		else 		return '<a '.$class.' title="'. tsmText::_($altText).'" href="'.JRoute::_($link, FALSE).'">'.$text.'</a>';
	}

	public function escape($var)
	{
		if (in_array($this->_escape, array('htmlspecialchars', 'htmlentities')))
		{
			$result = call_user_func($this->_escape, $var, ENT_COMPAT, $this->_charset);
		} else {
			$result =  call_user_func($this->_escape, $var);
		}

		return $result;
	}

}