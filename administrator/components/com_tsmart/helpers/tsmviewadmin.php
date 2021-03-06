<?php
/**
 * abstract controller class containing get,store,delete,publish and pagination
 *
 * This class provides the functions for the Views
 * This class provides the functions for the calculations
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2014-2015 tsmart Team. All rights reserved.
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
jimport('joomla.application.component.view');
// Load default helpers
if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
if (!class_exists('AdminUIHelper')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'adminui.php');
if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR . DS . 'includes' . DS . 'toolbar.php');


class tsmViewAdmin extends JViewLegacy
{
    /**
     * Sets automatically the shortcut for the language and the redirect path
     * @author Max Milbers
     */

    var $lists = array();
    var $showVendors = null;
    protected $canDo;
    var $writeJs = true;

    function __construct($config = array())
    {
        parent::__construct($config);
    }

    /*
    * Override the display function to include ACL
    * Redirect to the control panel when user does not have access
    */
    public function display($tpl = null)
    {
        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        $doc = JFactory::getDocument();
        $input = JFactory::getApplication()->input;
        $this->add_new_popup = $input->get('add_new_popup', 0, 'string');
        $this->close_window_children = $input->get('close_window_children', 0, 'int');
        $this->show_in_parent_window = $input->get('show_in_parent_window', 0, 'int');
        $dialog_element_id = $input->get('dialog_element_id', '', 'string');
        $close_ui_dialog_id = $input->get('ui_dialog_id', '', 'string');
        $parent_ui_dialog_id = $input->get('ui_dialog_id', '', 'string');
        $link_reload = $input->get('link_redirect', base64_encode('index.php?option=com_tsmart&view='.$view), 'string');
        $parent_iframe_id = $input->get('iframe_id', '', 'string');
        $reload_iframe_id = $input->get('reload_iframe_id', '', 'string');
        $remove_ui_dialog = $input->get('remove_ui_dialog', false, 'boolean');
        $small_form = $input->get('small_form', 0, 'int');
        if ($this->show_in_parent_window == 1) {
            $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/asianventure_edit_from.js');
            $doc->addScript(JUri::root() . '/media/system/js/base64.js');
            $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_component.less');

            $js_content = '';
            ob_start();
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('.admin.com_tsmart.view-<?php echo $view ?>').asianventure_edit_from({
                        show_in_parent_window:<?php echo $this->show_in_parent_window==1?1:0  ?>,
                        view_height:<?php echo $this->view_height?$this->view_height:0  ?>,
                        close_window_children:<?php echo $this->close_window_children  ?>,
                        dialog_element_id:'<?php echo $dialog_element_id ?>',
                        link_reload:'<?php echo $link_reload ?>',
                        parent_iframe_id:'<?php echo $parent_iframe_id ?>',
                        parent_ui_dialog_id:'<?php echo $parent_ui_dialog_id ?>',
                        close_ui_dialog_id:'<?php echo $close_ui_dialog_id ?>',
                        reload_iframe_id:'<?php echo $reload_iframe_id ?>',
                        remove_ui_dialog:'<?php echo json_encode($remove_ui_dialog) ?>',
                        small_form:<?php echo $small_form ?>
                    });
                });
            </script>
            <?php
            $js_content = ob_get_clean();
            require_once  JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmutility.php';
            $js_content = TSMUtility::remove_string_javascript($js_content);
            $doc->addScriptDeclaration($js_content);


        }
        if (!class_exists('TSMHtmlJquery'))
        {
            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'jquery.php');
        }
        if ($view == 'tsmart' //tsmart view is always allowed since this is the page we redirect to in case the user does not have the rights
            or $view == 'about' //About view always displayed
            or $this->manager($view)
        ) {
            //or $this->canDo->get('core.admin')
            //or $this->canDo->get('vm.'.$view) ) { //Super administrators always have access

            if (JFactory::getApplication()->isSite()) {
                $unoverridable = array('category', 'manufacturer', 'user');    //This views have the same name and must not be overridable
                if (!in_array($view, $unoverridable)) {
                    if (!class_exists('VmTemplate')) require(VMPATH_SITE . DS . 'helpers' . DS . 'vmtemplate.php');
                    $template = VmTemplate::getDefaultTemplate();
                    $this->addTemplatePath(VMPATH_ROOT . DS . 'templates' . DS . $template['template'] . DS . 'html' . DS . 'com_tsmart' . DS . $this->_name);
                }
            }

            $result = $this->loadTemplate($tpl);
            if ($result instanceof Exception) {
                return $result;
            }

            echo $result;

            if ($this->writeJs) {
                vmJsApi::keepAlive();
                echo vmJsApi::writeJS();
            }
            return true;
        } else {
            JFactory::getApplication()->redirect('index.php?option=com_tsmart', tsmText::_('JERROR_ALERTNOAUTHOR'), 'error');
        }

    }

    /*
     * set all commands and options for BE default.php views
    * return $list filter_order and
    */
    function addStandardDefaultViewCommands($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew('show_parent_popup');
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }

    //only view price
    function addStandardDefaultViewCommandsPrice($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew();
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }

    //only view departure
    function addStandardDefaultViewCommandsdeparture($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew();
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }
    function addStandardDefaultViewCommandsdateavailability($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew();
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }
    function addStandardDefaultViewCommandspromotion($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew();
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }

    //only view Departure

    function addStandardDefaultViewCommandsEditInline($showNew = true,$show_edit=true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if ($show_edit&&vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }

        if ($showNew&&vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew('edit_in_line');
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }

    function addStandardDefaultViewCommandsPopup($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew('show_parent_popup');
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        JToolBarHelper::divider();
        JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }

        $this->addJsJoomlaSubmitButton();
        // javascript for cookies setting in case of press "APPLY"
    }

    function addStandardDefaultViewCommandsNoValidate($showNew = true, $showDelete = true, $showHelp = true)
    {


        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit.state')) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
        }
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::editList();
        }
        if (vmAccess::manager($view . '.create')) {
            JToolBarHelper::addNew();
        }
        if (vmAccess::manager($view . '.delete')) {
            JToolBarHelper::spacer('10');
            JToolBarHelper::deleteList();
        }
        ///JToolBarHelper::divider();
        //JToolBarHelper::spacer('2');
        //self::showACLPref($view);
        //self::showHelp ( $showHelp);
        /*if(JFactory::getApplication()->isSite()){
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }*/

        $this->addJsJoomlaSubmitButtonNoValidate();
        // javascript for cookies setting in case of press "APPLY"
    }

    function addJsJoomlaSubmitButton($validate = false)
    {

        static $done = array(false, false);
        if (!$done[$validate]) {
            if ($validate) {
                vmJsApi::vmValidator();
                $form = "if( (a=='apply' || a=='save') && myValidator(form,false)){
				form.submit();
			} else if(a!='apply' && a!='save'){
				form.submit();
			}";
            } else {
                $form = "form.submit();";
            }

        }

        $j = "
	Joomla.submitbutton=function(a){

		var options = { path: '/', expires: 2}
		if (a == 'apply') {
			var idx = jQuery('#tabs li.current').index();
			jQuery.cookie('vmapply', idx, options);
		} else {
			jQuery.cookie('vmapply', '0', options);
		}
		jQuery( '#media-dialog' ).remove();
		form = document.getElementById('adminForm');
		form.task.value = a;
		" . $form . "
		return false;
	};

		links = jQuery('a[onclick].toolbar');

		links.each(function(){
			var onClick = new String(this.onclick);
			jQuery(this).click(function(e){
				//console.log('click ');
				e.stopImmediatePropagation();
				e.preventDefault();
			});
		});";
        vmJsApi::addJScript('submit', $j, false, true);
        $done[$validate] = true;
    }

    function addJsJoomlaSubmitButtonNoValidate($validate = false)
    {
        return;
        static $done = array(false, false);
        if (!$done[$validate]) {
            if ($validate) {
                vmJsApi::vmValidator();
                $form = "if( (a=='apply' || a=='save')){
				form.submit();
			} else if(a!='apply' && a!='save'){
				form.submit();
			}";
            } else {
                $form = "form.submit();";
            }

        }

        $j = "
	Joomla.submitbutton=function(a){

		var options = { path: '/', expires: 2}
		if (a == 'apply') {
			var idx = jQuery('#tabs li.current').index();
			jQuery.cookie('vmapply', idx, options);
		} else {
			jQuery.cookie('vmapply', '0', options);
		}
		jQuery( '#media-dialog' ).remove();
		form = document.getElementById('adminForm');
		form.task.value = a;
		" . $form . "
		return false;
	};

		links = jQuery('a[onclick].toolbar');

		links.each(function(){
			var onClick = new String(this.onclick);
			jQuery(this).click(function(e){
				//console.log('click ');
				e.stopImmediatePropagation();
				e.preventDefault();
			});
		});";
        vmJsApi::addJScript('submit', $j, false, true);
        $done[$validate] = true;
    }

    /**
     * set pagination and filters
     * return Array() $list( filter_order and dir )
     */

    function addStandardDefaultViewLists($model, $default_order = 0, $default_dir = 'DESC', $name = 'search')
    {

        // set list filters
        $option = vRequest::getCmd('option');
        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        $app = JFactory::getApplication();
        $this->lists[$name] = $app->getUserStateFromRequest($option . '.' . $view . '.' . $name, $name, '', 'string');

        $this->lists['filter_order'] = $this->getValidFilterOrder($app, $model, $view, $default_order);

        $toTest = $app->getUserStateFromRequest('com_tsmart.' . $view . '.filter_order_Dir', 'filter_order_Dir', $default_dir, 'cmd');

        $this->lists['filter_order_Dir'] = $model->checkFilterDir($toTest);

    }

    function getValidFilterOrder($app, $model, $view, $default_order)
    {

        if ($default_order === 0) {
            $default_order = $model->getDefaultOrdering();
        }
        $toTest = $app->getUserStateFromRequest('com_tsmart.' . $view . '.filter_order', 'filter_order', $default_order, 'cmd');

        return $model->checkFilterOrder($toTest);
    }


    /**
     * Add simple search to form
     * @param $searchLabel text to display before searchbox
     * @param $name         lists and id name
     * ??vmText::_('com_tsmart_NAME')
     */

    function displayDefaultViewSearch($searchLabel = 'com_tsmart_NAME', $name = 'search')
    {
        return tsmText::_('com_tsmart_FILTER') . ' ' . tsmText::_($searchLabel) . ':
		<input type="text" name="' . $name . '" id="' . $name . '" value="' . $this->lists[$name] . '" class="text_area" />
		<button class="btn btn-small" onclick="this.form.submit();">' . tsmText::_('com_tsmart_GO') . '</button>
		<button class="btn btn-small" onclick="document.getElementById(\'' . $name . '\').value=\'\';this.form.submit();">' . tsmText::_('com_tsmart_RESET') . '</button>';
    }

    function addStandardEditViewCommands($id = 0, $object = null)
    {

        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR . DS . 'includes' . DS . 'toolbar.php');

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::save();
            JToolBarHelper::apply();
        }
        JToolBarHelper::cancel();
        //self::showHelp();
        //self::showACLPref($view);

        if ($view != 'shipmentmethod' and $view != 'paymentmethod' and $view != 'media') $validate = true; else $validate = false;
        $this->addJsJoomlaSubmitButton($validate);

        $editView = vRequest::getCmd('view', vRequest::getCmd('controller', ''));
        $params = JComponentHelper::getParams('com_languages');

        $selectedLangue = $params->get('site', 'en-GB');
        $this->lang = strtolower(strtr($selectedLangue, '-', '_'));

        // Get all the published languages defined in Language manager > Content
        $allLanguages = JLanguageHelper::getLanguages();
        foreach ($allLanguages as $jlang) {
            $languagesByCode[$jlang->lang_code] = $jlang;
        }

        // only add if ID and view not null
        if ($editView and $id and (count(tsmConfig::get('active_languages')) > 1)) {

            if ($editView == 'user') $editView = 'vendor';

            jimport('joomla.language.helper');
            $this->lang = vRequest::getVar('vmlang', $this->lang);
            // list of languages installed in #__extensions (may be more than the ones in the Language manager > Content if the user did not added them)
            $languages = JLanguageHelper::createLanguageList($selectedLangue, constant('VMPATH_ROOT'), true);
            $activeVmLangs = (tsmConfig::get('active_languages'));
            $flagCss = "";
            foreach ($languages as $k => &$joomlaLang) {
                if (!in_array($joomlaLang['value'], $activeVmLangs)) {
                    unset($languages[$k]);
                } else {

                    $key = $joomlaLang['value'];
                    if (!isset($languagesByCode[$key])) {
                        $img = substr($key, 0, 2);//We try a fallback
                        vmdebug('com_tsmart_MISSING_FLAG', $img, $joomlaLang['text']);
                    } else {
                        $img = $languagesByCode[$key]->image;
                    }
                    $image_flag = VMPATH_ROOT . "/media/mod_languages/images/" . $img . ".gif";
                    $image_flag_url = JURI::root() . "media/mod_languages/images/" . $img . ".gif";

                    if (!file_exists($image_flag)) {
                        vmerror(tsmText::sprintf('com_tsmart_MISSING_FLAG', $image_flag, $joomlaLang['text']));
                    } else {
                        $flagCss .= "td.flag-" . $key . ",.flag-" . $key . "{background: url( " . $image_flag_url . ") no-repeat 0 0; padding-left:20px !important;}\n";
                    }
                }
            }
            JFactory::getDocument()->addStyleDeclaration($flagCss);

            $this->langList = JHtml::_('select.genericlist', $languages, 'vmlang', 'class="inputbox" style="width:176px;"', 'value', 'text', $selectedLangue, 'vmlang');


            if ($editView == 'product') {
                $productModel = tmsModel::getModel('product');
                $childproducts = $productModel->getProductChilds($id) ? $productModel->getProductChilds($id) : '';
            }

            $token = vRequest::getFormToken();
            $j = '
			jQuery(function($) {
				var oldflag = "";
				$("select#vmlang").chosen().change(function() {
					langCode = $(this).find("option:selected").val();
					flagClass = "flag-"+langCode;
					jQuery.ajax({
						type: "GET",
						cache: false,
        				dataType: "json",
        				url: "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $id . '&editView=' . $editView . '&' . $token . '=1",
    				}).done(
						function(data) {
							var items = [];

							var theForm = document.forms["adminForm"];
							if(typeof theForm.vmlang==="undefined"){
							 	var input = document.createElement("input");
								input.type = "hidden";
								input.name = "vmlang";
								input.value = langCode;
								theForm.appendChild(input);
							} else {
								theForm.vmlang.value = langCode;
							}
							if (data.fields !== "error" ) {
								if (data.structure == "empty") alert(data.msg);
								$.each(data.fields , function(key, val) {
									cible = jQuery("#"+key);
									if (oldflag !== "") cible.parent().removeClass(oldflag)
									var tmce_ver = 0;
									if(typeof window.tinyMCE!=="undefined"){
										var tmce_ver=window.tinyMCE.majorVersion;
									}
									if (tmce_ver>="4") {
										if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
											tinyMCE.get(key).execCommand("mceSetContent", false,val);
											cible.val(val);
										} else if (data.structure !== "empty") cible.val(val);
									} else {
										if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
											tinyMCE.execInstanceCommand(key,"mceSetContent",false,val);
											cible.val(val);
										} else if (data.structure !== "empty") cible.val(val);
									}
									});

							} else alert(data.msg);';

            if ($editView == 'product' && !empty($childproducts)) {
                foreach ($childproducts as $child) {
                    $j .= 'jQuery.ajax({
        						type: "GET",
								cache: false,
        						dataType: "json",
        						url: "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $child->tsmart_product_id . '&editView=' . $editView . '&' . $token . '=1",
    					}).done(
								//	$.getJSON( "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $child->tsmart_product_id . '&editView=' . $editView . '&' . $token . '=1" ,
										function(data) {
											cible = jQuery("#child' . $child->tsmart_product_id . 'product_name");
											if (oldflag !== "") cible.parent().removeClass(oldflag)
											cible.parent().addClass(flagClass);
											cible.val(data.fields.product_name);
											jQuery("#child' . $child->tsmart_product_id . 'slug").val(data.fields["slug"]);
										}
									)
								';
                }
            }

            $j .= 'oldflag = flagClass ;
						}
					)
				});
			})';
            vmJsApi::addJScript('vmlang', $j);
        } else {
            $jlang = JFactory::getLanguage();
            $langs = $jlang->getKnownLanguages();
            $defautName = $selectedLangue;
            $flagImg = $selectedLangue;
            if (isset($languagesByCode[$selectedLangue])) {
                $defautName = $langs[$selectedLangue]['name'];
                $flagImg = JHtml::_('image', 'mod_languages/' . $languagesByCode[$selectedLangue]->image . '.gif', $languagesByCode[$selectedLangue]->title_native, array('title' => $languagesByCode[$selectedLangue]->title_native), true);
            } else {
                vmWarn(tsmText::sprintf('com_tsmart_MISSING_FLAG', $selectedLangue, $selectedLangue));
            }
            $this->langList = '<input name ="vmlang" type="hidden" value="' . $selectedLangue . '" >' . $flagImg . ' <b> ' . $defautName . '</b>';
        }

        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }
    }

    function addStandardEditViewCommandsPopup($id = 0, $object = null)
    {

        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR . DS . 'includes' . DS . 'toolbar.php');

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::save();
            JToolBarHelper::apply();
        }
        //JToolBarHelper::cancel();
        //self::showHelp();
        //self::showACLPref($view);

        if ($view != 'shipmentmethod' and $view != 'paymentmethod' and $view != 'media') $validate = true; else $validate = false;
        $this->addJsJoomlaSubmitButton($validate);

        $editView = vRequest::getCmd('view', vRequest::getCmd('controller', ''));
        $params = JComponentHelper::getParams('com_languages');

        $selectedLangue = $params->get('site', 'en-GB');
        $this->lang = strtolower(strtr($selectedLangue, '-', '_'));

        // Get all the published languages defined in Language manager > Content
        $allLanguages = JLanguageHelper::getLanguages();
        foreach ($allLanguages as $jlang) {
            $languagesByCode[$jlang->lang_code] = $jlang;
        }

        // only add if ID and view not null
        if ($editView and $id and (count(tsmConfig::get('active_languages')) > 1)) {

            if ($editView == 'user') $editView = 'vendor';

            jimport('joomla.language.helper');
            $this->lang = vRequest::getVar('vmlang', $this->lang);
            // list of languages installed in #__extensions (may be more than the ones in the Language manager > Content if the user did not added them)
            $languages = JLanguageHelper::createLanguageList($selectedLangue, constant('VMPATH_ROOT'), true);
            $activeVmLangs = (tsmConfig::get('active_languages'));
            $flagCss = "";
            foreach ($languages as $k => &$joomlaLang) {
                if (!in_array($joomlaLang['value'], $activeVmLangs)) {
                    unset($languages[$k]);
                } else {

                    $key = $joomlaLang['value'];
                    if (!isset($languagesByCode[$key])) {
                        $img = substr($key, 0, 2);//We try a fallback
                        vmdebug('com_tsmart_MISSING_FLAG', $img, $joomlaLang['text']);
                    } else {
                        $img = $languagesByCode[$key]->image;
                    }
                    $image_flag = VMPATH_ROOT . "/media/mod_languages/images/" . $img . ".gif";
                    $image_flag_url = JURI::root() . "media/mod_languages/images/" . $img . ".gif";

                    if (!file_exists($image_flag)) {
                        vmerror(tsmText::sprintf('com_tsmart_MISSING_FLAG', $image_flag, $joomlaLang['text']));
                    } else {
                        $flagCss .= "td.flag-" . $key . ",.flag-" . $key . "{background: url( " . $image_flag_url . ") no-repeat 0 0; padding-left:20px !important;}\n";
                    }
                }
            }
            JFactory::getDocument()->addStyleDeclaration($flagCss);

            $this->langList = JHtml::_('select.genericlist', $languages, 'vmlang', 'class="inputbox" style="width:176px;"', 'value', 'text', $selectedLangue, 'vmlang');


            if ($editView == 'product') {
                $productModel = tmsModel::getModel('product');
                $childproducts = $productModel->getProductChilds($id) ? $productModel->getProductChilds($id) : '';
            }

            $token = vRequest::getFormToken();
            $j = '
			jQuery(function($) {
				var oldflag = "";
				$("select#vmlang").chosen().change(function() {
					langCode = $(this).find("option:selected").val();
					flagClass = "flag-"+langCode;
					jQuery.ajax({
						type: "GET",
						cache: false,
        				dataType: "json",
        				url: "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $id . '&editView=' . $editView . '&' . $token . '=1",
    				}).done(
						function(data) {
							var items = [];

							var theForm = document.forms["adminForm"];
							if(typeof theForm.vmlang==="undefined"){
							 	var input = document.createElement("input");
								input.type = "hidden";
								input.name = "vmlang";
								input.value = langCode;
								theForm.appendChild(input);
							} else {
								theForm.vmlang.value = langCode;
							}
							if (data.fields !== "error" ) {
								if (data.structure == "empty") alert(data.msg);
								$.each(data.fields , function(key, val) {
									cible = jQuery("#"+key);
									if (oldflag !== "") cible.parent().removeClass(oldflag)
									var tmce_ver = 0;
									if(typeof window.tinyMCE!=="undefined"){
										var tmce_ver=window.tinyMCE.majorVersion;
									}
									if (tmce_ver>="4") {
										if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
											tinyMCE.get(key).execCommand("mceSetContent", false,val);
											cible.val(val);
										} else if (data.structure !== "empty") cible.val(val);
									} else {
										if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
											tinyMCE.execInstanceCommand(key,"mceSetContent",false,val);
											cible.val(val);
										} else if (data.structure !== "empty") cible.val(val);
									}
									});

							} else alert(data.msg);';

            if ($editView == 'product' && !empty($childproducts)) {
                foreach ($childproducts as $child) {
                    $j .= 'jQuery.ajax({
        						type: "GET",
								cache: false,
        						dataType: "json",
        						url: "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $child->tsmart_product_id . '&editView=' . $editView . '&' . $token . '=1",
    					}).done(
								//	$.getJSON( "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $child->tsmart_product_id . '&editView=' . $editView . '&' . $token . '=1" ,
										function(data) {
											cible = jQuery("#child' . $child->tsmart_product_id . 'product_name");
											if (oldflag !== "") cible.parent().removeClass(oldflag)
											cible.parent().addClass(flagClass);
											cible.val(data.fields.product_name);
											jQuery("#child' . $child->tsmart_product_id . 'slug").val(data.fields["slug"]);
										}
									)
								';
                }
            }

            $j .= 'oldflag = flagClass ;
						}
					)
				});
			})';
            vmJsApi::addJScript('vmlang', $j);
        } else {
            $jlang = JFactory::getLanguage();
            $langs = $jlang->getKnownLanguages();
            $defautName = $selectedLangue;
            $flagImg = $selectedLangue;
            if (isset($languagesByCode[$selectedLangue])) {
                $defautName = $langs[$selectedLangue]['name'];
                $flagImg = JHtml::_('image', 'mod_languages/' . $languagesByCode[$selectedLangue]->image . '.gif', $languagesByCode[$selectedLangue]->title_native, array('title' => $languagesByCode[$selectedLangue]->title_native), true);
            } else {
                vmWarn(tsmText::sprintf('com_tsmart_MISSING_FLAG', $selectedLangue, $selectedLangue));
            }
            $this->langList = '<input name ="vmlang" type="hidden" value="' . $selectedLangue . '" >' . $flagImg . ' <b> ' . $defautName . '</b>';
        }

        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }
    }

    function addStandardEditViewCommandsNoValidate($id = 0, $object = null)
    {

        $view = vRequest::getCmd('view', vRequest::getCmd('controller', 'tsmart'));

        if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR . DS . 'includes' . DS . 'toolbar.php');

        JToolBarHelper::divider();
        if (vmAccess::manager($view . '.edit')) {
            JToolBarHelper::save();
            JToolBarHelper::apply();
        }
        JToolBarHelper::cancel();
        //self::showHelp();
        //self::showACLPref($view);

        if ($view != 'shipmentmethod' and $view != 'paymentmethod' and $view != 'media') $validate = true; else $validate = false;
        $this->addJsJoomlaSubmitButtonNoValidate($validate);

        $editView = vRequest::getCmd('view', vRequest::getCmd('controller', ''));
        $params = JComponentHelper::getParams('com_languages');

        $selectedLangue = $params->get('site', 'en-GB');
        $this->lang = strtolower(strtr($selectedLangue, '-', '_'));

        // Get all the published languages defined in Language manager > Content
        $allLanguages = JLanguageHelper::getLanguages();
        foreach ($allLanguages as $jlang) {
            $languagesByCode[$jlang->lang_code] = $jlang;
        }

        // only add if ID and view not null
        if ($editView and $id and (count(tsmConfig::get('active_languages')) > 1)) {

            if ($editView == 'user') $editView = 'vendor';

            jimport('joomla.language.helper');
            $this->lang = vRequest::getVar('vmlang', $this->lang);
            // list of languages installed in #__extensions (may be more than the ones in the Language manager > Content if the user did not added them)
            $languages = JLanguageHelper::createLanguageList($selectedLangue, constant('VMPATH_ROOT'), true);
            $activeVmLangs = (tsmConfig::get('active_languages'));
            $flagCss = "";
            foreach ($languages as $k => &$joomlaLang) {
                if (!in_array($joomlaLang['value'], $activeVmLangs)) {
                    unset($languages[$k]);
                } else {

                    $key = $joomlaLang['value'];
                    if (!isset($languagesByCode[$key])) {
                        $img = substr($key, 0, 2);//We try a fallback
                        vmdebug('com_tsmart_MISSING_FLAG', $img, $joomlaLang['text']);
                    } else {
                        $img = $languagesByCode[$key]->image;
                    }
                    $image_flag = VMPATH_ROOT . "/media/mod_languages/images/" . $img . ".gif";
                    $image_flag_url = JURI::root() . "media/mod_languages/images/" . $img . ".gif";

                    if (!file_exists($image_flag)) {
                        vmerror(tsmText::sprintf('com_tsmart_MISSING_FLAG', $image_flag, $joomlaLang['text']));
                    } else {
                        $flagCss .= "td.flag-" . $key . ",.flag-" . $key . "{background: url( " . $image_flag_url . ") no-repeat 0 0; padding-left:20px !important;}\n";
                    }
                }
            }
            JFactory::getDocument()->addStyleDeclaration($flagCss);

            $this->langList = JHtml::_('select.genericlist', $languages, 'vmlang', 'class="inputbox" style="width:176px;"', 'value', 'text', $selectedLangue, 'vmlang');


            if ($editView == 'product') {
                $productModel = tmsModel::getModel('product');
                $childproducts = $productModel->getProductChilds($id) ? $productModel->getProductChilds($id) : '';
            }

            $token = vRequest::getFormToken();
            $j = '
			jQuery(function($) {
				var oldflag = "";
				$("select#vmlang").chosen().change(function() {
					langCode = $(this).find("option:selected").val();
					flagClass = "flag-"+langCode;
					jQuery.ajax({
						type: "GET",
						cache: false,
        				dataType: "json",
        				url: "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $id . '&editView=' . $editView . '&' . $token . '=1",
    				}).done(
						function(data) {
							var items = [];

							var theForm = document.forms["adminForm"];
							if(typeof theForm.vmlang==="undefined"){
							 	var input = document.createElement("input");
								input.type = "hidden";
								input.name = "vmlang";
								input.value = langCode;
								theForm.appendChild(input);
							} else {
								theForm.vmlang.value = langCode;
							}
							if (data.fields !== "error" ) {
								if (data.structure == "empty") alert(data.msg);
								$.each(data.fields , function(key, val) {
									cible = jQuery("#"+key);
									if (oldflag !== "") cible.parent().removeClass(oldflag)
									var tmce_ver = 0;
									if(typeof window.tinyMCE!=="undefined"){
										var tmce_ver=window.tinyMCE.majorVersion;
									}
									if (tmce_ver>="4") {
										if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
											tinyMCE.get(key).execCommand("mceSetContent", false,val);
											cible.val(val);
										} else if (data.structure !== "empty") cible.val(val);
									} else {
										if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
											tinyMCE.execInstanceCommand(key,"mceSetContent",false,val);
											cible.val(val);
										} else if (data.structure !== "empty") cible.val(val);
									}
									});

							} else alert(data.msg);';

            if ($editView == 'product' && !empty($childproducts)) {
                foreach ($childproducts as $child) {
                    $j .= 'jQuery.ajax({
        						type: "GET",
								cache: false,
        						dataType: "json",
        						url: "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $child->tsmart_product_id . '&editView=' . $editView . '&' . $token . '=1",
    					}).done(
								//	$.getJSON( "index.php?option=com_tsmart&view=translate&task=paste&format=json&lg="+langCode+"&id=' . $child->tsmart_product_id . '&editView=' . $editView . '&' . $token . '=1" ,
										function(data) {
											cible = jQuery("#child' . $child->tsmart_product_id . 'product_name");
											if (oldflag !== "") cible.parent().removeClass(oldflag)
											cible.parent().addClass(flagClass);
											cible.val(data.fields.product_name);
											jQuery("#child' . $child->tsmart_product_id . 'slug").val(data.fields["slug"]);
										}
									)
								';
                }
            }

            $j .= 'oldflag = flagClass ;
						}
					)
				});
			})';
            vmJsApi::addJScript('vmlang', $j);
        } else {
            $jlang = JFactory::getLanguage();
            $langs = $jlang->getKnownLanguages();
            $defautName = $selectedLangue;
            $flagImg = $selectedLangue;
            if (isset($languagesByCode[$selectedLangue])) {
                $defautName = $langs[$selectedLangue]['name'];
                $flagImg = JHtml::_('image', 'mod_languages/' . $languagesByCode[$selectedLangue]->image . '.gif', $languagesByCode[$selectedLangue]->title_native, array('title' => $languagesByCode[$selectedLangue]->title_native), true);
            } else {
                vmWarn(tsmText::sprintf('com_tsmart_MISSING_FLAG', $selectedLangue, $selectedLangue));
            }
            $this->langList = '<input name ="vmlang" type="hidden" value="' . $selectedLangue . '" >' . $flagImg . ' <b> ' . $defautName . '</b>';
        }

        if (JFactory::getApplication()->isSite()) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0');
        }
    }

    function SetViewTitle($name = '', $msg = '', $icon = '')
    {

        $view = vRequest::getCmd('view', vRequest::getCmd('controller'));
        if ($name == '')
            $name = strtoupper($view);
        if ($icon == '')
            $icon = strtolower($view);
        if (!$task = vRequest::getCmd('task'))
            $task = 'list';

        if (!empty($msg)) {
            $msg = ' <span style="color: #666666; font-size: large;">' . $msg . '</span>';
        }

        $viewText = tsmText::_('com_tsmart_' . strtoupper($name));

        $taskName = ' <small><small>[ ' . tsmText::_('com_tsmart_' . $task) . ' ]</small></small>';

        JToolBarHelper::title($viewText . ' ' . $taskName . $msg, 'head vm_' . $icon . '_48');
        $this->assignRef('viewName', $viewText); //was $viewName?
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $doc->setTitle($app->getCfg('sitename') . ' - ' . tsmText::_('JADMINISTRATION') . ' - ' . strip_tags($msg));
    }

    function sort($orderby, $name = null)
    {
        if (!$name) $name = 'com_tsmart_' . strtoupper($orderby);
        return JHtml::_('grid.sort', tsmText::_($name), $orderby, $this->lists['filter_order_Dir'], $this->lists['filter_order']);
    }

    public function addStandardHiddenToForm($controller = null, $task = '')
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $key=$input->get('key',array(),'array');
        $ui_dialog_id=$input->get('ui_dialog_id','','string');
        $layout=$input->get('layout','','string');
        $iframe_id=$input->get('iframe_id','','string');
        $link_reload=$input->get('link_reload','','string');
        $reload_ui_dialog_id=$input->get('reload_ui_dialog_id','','string');
        $reload_iframe_id=$input->get('reload_iframe_id','','string');
        $remove_ui_dialog=$input->get('remove_ui_dialog','','string');
        $small_form=$input->get('small_form',0,'int');
        if (!$controller) $controller = vRequest::getCmd('view');
        $option = vRequest::getCmd('option', 'com_tsmart');
        $show_in_parent_window = vRequest::getInt('show_in_parent_window', 0);
        ob_start();
        foreach($key as $key=>$value)
        {
            ?>
            <input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>" />
            <?php
        }
        $hidden = '';
        if (array_key_exists('filter_order', $this->lists)) {
            $hidden = '
			<input type="hidden" name="filter_order" value="' . $this->lists['filter_order'] . '" />
			<input type="hidden" name="filter_order_Dir" value="' . $this->lists['filter_order_Dir'] . '" />';
        }

        if (vRequest::get('manage', false) or JFactory::getApplication()->isSite()) {
            $hidden .= '<input type="hidden" name="manage" value="1" />';
        }

         $hidden .='
		<input type="hidden" name="task" value="' . $task . '" />
		<input type="hidden" name="show_in_parent_window" value="' . $show_in_parent_window . '" />
		<input type="hidden" name="key[ui_dialog_id]" value="' . $ui_dialog_id . '" />
		<input type="hidden" name="key[iframe_id]" value="' . $iframe_id . '" />
		<input type="hidden" name="key[link_reload]" value="' . $link_reload . '" />
		<input type="hidden" name="key[reload_ui_dialog_id]" value="' . $reload_ui_dialog_id . '" />
		<input type="hidden" name="key[reload_iframe_id]" value="' . $reload_iframe_id . '" />
		<input type="hidden" name="key[remove_ui_dialog]" value="' . $remove_ui_dialog . '" />
		<input type="hidden" name="key[small_form]" value="' . $small_form . '" />
		<input type="hidden" name="option" value="' . $option . '" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="' . $controller . '" />
		<input type="hidden" name="view" value="' . $controller . '" />
		<input type="hidden" name="layout" value="' . $layout . '" />

		' . JHtml::_('form.token');
        echo $hidden;
        return ob_get_clean();
    }

    public function addStandardHiddenToForm1($controller = null, $task = '')
    {
        if (!$controller) $controller = vRequest::getCmd('view');
        $option = vRequest::getCmd('option', 'com_tsmart');
        $hidden = '';
        if (array_key_exists('filter_order', $this->lists)) {
            $hidden = '
			<input type="hidden" name="filter_order" value="' . $this->lists['filter_order'] . '" />
			<input type="hidden" name="filter_order_Dir" value="' . $this->lists['filter_order_Dir'] . '" />';
        }

        if (vRequest::get('manage', false) or JFactory::getApplication()->isSite()) {
            $hidden .= '<input type="hidden" name="manage" value="1" />';
        }
        return $hidden . '
		<input type="hidden" name="task" value="' . $task . '" />
		<input type="hidden" name="option" value="' . $option . '" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="' . $controller . '" />
		<input type="hidden" name="view" value="' . $controller . '" />
		' . JHtml::_('form.token');
    }


    /**
     * Additional grid function for custom toggles
     *
     * @return string HTML code to write the toggle button
     */
    function toggle($field, $i, $toggle, $imgY = 'tick.png', $imgX = 'publish_x.png', $untoggleable = false)
    {

        $img = $field ? $imgY : $imgX;
        if ($toggle == 'published') {
            // Stay compatible with grid.published
            $task = $field ? 'unpublish' : 'publish';
            $alt = $field ? tsmText::_('com_tsmart_PUBLISHED') : tsmText::_('com_tsmart_UNPUBLISHED');
            $action = $field ? tsmText::_('com_tsmart_UNPUBLISH_ITEM') : tsmText::_('com_tsmart_PUBLISH_ITEM');
        } else {
            $task = $field ? $toggle . '.0' : $toggle . '.1';
            $alt = $field ? tsmText::_('com_tsmart_PUBLISHED') : tsmText::_('com_tsmart_DISABLED');
            $action = $field ? tsmText::_('com_tsmart_DISABLE_ITEM') : tsmText::_('com_tsmart_ENABLE_ITEM');
        }

        $img = 'admin/' . $img;

        if ($untoggleable) {
            $attribs = 'style="opacity: 0.6;"';
        } else {
            $attribs = '';
        }
        $image = JHtml::_('image', $img, $alt, $attribs, true);

        if ($untoggleable) return $image;

        if (JVM_VERSION < 3) {
            return ('<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $action . '">'
                . $image . '</a>');
        } else {
            $icon = $field ? 'publish' : 'unpublish';
            return ('<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $action . '">'
                . '<span class="icon-' . $icon . '"><span>' . '</a>');
        }


    }

    function gridPublished($name, $i)
    {
        if (JVM_VERSION < 3) {
            $published = JHtml::_('grid.published', $name, $i);
        } else {
            $published = JHtml::_('jgrid.published', $name->published, $i);
        }
        return $published;
    }

    function gridEdit($value, $i, $key, $link)
    {
        return '<a href="' . $link . '" class="edit" data-id="' . $value->$key . '"  title="edit">'
        . JHtml::_('image', 'admin/edit.png', 'edit', null, true) . '</a>';
    }

    function grid_save_in_line($name, $i, $key)
    {
        $save_in_line = JHtml::_('jgrid.save_in_line', $name->$key, $i);
        return $save_in_line;
    }

    function grid_delete_in_line($name, $i, $key)
    {
        $save_in_line = JHtml::_('jgrid.delete_in_line', $name->$key, $i);
        return $save_in_line;
    }

    function grid_cancel_in_line($name, $i, $key)
    {
        $save_in_line = JHtml::_('jgrid.cancel_in_line', $name->$key, $i);
        return $save_in_line;
    }

    function showhelp()
    {
        /* http://docs.joomla.org/Help_system/Adding_a_help_button_to_the_toolbar */
        $task = vRequest::getCmd('task', '');
        $view = vRequest::getCmd('view', '');
        if ($task) {
            if ($task == "add") {
                $task = "edit";
            }
            $task = "_" . $task;
        }
        if (!class_exists('tsmConfig')) require(VMPATH_ADMIN . '/helpers/config.php');
        tsmConfig::loadConfig();
        tsmConfig::loadJLang('com_tsmart_help');
        $lang = JFactory::getLanguage();
        $key = 'com_tsmart_HELP_' . $view . $task;
        if ($lang->hasKey($key)) {
            $help_url = tsmText::_($key) . "?tmpl=component";
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Popup', 'help', 'JTOOLBAR_HELP', $help_url, 960, 500);
        }

    }

    function showACLPref()
    {

        if (vmAccess::manager('core')) {
            JToolBarHelper::divider();
            $bar = JToolBar::getInstance('toolbar');
            if (JVM_VERSION < 3) {
                $bar->appendButton('Popup', 'lock', 'JCONFIG_PERMISSIONS_LABEL', 'index.php?option=com_config&amp;view=component&amp;component=com_tsmart&amp;tmpl=component', 875, 550, 0, 0, '');
            } else {
                $bar->appendButton('Link', 'lock', 'JCONFIG_PERMISSIONS_LABEL', 'index.php?option=com_config&amp;view=component&amp;component=com_tsmart');
            }

        }

    }

    /**
     * Checks if we show multivendor related stuff for admins
     * @return bool|null
     */
    public function showVendors()
    {

        if ($this->showVendors === null) {
            if (tsmConfig::get('multix', 'none') != 'none' and vmAccess::manager('managevendors')) {
                $this->showVendors = true;
            } else {
                $this->showVendors = false;
            }
        }
        return $this->showVendors;
    }

    public function manager($view = 0)
    {
        if (empty($view)) $view = $this->_name;
        return vmAccess::manager($view);
    }

    /**
     * Get the filter form
     *
     * @param   array $data data
     * @param   boolean $loadData load current data
     *
     * @return  JForm/false  the JForm object or false
     *
     * @since   3.2
     */
    public function getFilterForm($data = array(), $loadData = true)
    {
        $form = null;

        // Try to locate the filter form automatically. Example: ContentModelArticles => "filter_articles"
        if (empty($this->filterFormName)) {
            $classNameParts = explode('Model', get_called_class());

            if (count($classNameParts) == 2) {
                $this->filterFormName = 'filter_' . strtolower($classNameParts[1]);
            }
        }

        if (!empty($this->filterFormName)) {
            // Get the form.
            $form = $this->loadForm($this->context . '.filter', $this->filterFormName, array('control' => '', 'load_data' => $loadData));
        }

        return $form;
    }


}