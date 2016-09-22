<?php
/**
 *
 * Main product information
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 8982 2015-09-14 09:45:02Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="buid-information">
    <div class="row-fluid ">
        <div class="span4">
            <fieldset>
                <legend>General</legend>
                <?php echo VmHTML::row_control('input', 'write name', 'product_name', $this->product->product_name, 'class="required"'); ?>
                <?php echo VmHTML::row_control('input', 'Select length', 'tour_length', $this->product->tour_length, 'class="required"'); ?>
                <?php echo VmHTML::row_control('select', 'Add country', 'list_virtuemart_country_id[]', $this->countries, $this->product->list_virtuemart_country_id, 'multiple', 'virtuemart_country_id', 'country_name', false); ?>
                <?php echo VmHTML::row_control('location_city', 'Add start city', 'start_city', $this->product->start_city, ''); ?>
                <?php echo VmHTML::row_control('location_city', 'Add end city', 'end_city', $this->product->start_city, ''); ?>
                <?php echo VmHTML::row_control('select', 'Tour method', 'price_type', $this->list_tour_method, $this->product->tour_method, '', 'value', 'text', false); ?>
            </fieldset>

        </div>
        <div class="span4">
            <fieldset>
                <legend>Particularity</legend>

                <?php echo VmHTML::row_control('select_tour_type', 'Tour type', 'virtuemart_tour_type_id', $this->product->virtuemart_tour_type_id, '', false); ?>
                <?php echo VmHTML::row_control('select', 'tour style', 'virtuemart_tour_style_id', $this->list_tour_style, $this->product->virtuemart_tour_style_id, '', 'virtuemart_tour_style_id', 'title', false); ?>
                <?php echo VmHTML::row_control('select', 'physical grade', 'virtuemart_physicalgrade_id', $this->list_physical_grade, $this->product->virtuemart_physicalgrade_id, '', 'virtuemart_physicalgrade_id', 'title', false); ?>

                <div class="control-group min-max-pers"><label class="control-label">Min Max pers</label>
                    <div class="controls">
                        <div class="row-fluid  ">
                            <div class="span5">
                                <?php echo VmHTML::input('min_person', $this->product->min_person, 'class="required min_person"'); ?>
                            </div>
                            <div class="span1 offset1">
                                <label class="control-label">To</label>
                            </div>
                            <div class="span5">
                                <?php echo VmHTML::input('max_person', $this->product->max_person, 'class="required max_person"'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="control-group min-max-pers"><label class="control-label">Min Max age</label>
                    <div class="controls">
                        <div class="row-fluid  ">
                            <div class="span5">
                                <?php echo VmHTML::input('min_age', $this->product->min_age, 'class="required min_age"'); ?>
                            </div>
                            <div class="span1 offset1">
                                <label class="control-label">To</label>
                            </div>
                            <div class="span5">
                                <?php echo VmHTML::input('max_age', $this->product->max_age, 'class="required max_age"'); ?>
                            </div>
                        </div>
                    </div>
                </div>


            </fieldset>
        </div>
        <div class="span4">
            <fieldset >
                <legend>Tour Section</legend>
                <?php echo VmHTML::list_radio('virtuemart_tour_section_id', $this->list_tour_section, $this->product->virtuemart_tour_section_id, '', 'virtuemart_tour_section_id', 'tour_section_name', false,true,true,2); ?>
            </fieldset>
            <fieldset class="list_tour_service_class">
                <legend>Service class</legend>
                <?php echo VmHTML::list_checkbox( 'list_tour_service_class_id', $this->list_tour_service_class, $this->product->list_tour_service_class_id, '', 'virtuemart_service_class_id', 'service_class_name', false); ?>
            </fieldset>
        </div>
    </div>
    <div class="row-fluid ">
        <div class="span12">
            <div class="btn-toolbar" role="tablist">
                <div role="presentation" class="active btn-wrapper"><a class="btn" href="#activities" aria-controls="activities" role="tab" data-toggle="tab"><span class="icon-save"></span>activities</a></div>
                <div role="presentation" class="active btn-wrapper"><a  class="btn" href="#group_size" aria-controls="group_size" role="tab" data-toggle="tab"><span class="icon-save"></span>group size</a></div>
                <div role="presentation" class="active btn-wrapper"><a  class="btn"  href="#write_service" aria-controls="write_service" role="tab" data-toggle="tab"><span class="icon-save"></span>write service</a></div>
                <div role="presentation" class="active btn-wrapper"><a  class="btn"  href="#meta_tag" aria-controls="meta_tag" role="tab" data-toggle="tab"><span class="icon-save"></span>Meta tag</a></div>
                <div role="presentation" class="active btn-wrapper"><a  class="btn"  href="#trip_facts" aria-controls="trip_facts" role="tab" data-toggle="tab"><span class="icon-save"></span>trip facts</a></div>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="activities">
            <div class="panel panel-default">
                <div class="panel-heading">Activities</div>
                <div class="panel-body">
                    <?php echo VmHTML::list_checkbox( 'list_activity_id', $this->activities, $this->product->list_activity_id, '', 'virtuemart_activity_id', 'title', false); ?>
                </div>
            </div>


        </div>
        <div role="tabpanel" class="tab-pane" id="group_size">
            <div class="panel panel-default">
                <div class="panel-heading">Select price type</div>
                <div class="panel-body">
                    <div class=""></div>
                    <?php echo VmHTML::list_radio_price_type('price_type', $this->product->price_type, 'class="required"'); ?>
                    <div class="group-size" style="display: none">
                        <hr/>
                        <h4 style="text-align: center">select group size for multiply group price</h4>
                        <?php echo VmHTML::list_checkbox_group_size('list_group_size_id',  $this->product->list_group_size_id, '', false); ?>
                    </div>
                </div>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane" id="write_service">
            <div class="panel panel-default">
                <div class="panel-heading">Write service</div>
                <div class="panel-body">
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 style="text-align: center">inclusions</h3>
                            <?php echo VmHTML::editor('inclusions', $this->product->inclusions); ?>

                        </div>
                    </div>
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 style="text-align: center">exclusions</h3>
                            <?php echo VmHTML::editor('exclusions', $this->product->exclusions); ?>

                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div role="tabpanel" class="tab-pane" id="meta_tag">
            <div class="panel panel-default">
                <div class="panel-heading">Meta tag</div>
                <div class="panel-body">
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 style="text-align: center">Meta name</h3>
                            <?php echo VmHTML::textarea( 'meta_name', $this->product->meta_name, 'class="required" style="width:100%"', 30, 10); ?>

                        </div>
                    </div>
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 style="text-align: center">Short description</h3>
                            <?php echo VmHTML::editor('product_s_desc', $this->product->product_s_desc); ?>

                        </div>
                    </div>
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 style="text-align: center">Long description</h3>
                            <?php echo VmHTML::editor('product_desc', $this->product->product_desc); ?>

                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane" id="trip_facts">
            <div class="panel panel-default">
                <div class="panel-heading">Trip facts</div>
                <div class="panel-body">
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 class="">Inserte image map</h3>
                            <?php echo VmHTML::image( '','image_map', $this->product->image_map, 'class="required" style="width:100%"', 30, 10); ?>
                        </div>
                        <div class="span12">
                            <h3 class="">Inserte google map</h3>
                            <?php echo VmHTML::google_map( 'latitude','longitude','location','radius' ,$this->product->latitude, $this->product->longitude,$this->product->location,$this->product->radius,'class="required" style="width:100%"', 30, 10); ?>
                        </div>
                    </div>
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 class="">Tour highlight</h3>
                            <?php echo VmHTML::editor('highlights', $this->product->highlights,'100%','40px'); ?>

                        </div>
                    </div>
                    <div class="row-fluid ">
                        <div class="span12">
                            <h3 class="">Add private policy</h3>
                            <?php echo VmHTML::editor('private_policy', $this->product->private_policy); ?>

                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>








<!-- Product pricing -->


<div class="clear"></div>

