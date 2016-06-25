<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$input = $app->input;
$show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
$cid = $input->get('cid', array(), 'array');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_virtuemart&controller=general&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'city_area_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$doc=JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_general_default.js');
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');

AdminUIHelper::startAdminArea($this);
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-general-default').view_general_default({
                task: "<?php echo $task ?>"
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);


?>

    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">
        <div class="view-general-default">
            <div id='tabbed-nav-general' data-role='z-tabs' data-options='{"theme": "silver", "orientation": "horizontal", "position": "top-left","size": "medium", "animation": {"duration": 400, "effects": "slideH"}, "defaultTab": "tab1"}'>

                <!-- Tab Navigation Menu -->
                <ul>
                    <li><a>Global</a></li>
                    <li><a>Features</a></li>
                    <li><a>Docs</a></li>
                    <li><a>Themes</a></li>
                    <li><a>Purchase</a></li>
                </ul>

                <!-- Content container -->
                <div>

                    <!-- Overview -->
                    <div>
                        <div class="row-fuid">
                            <div class="span4">
                                <?php echo VmHTML::row_control('select_from_to', 'Senior passenger allowance(Age to age)', 'params[senior_passenger_age_from]','params[senior_passenger_age_to]',$this->config->params->get('senior_passenger_age_from',0),$this->config->params->get('senior_passenger_age_to',0) , 'class="required"'); ?>
                                <?php echo VmHTML::row_control('select_from_to', 'Adult passenger allowance(Age to age)', 'params[adult_passenger_age_from]','params[adult_passenger_age_to]',$this->config->params->get('adult_passenger_age_from',0), $this->config->params->get('adult_passenger_age_to',0), 'class="required"'); ?>
                                <?php echo VmHTML::row_control('select_from_to', 'Teen passenger allowance(Age to age)', 'params[teen_passenger_age_from]','params[teen_passenger_age_to]',$this->config->params->get('teen_passenger_age_from',0), $this->config->params->get('teen_passenger_age_to',0), 'class="required"'); ?>
                                <?php echo VmHTML::row_control('select_from_to', 'Children 1 passenger allowance(Age to age)', 'params[children_1_passenger_age_from]','params[children_1_passenger_age_to]',$this->config->params->get('children_1_passenger_age_from',0), $this->config->params->get('children_1_passenger_age_to',0), 'class="required"'); ?>
                                <?php echo VmHTML::row_control('select_from_to', 'Children 2 passenger allowance(Age to age)', 'params[children_2_passenger_age_from]','params[children_2_passenger_age_to]',$this->config->params->get('children_2_passenger_age_from',0), $this->config->params->get('children_2_passenger_age_to',0), 'class="required"'); ?>
                                <?php echo VmHTML::row_control('select_from_to', 'Infant passenger allowance(Age to age)', 'params[infant_passenger_age_from]','params[infant_passenger_age_to]',$this->config->params->get('infant_passenger_age_from',0), $this->config->params->get('infant_passenger_age_to',0), 'class="required"'); ?>
                            </div>
                            <div class="span4">

                            </div>
                            <div class="span4"></div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div>
                        <h4>Features</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer</p>
                    </div>

                    <!-- Docs -->
                    <div>
                        <h4>Docs</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer</p>
                    </div>

                    <!-- Themes -->
                    <div>
                        <h4>Themes</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer</p>
                    </div>

                    <!-- Purchase -->
                    <div>
                        <h4>Purchase</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer</p>
                    </div>

                </div>

            </div>
        </div>
        <?php echo $this->addStandardHiddenToForm(); ?>
        <input type="hidden" value="<?php echo $this->config->virtuemart_general_id ?>" name="virtuemart_general_id">
        <?php echo JHtml::_('form.token'); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>