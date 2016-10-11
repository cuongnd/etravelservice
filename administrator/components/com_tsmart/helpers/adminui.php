<?php
/**
 * Administrator menu helper class
 *
 * This class was derived from the show_image_in_imgtag.php and imageTools.class.php files in VM.  It provides some
 * image functions that are used throughout the tsmart shop.
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author Eugen Stranz
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009 tsmart Team. All rights reserved.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die ();

class AdminUIHelper
{

    public static $vmAdminAreaStarted = false;
    public static $backEnd = true;

    /**
     * Start the administrator area table
     *
     * The entire administrator area with contained in a table which include the admin ribbon menu
     * in the left column and the content in the right column.  This function sets up the table and
     * displays the admin menu in the left column.
     */
static function startAdminArea($vmView, $selectText = 'com_tsmart_DRDOWN_AVA2ALL')
{
    JHtml::_('jquery.framework');
    $doc = JFactory::getDocument();
//JHtml::_('behavior.formvalidator');
    JHtml::_('formbehavior.chosen');
    JHTML::_('behavior.core');
    JHtml::_('jquery.ui');
    $doc = JFactory::getDocument();
    $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
    $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
    $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
    $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');
    $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
    $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/autocomplete.js');
    $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
    $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
    $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
    $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
    $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
    $doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/base.css');
    $doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/clean.css');

    if (vRequest::getCmd('format') == 'pdf') return;
    if (vRequest::getCmd('manage', false)) self::$backEnd = false;

    if (self::$vmAdminAreaStarted) return;
    self::$vmAdminAreaStarted = true;
    $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/admin_ui.less');
    $doc->addStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/css/icons.css');
    $doc->addScript(JUri::root() . '/media/system/js/purl-master/purl-master/purl.js');
    $doc->addScript(JUri::root() . '/media/system/js/URI.js-gh-pages/src/URI.js');
    $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/asianventure.js');
    $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/asianventure.less');
    $admin = 'administrator/components/com_tsmart/assets/css';
    $modalJs = '';
    //loading defaut admin CSS
    vmJsApi::css('admin_ui', $admin);
    vmJsApi::css('admin.styles', $admin);
    vmJsApi::css('toolbar_images', $admin);
    vmJsApi::css('menu_images', $admin);
    vmJsApi::css('vtip');

    $view = vRequest::getCmd('view', 'tsmart');

    if ($view != 'tsmart') {
        vmJsApi::css('chosen');
        vmJsApi::css('jquery.fancybox-1.3.4');
        //vmJsApi::css('ui/jquery.ui.all');
    }

    if ($view != 'tsmart') {
        vmJsApi::addJScript('fancybox/jquery.mousewheel-3.0.4.pack', false, false);
        vmJsApi::addJScript('fancybox/jquery.easing-1.3.pack', false, false);
        vmJsApi::addJScript('fancybox/jquery.fancybox-1.3.4.pack', false, false);
        VmJsApi::chosenDropDowns();
    }
    $app = JFactory::getApplication();
    $input = $app->input;
    $cid = $input->get('cid', array(), 'array');
    $key = $input->get('key', array(), 'array');
    $list_key = array();
    foreach ($key as $a_key => $item) {
        $list_key = "$a_key=$item";
    }
    $str_key = '';
    if ($list_key != '') {
        $str_key = '&' . $list_key;
    }
    $key_string = '';
    $show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
    $hide_toolbar = $input->get('hide_toolbar', 0, 'int');
    if($show_edit_in_line)
    {
        $doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/asianventure-edit-inline.less');
    }
    $tsmart_product_id = $app->input->get('tsmart_product_id', array(), 'array');
    $tsmart_product_id = $tsmart_product_id[0];
    $uri = JUri::getInstance();
    $url = $uri->toString(array('query'));
    $js_content = '';
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.admin.com_tsmart').asianventure({
                show_iframe:<?php echo json_encode(tsmConfig::$show_iframe) ?>,
                add_new_popup:<?php echo $vmView->add_new_popup==1?1:0 ?>,
                cid:<?php echo json_encode($cid) ?>,
                key_string: "<?php echo $str_key ?>",
                url: '<?php echo 'index.php'.$url ?>',
                view: '<?php echo $vmView->getName() ?>',
            });
        });
    </script>
    <?php
    $js_content = ob_get_clean();
    require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/utility.php';
    $js_content = TSMUtility::remove_string_javascript($js_content);
    $doc->addScriptDeclaration($js_content);





    vmJsApi::addJScript('/administrator/components/com_tsmart/assets/js/jquery.coookie.js');
    vmJsApi::addJScript('/administrator/components/com_tsmart/assets/js/vm2admin.js');

    $vm2string = "editImage: 'edit image',select_all_text: '" . tsmText::_('com_tsmart_DRDOWN_SELALL') . "',select_some_options_text: '" . tsmText::_($selectText) . "'";
    vmJsApi::addJScript('vm.remindTab', "
		var tip_image='" . JURI::root(true) . "/components/com_tsmart/assets/js/images/vtip_arrow.png';
		var vm2string ={" . $vm2string . "} ;
		jQuery( function($) {

			jQuery('dl#system-message').hide().slideDown(400);
			jQuery('.tsmart-admin-area .toggler').vm2admin('toggle');
			jQuery('#admin-ui-menu').vm2admin('accordeon');
			if ( jQuery('#admin-ui-tabs').length  ) {
				jQuery('#admin-ui-tabs').vm2admin('tabs',tsmartcookie);
			}
			jQuery('#content-box [title]').vm2admin('tips',tip_image);
			jQuery('.reset-value').click( function(e){
				e.preventDefault();
				none = '';
				jQuery(this).parent().find('.ui-autocomplete-input').val(none);
			});
		});	");

    ?>
    <!--[if lt IE 9]>
    <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <style type="text/css">
        .tsmart-admin-area {
            display: block;
        }

        .tsmart-admin-area #menu-wrapper {
            float: left;
        }

        .tsmart-admin-area #admin-content {
            margin-left: 221px;
        }

        <
        /
        script >
    <![endif]-->
    <?php if (!self::$backEnd) {
    //JToolBarHelper
    $bar = JToolbar::getInstance('toolbar');
    ?>
    <div class="toolbar-box" style="height: 84px;position: relative;"><?php echo $bar->render() ?></div>
<?php } ?>
    <?php $hideMenu = JFactory::getApplication()->input->cookie->getString('vmmenu', 'show') === 'hide' ? ' menu-collapsed' : ''; ?>
    <div class="tsmart-admin-area<?php echo $hideMenu ?> <?php echo $show_edit_in_line?'edit-in-line':''; ?>">
        <div class="div-loading"></div>
        <?php if ($vmView->add_new_popup == 1) { ?>
            <div id="vm-edit-form-<?php echo $vmView->getName() ?>" class="vm-edit-form">
                <iframe id="vm-iframe-<?php echo $vmView->getName() ?>" scrolling="no" src=""></iframe>
            </div>
        <?php } ?>
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
        <div id="admin-content" class="admin-content container-fluid">
            <?php if(!$hide_toolbar){ ?>
            <div class="row-fluid toolbar-top">

            </div>
            <div class="toolbar-top2 row-fluid">
                <div class="span8">
                    <img src="<?php echo JUri::root() ?>/images/Untitled-10.png">
                </div>
                <div class="span4 offset2">
                    <h2>ADMIN DASKBOARD</h2>
                </div>
            </div>

            <div class="toolbar-top3 row-fluid">
                <div class="span5">
                    <a href="index.html" class="navbar-brand">
                        <i class="im-windows8 text-logo-element animated bounceIn"></i><span class="text-logo">Asianventure</span>
                    </a>


                    <a title="" class="pull-right tool">
                        <i class="ec-help"></i>
                    </a>
                    <a title="" class="pull-right tool">
                        <i class="ec-pencil"></i>
                    </a>
                    <a title="" class="pull-right tool">
                        <i class="br-grid"></i>
                    </a>
                    <a title="" class="pull-right tool">
                        <i class="ec-refresh"></i>
                    </a>

                </div>
                <div class="span7">
                    <a title="" class="pull-right tool">
                        <i class="ec-help"></i>
                    </a>
                    <a title="" class="pull-right tool">
                        <i class="ec-pencil"></i>
                    </a>
                    <a title="" class="pull-right tool">
                        <i class="br-grid"></i>
                    </a>
                    <a title="" class="pull-right tool">
                        <i class="ec-refresh"></i>
                    </a>
                </div>
            </div>

            <div class="toolbar-top5 row-fluid">
                <div class="span3">
                    <h1><span title="" class="icon-palette"></span><span style="margin-left: 20px;color: #990100">Tour portal</span>
                    </h1>
                </div>
            </div>
            <?php } ?>
            <?php
            $app = JFactory::getApplication();
            $view = $app->input->get('view', 'tsmart', 'string');

            ?>
            <?php if ($view == 'tsmart') {
                echo self::show_tab_home_page($tsmart_product_id);
                ?>
                <div class="vm_toolbar"></div>
                <script>
                    jQuery(document).ready(function ($) {
                        $.fn.vertical_accordian_drop_down_menu_bar('#vertical_accordian_drop_down_menu_bar');
                    });
                </script>
            <?php } ?>
            <?php if ($view != 'tsmart') { ?>

                <?php if(!$hide_toolbar){ ?>
                <?php
                    if(in_array($view,array('country','state','cityarea','currency','language','airport'))){
                        echo self::show_tab_geo($view,$tsmart_product_id);
                        ?>
                        <div class="vm-title tab-geo row-fluid">
                            <div class="span2 ">
                                <h3 class="title_page"><?php echo JText::_($vmView->getName()) ?></h3>
                            </div>
                        </div>
                        <div class="vm_toolbar"></div>
                        <?php
                        }else{
                        echo self::show_tab_default($tsmart_product_id);
                        ?>
                        <div class="vm-title row-fluid">
                            <div class="span2 offset8">
                                <h3 class="title_page"><?php echo JText::_($vmView->getName()) ?></h3>
                            </div>
                        </div>
                        <div class="vm_toolbar"></div>
                        <?php
                    }
                ?>
                    <?php } ?>
            <?php } ?>

            <?php
            }

            /**
             * Close out the adminstrator area table.
             * @author RickG, Max Milbers
             */
            static function endAdminArea()
            {
            if (!self::$backEnd) return;
            self::$vmAdminAreaStarted = false;
            ?>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}

    /**
     * Admin UI Tabs
     * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
     * @param $load_template = a key => value array. key = template name, value = Language File contraction
     * @params $cookieName = choose a cookiename or leave empty if you don't want cookie tabs in this place
     * @example 'shop' => 'com_tsmart_ADMIN_CFG_SHOPTAB'
     */
    static public function buildTabs($view, $load_template = array(), $cookieName = '')
    {
        $cookieName = vRequest::getCmd('view', 'tsmart') . $cookieName;

        vmJsApi::addJScript('vm.cookie', '
		var tsmartcookie="' . $cookieName . '";
		');

        $html = '<div id="admin-ui-tabs">';

        foreach ($load_template as $tab_content => $tab_title) {
            $html .= '<div class="tabs" title="' . tsmText::_($tab_title) . '">';
            $html .= $view->loadTemplate($tab_content);
            $html .= '<div class="clear"></div></div>';
        }
        $html .= '</div>';
        echo $html;
    }


    /**
     * Admin UI Tabs Imitation
     * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
     * @param $return = return the start tag or the closing tag - choose 'start' or 'end'
     * @params $language = pass the language string
     */
    static function imitateTabs($return, $language = '')
    {
        if ($return == 'start') {

            vmJsApi::addJScript('vm.cookietab', '
			var tsmartcookie="vm-tab";
			');
            $html = '<div id="admin-ui-tabs">
							<div class="tabs" title="' . tsmText::_($language) . '">';
            echo $html;
        }
        if ($return == 'end') {
            $html = '		</div>
						</div>';
            echo $html;
        }
    }

    /**
     * Build an array containing all the menu items.
     *
     * @param int $moduleId Id of the module to filter on
     */
    static function _getAdminMenu($moduleId = 0)
    {
        $db = JFactory::getDBO();
        $menuArr = array();

        $filter [] = "jmmod.published='1'";
        $filter [] = "item.published='1'";

        if (!empty ($moduleId)) {
            $filter [] = 'vmmod.module_id=' . ( int )$moduleId;
        }

        $query = 'SELECT `jmmod`.`module_id`, `module_name`, `module_perms`, `id`, `name`, `link`, `depends`, `icon_class`, `view`, `task`';
        $query .= 'FROM `#__tsmart_modules` AS jmmod
						LEFT JOIN `#__tsmart_adminmenuentries` AS item ON `jmmod`.`module_id`=`item`.`module_id`
						WHERE  ' . implode(' AND ', $filter) . '
						ORDER BY `jmmod`.`ordering`, `item`.`ordering` ';

        $db->setQuery($query);
        $result = $db->loadAssocList();

        for ($i = 0, $n = count($result); $i < $n; $i++) {
            $row = $result [$i];
            $menuArr [$row['module_id']] ['title'] = 'com_tsmart_' . strtoupper($row['module_name']) . '_MOD';
            $menuArr [$row['module_id']] ['items'] [] = $row;
        }
        unset($menuArr[3]);
        unset($menuArr[11]);
        return $menuArr;
    }

    /**
     * Display the administrative ribbon menu.
     * @todo The link should be done better
     */
    static function showAdminMenu($vmView)
    {
        if (!isset(tsmConfig::$installed)) {
            tsmConfig::$installed = false;
        }
        if (!tsmConfig::$installed) return false;

        $moduleId = vRequest::getInt('module_id', 0);
        $menuItems = AdminUIHelper::_getAdminMenu($moduleId);
        $app = JFactory::getApplication();
        $isSite = $app->isSite();
        ?>
        <div id="admin-ui-menu" class="admin-ui-menu">
            <?php
            $modCount = 1;
            foreach ($menuItems as $item) {

                $html = '';
                foreach ($item ['items'] as $link) {
                    $target = '';
                    if ($link ['name'] == '-') {
                        // it was emtpy before
                    } else {
                        if (strncmp($link ['link'], 'http', 4) === 0) {
                            $url = $link ['link'];
                            $target = 'target="_blank"';
                        } else {
                            $url = ($link ['link'] === '') ? 'index.php?option=com_tsmart' : $link ['link'];
                            $url .= $link ['view'] ? "&view=" . $link ['view'] : '';
                            $url .= $link ['task'] ? "&task=" . $link ['task'] : '';
                            $url .= $isSite ? '&manage=1' : '';
                            // $url .= $link['extra'] ? $link['extra'] : '';
                            $url = vRequest::vmSpecialChars($url);
                        }

                        if ($vmView->manager($link ['view'])
                            || $target || $link ['view'] == 'about' || $link ['view'] == 'tsmart'
                        ) {
                            $html .= '
						<li>
							<a href="' . $url . '" ' . $target . '>
								<span class="vmicon-wrapper"><span class="' . $link ['icon_class'] . '"></span></span>
								<span class="menu-subtitle">' . tsmText::_($link ['name']) . '</span>
							</a>
						</li>';
                        }
                    }
                }
                if (!empty($html)) {
                    ?>
                    <h3 class="menu-title">
					<span class="menu-title-wrapper">
						<span class="vmicon-wrapper"><span
                                class="<?php echo tsmText::_($item['items'][0]['icon_class']) ?>"></span></span>
						<span class="menu-title-content"><?php echo tsmText::_($item ['title']) ?></span>
					</span>
                    </h3>

                    <div class="menu-list">
                        <ul>
                            <?php echo $html ?>
                        </ul>
                    </div>
                    <?php $modCount++;
                }
            }
            ?>
            <div class="menu-notice"></div>
        </div>
        <?php
    }
    function show_tab_default($tsmart_product_id){
        ob_start();
        ?>
        <div class="header-main-menu">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="index.php?option=com_tsmart">Daskboard</a>
                </li>
                <li role="presentation"><a href="#setup_system" aria-controls="profile" role="tab"
                                           data-toggle="tab">Setup
                        system</a></li>
                <li role="presentation"><a href="#logistic" aria-controls="messages" role="tab"
                                           data-toggle="tab">Logistic</a>
                </li>
                <li role="presentation"><a href="#tour_build" aria-controls="messages" role="tab"
                                           data-toggle="tab">Tour
                        buil</a></li>
                <li role="presentation"><a href="#tour_manager" aria-controls="settings" role="tab"
                                           data-toggle="tab">Tour
                        Manager</a></li>
                <li role="presentation"><a href="#sales_manager" aria-controls="settings" role="tab"
                                           data-toggle="tab">Sales Manager</a></li>
                <li role="presentation"><a href="#reservation" aria-controls="settings" role="tab"
                                           data-toggle="tab">Reservation</a>
                </li>
                <li role="presentation"><a href="#customer" aria-controls="settings" role="tab"
                                           data-toggle="tab">Customer</a>
                </li>
                <li role="presentation"><a href="#tour_enquiry" aria-controls="settings" role="tab"
                                           data-toggle="tab">Tour
                        Enquiry</a></li>
                <li role="presentation"><a href="#report" aria-controls="settings" role="tab"
                                           data-toggle="tab">Report</a></li>
                <li role="presentation"><a href="#suport" aria-controls="settings" role="tab"
                                           data-toggle="tab">Suport</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="daskboard">

                </div>
                <div role="tabpanel" class="tab-pane" id="setup_system">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href='index.php?option=com_tsmart&view=general'><i
                                            class="im-screen"></i><?php echo JText::_('general setup') ?></a></li>
                                <li><a href="index.php?option=com_tsmart&view=template"><span
                                            class="icon-palette" title=""></span>Template</a></li>

                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href='index.php?option=com_tsmart&view=apisetting'><i
                                            class="im-screen"></i>Api setting</a></li>
                                <li><a href='index.php?option=com_tsmart&view=seosetting'><i
                                            class="im-screen"></i>Seo setting</a></li>
                                <li><a href="index.php?option=com_tsmart&view=currency"><span
                                            class="icon-palette" title=""></span>Curency</a></li>

                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=language"><span
                                            class="icon-palette" title=""></span>Language</a></li>
                                <li><a href="index.php?option=com_tsmart&view=module"><span class="icon-palette" title=""></span>Module</a>
                                </li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=paymentsetting"><span class="icon-palette" title=""></span>Payment</a>
                                </li>
                                <li><a href="index.php?option=com_tsmart&view=paymentmethod"><span class="icon-palette" title=""></span>Payment Method</a>
                                </li>
                                <li><a href="index.php?option=com_tsmart&view=general&layout=passenger"><span class="icon-palette" title=""></span><?php echo JText::_('Passenger type') ?></a>
                                </li>
                                <li><a href="index.php?option=com_tsmart&view=company"><span class="icon-palette" title=""></span><?php echo JText::_('Company infomation') ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>


                </div>
                <div role="tabpanel" class="tab-pane" id="logistic">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=country"><span
                                            class="icon-palette" title=""></span>GEO contry</a></li>
                                <li><a href="index.php?option=com_tsmart&view=state"><span
                                            class="icon-palette" title=""></span>state/province</a></li>
                                <li><a href="index.php?option=com_tsmart&view=cityarea"><span
                                            class="icon-palette" title=""></span>city/area</a></li>
                                <li><a href="index.php?option=com_tsmart&view=airport"><span
                                            class="icon-palette" title=""></span>airport</a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=tourclass"><span
                                            class="icon-palette" title=""></span>Tour class</a></li>
                                <li><a href="index.php?option=com_tsmart&view=activity"><span
                                            class="icon-palette" title=""></span>Activities</a></li>
                                <li><a href="index.php?option=com_tsmart&view=toursection"><span
                                            class="icon-palette" title=""></span>tour section</a>
                                </li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=tourtype"><span
                                            class="icon-palette" title=""></span>Tour type</a></li>
                                <li><a href="index.php?option=com_tsmart&view=tourstyle"><span
                                            class="icon-palette" title=""></span>Tour style</a></li>

                                <li class="btn-group">
                                    <a href="#" class=" dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false"><span class="icon-palette" title=""></span>
                                        Service supplier </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="index.php?option=com_tsmart&view=supplier"></span>supplier manager</a>
                                        </li>

                                        <li>
                                            <a href="index.php?option=com_tsmart&view=servicetype">service field list</a>
                                        </li>
                                    </ul>

                                </li>
                                <!-- Single button -->

                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=hotel"><span
                                            class="icon-palette" title=""></span>Hotel</a>
                                    <ul style="display: none" class="dropdown-menu">
                                        <li>
                                            <a href="index.php?option=com_tsmart&view=room"></span>Room</a>
                                        </li>

                                    </ul>

                                </li>
                                <li><a href="index.php?option=com_tsmart&view=groupsize"><span
                                            class="icon-palette" title=""></span>Tour group size</a></li>
                                <li><a href="index.php?option=com_tsmart&view=physicalgrade"><span
                                            class="icon-palette" title=""></span>physical grade</a></li>

                            </ul>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="tour_build">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=product&task=edit&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>Tour infomation</a></li>
                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=itinerary&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>build itinerary</a></li>

                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=price&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>Build tour price</a></li>

                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=accommodation&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>Accommodation</a></li>
                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=document&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>Add document</a></li>

                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=media&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>Add photo</a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=faq&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>add faqs</a></li>
                                <li>
                                    <a href="<?php echo $tsmart_product_id ? 'index.php?option=com_tsmart&view=relation&tsmart_product_id=' . $tsmart_product_id : 'javascript:void(0)' ?>"><span
                                            class="icon-palette" title=""></span>build relation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="tour_manager">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=product"><span
                                            class="icon-palette"
                                            title=""></span>Tour
                                        listing</a></li>
                                <li><a href="index.php?option=com_tsmart&view=departure"><span
                                            class="icon-palette" title=""></span>departure</a></li>
                                <li><a href="index.php?option=com_tsmart&view=payment"><span
                                            class="icon-palette" title=""></span>Payment</a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=transferaddon"><span
                                            class="icon-palette" title=""></span>Transfer addon</a></li>
                                <li><a href="index.php?option=com_tsmart&view=excursionaddon"><span
                                            class="icon-palette" title=""></span>excursion addon</a></li>
                                <li><a href="index.php?option=com_tsmart&view=excursionaddon"><span
                                            class="icon-palette" title=""></span>excursion addon</a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=dateavailability"><span class="icon-palette" title=""></span>set availability</a></li>
                                <li><a href="#"><span class="icon-palette" title=""></span>Asign</a></li>
                                <li>
                                    <a href="index.php?option=com_tsmart&view=hoteladdon"><span
                                            class="icon-palette" title=""></span>Hotel addon</a></li>

                            </ul>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="sales_manager">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=promotion"><span
                                            class="icon-palette" title=""></span>Promotion</a></li>
                                <li><a href="index.php?option=com_tsmart&view=coupon"><span
                                            class="icon-palette" title=""></span>coupon</a></li>
                                <li><a href="index.php?option=com_tsmart&view=affiliate"><span
                                            class="icon-palette" title=""></span>Affiliate</a></li>
                                <li><a href="index.php?option=com_tsmart&view=coupon"><span
                                            class="icon-palette" title=""></span>Coupons</a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=gift"><span
                                            class="icon-palette" title=""></span>Gift</a></li>
                                <li><a href="index.php?option=com_tsmart&view=rewards"><span
                                            class="icon-palette" title=""></span>Rewards</a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="#"><span class="icon-palette" title=""></span>Tracker</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="reservation">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=orders"><span
                                            class="icon-palette"
                                            title=""></span>Reservation
                                        listing</a></li>
                                <li><a href="index.php?option=com_tsmart&view=passenger"><span
                                            class="icon-palette" title=""></span>Passenger manager</a></li>
                                <li><a href="index.php?option=com_tsmart&view=conversition"><span
                                            class="icon-palette" title=""></span>Conversition Manager</a></li>
                                <li><a href='index.php?option=com_tsmart&view=user'><i
                                            class="im-screen"></i>Customers</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="customer">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul class="ul_sub_menu">
                                <li><a href='index.php?option=com_tsmart&view=user'><i
                                            class="im-screen"></i>Customers listing</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="tour_enquiry">...</div>
                <div role="tabpanel" class="tab-pane" id="report">...</div>
                <div role="tabpanel" class="tab-pane" id="suport">...</div>
            </div>

        </div>

        <?php
        $html=ob_get_clean();
        return $html;
    }
    function show_tab_geo($view,$tsmart_product_id){
        ob_start();
        ?>
        <div class="header-main-menu tab-geo">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a href="index.php?option=com_tsmart">Daskboard</a>
                </li>
                <li role="presentation" ><a href="#geo_manager"
                                           ><?php echo JText::_('Geo Manager') ?></a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="daskboard">

                </div>
                <div role="tabpanel" class="tab-pane" id="geo_manager">
                    <div class="row-fluid">
                        <div class="span2">
                            <ul  class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=currency"><span
                                            class="icon-palette" title=""></span><?php echo JText::_('Currency') ?></a></li>
                                <li><a href="index.php?option=com_tsmart&view=language"><span
                                            class="icon-palette" title=""></span><?php echo JText::_('Language') ?></a></li>
                                <li><a href="index.php?option=com_tsmart&view=country"><span
                                            class="icon-palette" title=""></span><?php echo JText::_('Geo Country') ?></a></li>
                            </ul>
                        </div>
                        <div class="span2">
                            <ul  class="ul_sub_menu">
                                <li><a href="index.php?option=com_tsmart&view=state"><span
                                            class="icon-palette" title=""></span><?php echo JText::_('Geo State') ?></a></li>
                                <li><a href="index.php?option=com_tsmart&view=cityarea"><span
                                            class="icon-palette" title=""></span><?php echo JText::_('Geo City') ?></a></li>
                                <li><a href="index.php?option=com_tsmart&view=airport"><span
                                            class="icon-palette" title=""></span><?php echo JText::_('Airport') ?></a></li>
                            </ul>
                        </div>
                    </div>


                </div>

            </div>

        </div>

        <?php
        $html=ob_get_clean();
        return $html;
    }
    function show_tab_home_page($tsmart_product_id){
        ob_start();
        ?>
        <div class="toolbar-top4 row-fluid">
            <div class="span3 dashboard-left">
                <div id="vertical_accordian_drop_down_menu_bar">
                    <div class='menu'>
                        <ul>
                            <li class='active'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Dashboard</a>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Setup system</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=currency'><i
                                                class="im-screen"></i>currency</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=paymentmethod'><i
                                                class="im-screen"></i>Payment method</a>
                                    </li>
                                </ul>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Logistic</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=country'><i
                                                class="im-screen"></i>GEO country</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=state'><i
                                                class="im-screen"></i>Sate/province</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=cityarea'><i
                                                class="im-screen"></i>city/area</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=groupsize'><i
                                                class="im-screen"></i>Group size</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=tourclass'><i
                                                class="im-screen"></i>tour class</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=activity'><i
                                                class="im-screen"></i>Activities</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=tourstyle'><i
                                                class="im-screen"></i>Tour style</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=tourtype'><i
                                                class="im-screen"></i>Tour type</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=physicalgrade'><i
                                                class="im-screen"></i>physical grade</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=hotel'><i
                                                class="im-screen"></i>Hotel</a>
                                    </li>
                                </ul>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Tour
                                    build</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Genaral
                                            build</a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_tsmart&view=highlight&tsmart_product_id=<?php echo $tsmart_product_id ?>"><i
                                                class="im-screen"></i>Highlights</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=itinerary&tsmart_product_id=<?php echo $tsmart_product_id ?>'><i
                                                class="im-screen"></i>itinerary</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=media'><i
                                                class="im-screen"></i>Photo</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=document&tsmart_product_id=<?php echo $tsmart_product_id ?>'><i
                                                class="im-screen"></i> Documents</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=tourprice&tsmart_product_id=<?php echo $tsmart_product_id ?>'><i
                                                class="im-screen"></i>Tour price</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=relation&tsmart_product_id=<?php echo $tsmart_product_id ?>'><i
                                                class="im-screen"></i>Relation</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=hotel&tsmart_product_id=<?php echo $tsmart_product_id ?>'><i
                                                class="im-screen"></i>Hotel</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=faq&tsmart_product_id=<?php echo $tsmart_product_id ?>'><i
                                                class="im-screen"></i>FAQs</a>
                                    </li>
                                </ul>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Tour Manager</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=product'><i
                                                class="im-screen"></i>Tour listing</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=departure'><i
                                                class="im-screen"></i>departure</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=payment'><i
                                                class="im-screen"></i>Payment</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=departure'><i
                                                class="im-screen"></i>departure</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=discount'><i
                                                class="im-screen"></i>Discount</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=hoteladdon'><i
                                                class="im-screen"></i>Hotel addon</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=tranferaddon'><i
                                                class="im-screen"></i>Tranfer addon</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=excursion'><i
                                                class="im-screen"></i>excursion addon</a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=asign'><i
                                                class="im-screen"></i><?php echo JText::_('Asign') ?></a>
                                    </li>
                                </ul>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Sales manager</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=departure'><i
                                                class="im-screen"></i><?php echo JText::_('departure') ?></a>
                                    </li>
                                </ul>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i
                                        class="im-screen"></i>Reservation</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=orders'><i
                                                class="im-screen"></i><?php echo JText::_('Reservation listing')?></a>
                                    </li>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=passenger'><i
                                                class="im-screen"></i><?php echo JText::_('Passenger manager')?></a>
                                    </li>

                                </ul>
                            </li>
                            <li class='sub'>
                                <a href='index.php?option=com_tsmart'><i class="im-screen"></i>Customers</a>
                                <ul>
                                    <li>
                                        <a href='index.php?option=com_tsmart&view=user'><i
                                                class="im-screen"></i>Customers</a>
                                    </li>
                                </ul>
                            </li>

                            <li class='last'>
                                <a href='index.php?option=com_tsmart&view=help'><i class="im-screen"></i>Help</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="span9">
                <div class="row-fluid dashboard">
                    <div class="span6"><h1 class="page-header"><i class="im-screen"></i> Dashboard</h1></div>
                    <div class="span6 option-buttons">

                        <a title="" class="pull-right">
                            <i class="ec-help"></i>
                        </a>
                        <a title="" class="pull-right">
                            <i class="ec-pencil"></i>
                        </a>
                        <a title="" class="pull-right">
                            <i class="br-grid"></i>
                        </a>
                        <a title="" class="pull-right">
                            <i class="ec-refresh"></i>
                        </a>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <div class="tile-content tour">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span3">
                        <div class="tile-content hotel">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span3">
                        <div class="tile-content flight">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span3">
                        <div class="tile-content car">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                </div>
                <div class="row-fluid">

                    <div class="span6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="container"
                                     style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>

                    </div>
                    <div class="span6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="container"
                                     style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <div class="tile-content tour">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span2">
                        <div class="tile-content hotel">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span2">
                        <div class="tile-content flight">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span2">
                        <div class="tile-content car">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span2">
                        <div class="tile-content car">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                    <div class="span2">
                        <div class="tile-content car">
                            <div class="number countTo" data-from="0" data-to="5">5</div>
                            <h3>Settings changed</h3>
                            <i class="im-screen"></i>
                        </div>

                    </div>
                </div>


            </div>
        </div>

        <?php
        $html=ob_get_clean();
        return $html;
    }


}
?>