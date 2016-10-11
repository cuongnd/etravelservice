<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU company Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU company Public License or
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

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=company&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'city_area_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$doc=JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_company_default.js');
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_company_passenger.less');
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');

AdminUIHelper::startAdminArea($this);
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-company-default').view_company_default({
                task: "<?php echo $task ?>"
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$list_passenger_type=array('senior','adult','teen','children_1','children_2','infant');

?>
<div class="view-company-passenger">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">

        <div class="view-company-default">
            <h4 class="form-passenger-type"><?php echo JText::_('Passenger type setting') ?></h4>
            <?php for($i=0;$i<count($list_passenger_type);$i++){ ?>
                <?php
                $passenger_type=$list_passenger_type[$i];
                $passenger_type_label=$this->config->params->get($passenger_type.'_label',$passenger_type);
                ?>
            <div class="row-fluid">
                <div class="span4">
                    <div class="column-label">
                        <div class="label"><?php echo JText::_('Passenger type '.($i+1)) ?></div><?php echo VmHTML::input('params['.$passenger_type.'_label]',$this->config->params->get($passenger_type.'_label',$passenger_type),'class="required"') ?>
                    </div>
                </div>
                <div class="span2">
                    <div class="activelist">
                        <?php echo VmHTML::bootstrap_activelist('params['.$passenger_type.'_state]', $this->config->params->get($passenger_type.'_state',true),' '); ?>
                    </div>
                </div>
                <div class="span6">
                    <div class="slider-age">
                        <div class="pull-left title"> <?php echo "Set $passenger_type_label age" ?> </div>
                        <div  class="pull-left" style="width: 70%"><?php echo VmHTML::select_from_to('params['.$passenger_type.'_passenger_age_from]','params['.$passenger_type.'_passenger_age_to]',$this->config->params->get($passenger_type.'_passenger_age_from',0),$this->config->params->get($passenger_type.'_passenger_age_to',0) , 'class="required"'); ?></div>
                    </div>
                </div>

            </div>
            <?php } ?>
        </div>
        <?php echo $this->addStandardHiddenToForm(); ?>
        <input type="hidden" value="<?php echo $this->config->tsmart_company_id ?>" name="tsmart_company_id">
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <div class="footer"></div>
</div>
<?php AdminUIHelper::endAdminArea(); ?>