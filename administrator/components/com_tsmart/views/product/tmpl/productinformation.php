<?php
/**
 *
 * Main product information
 *
 * @package    tsmart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2015 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 8982 2015-09-14 09:45:02Z Milbo $
 */
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_productonfomation.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="buid-information form-horizontal">
    <div class="row-fluid ">
        <div class="span4">
            <fieldset>
                <legend>General</legend>
                <?php echo VmHTML::row_control('text_view', 'Tour name', $view->product->product_name.'('.$view->product->product_code.')', 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view', 'Tour length', $view->product->tour_length, 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view', 'Tour countries', $view->product->list_country, 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view', 'start city', $view->product->start_city, 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view', 'end city', $view->product->end_city, 'class="required"'); ?>
            </fieldset>

        </div>
        <div class="span4">
            <fieldset>
                <legend>Particularity</legend>

                <?php echo VmHTML::row_control('text_view', 'Tour type', $view->product->tour_type, 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view', 'Tour stype', $view->product->tour_style, 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view', 'Difficulty grade', $view->product->physicalgrade, 'class="required"'); ?>
                <?php echo VmHTML::row_control('text_view_from_to', 'Min Max pers', $view->product->min_person,$view->product->max_person,$text='to', 'style="width:65px"',' style="width:65px" '); ?>
                <?php echo VmHTML::row_control('text_view_from_to', 'Min Max age', $view->product->min_age,$view->product->max_age,$text='to', 'style="width:65px"',' style="width:65px" '); ?>


            </fieldset>
        </div>
        <div class="span4">
            <fieldset >
                <legend>Tour Section</legend>
                <?php echo VmHTML::view_list(array($view->product->tour_section)); ?>
            </fieldset>
            <fieldset class="list_tour_service_class">
                <legend>Service class</legend>
                <?php echo VmHTML::view_list($view->product->list_tour_service_class); ?>
            </fieldset>
        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

