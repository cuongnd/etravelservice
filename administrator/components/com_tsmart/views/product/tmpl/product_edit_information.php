<<<<<<< master
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="buid-information">
    <h3 class="title"><?php echo JText::_('General information edit') ?></h3>
    <div class="row-fluid general-information">
        <div class="span4">
            <fieldset class="general">
                <legend>General</legend>
                <?php echo VmHTML::row_control('input', 'write name', 'product_name', $this->product->product_name, 'class="required"'); ?>
                <?php echo VmHTML::row_control('input', 'Select length', 'tour_length', $this->product->tour_length, 'class="required"'); ?>
                <?php echo VmHTML::row_control('select', 'Add country', 'list_tsmart_country_id[]', $this->countries, $this->product->list_tsmart_country_id, 'multiple', 'tsmart_country_id', 'country_name', false); ?>
                <?php echo VmHTML::row_control('location_city', 'Add start city', 'start_city', $this->product->start_city, ''); ?>
                <?php echo VmHTML::row_control('location_city', 'Add end city', 'end_city', $this->product->start_city, ''); ?>
                <?php echo VmHTML::row_control('select', 'Tour method', 'price_type', $this->list_tour_method, $this->product->tour_method, '', 'value', 'text', false); ?>
            </fieldset>

        </div>
        <div class="span4">
            <fieldset class="particularity">
                <legend>Particularity</legend>

                <?php echo VmHTML::row_control('select', 'Tour type', 'tsmart_tour_type_id', $this->list_tour_type, $this->product->tsmart_tour_type_id, '', 'tsmart_tour_type_id', 'tour_type_name', false); ?>
                <?php echo VmHTML::row_control('select', 'tour style', 'tsmart_tour_style_id', $this->list_tour_style, $this->product->tsmart_tour_style_id, '', 'tsmart_tour_style_id', 'tour_style_name', false); ?>
                <?php echo VmHTML::row_control('select', 'physical grade', 'tsmart_physicalgrade_id', $this->list_physical_grade, $this->product->tsmart_physicalgrade_id, '', 'tsmart_physicalgrade_id', 'physicalgrade_name', false); ?>

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
            <fieldset class="tour-section">
                <legend>Tour Section</legend>
                <?php echo VmHTML::list_radio('tsmart_tour_section_id', $this->list_tour_section, $this->product->tsmart_tour_section_id, '', 'tsmart_tour_section_id', 'tour_section_name', false, true, true, 3); ?>
            </fieldset>
            <fieldset class="list_tour_service_class">
                <legend>Service class</legend>
                <?php echo VmHTML::list_checkbox('list_tour_service_class_id', $this->list_tour_service_class, $this->product->list_tour_service_class_id, '', 'tsmart_service_class_id', 'service_class_name', 3); ?>
            </fieldset>
        </div>
    </div>
</div>

<div class="row-fluid ">
    <div class="span12">
        <h3  class="pull-right title-edit-trip-detail">
            <?php echo JText::_('Edit trip detail') ?>
        </h3>
    </div>
</div>


<div class="edit-trip-detail">
    <div class="tab-control-button">
        <div class="row-fluid ">
            <div class="span12">
                <div class="btn-toolbar" role="tablist">
                    <div role="presentation" class="active btn-wrapper"><a class="btn" href="#overview"
                                                                           aria-controls="overview" role="tab"
                                                                           data-toggle="tab"><span
                                class="icon-save"></span><?php echo JText::_('Overview') ?></a></div>
                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#activities"
                                                                           aria-controls="activities" role="tab"
                                                                           data-toggle="tab"><span class="icon-save"></span>activities</a>
                    </div>
                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#group_size"
                                                                           aria-controls="group_size" role="tab"
                                                                           data-toggle="tab"><span class="icon-save"></span><?php echo JText::_('Price type') ?></a></div>
                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#write_service"
                                                                           aria-controls="write_service" role="tab"
                                                                           data-toggle="tab"><span class="icon-save"></span><?php echo JText::_('Service') ?></a></div>
                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#trip_notes"
                                                                           aria-controls="trip_notes" role="tab"
                                                                           data-toggle="tab"><span class="icon-save"></span><?php echo JText::_('Trip notes')?></a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active overview" id="overview">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row-fluid">
                        <div class="span6">
                            <label class="title" for="meta_name"><?php echo JText::_('Add meta title') ?></label>
                            <?php echo VmHTML::textarea('meta_name', $this->product->meta_name, 'class="required" style="width:97%;height:100px"', 30, 10); ?>
                            <label class="title" for="meta_keyword"><?php echo JText::_('Add meta keyword') ?></label>
                            <?php echo VmHTML::textarea('meta_keyword', $this->product->meta_keyword, 'class="required" style="width:97%;height:100px"', 30, 10); ?>
                        </div>
                        <div class="span6">
                            <label class="title"  for="travel_route"><?php echo JText::_('Travel route') ?></label>
                            <?php echo VmHTML::editor('travel_route', $this->product->travel_route, '40%', 20, 10, 20, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <label class="title" for="meta_description"><?php echo JText::_('Meta description') ?></label>
                            <?php echo VmHTML::textarea('meta_description', $this->product->meta_description, 'class="required" style="width:97%;height:269px"', 30, 10); ?>
                        </div>
                        <div class="span6">
                            <label class="title"  for="trip_high_light"><?php echo JText::_('Trip highlights') ?></label>
                            <?php echo VmHTML::editor('trip_high_light', $this->product->trip_high_light, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <label class="title"  for="trip_high_light"><?php echo JText::_('Add overview') ?></label>
                            <?php echo VmHTML::editor('trip_overview', $this->product->trip_overview, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                        </div>
                        <div class="span6">
                            <label class="title" for="important_note"><?php echo JText::_('Important note') ?></label>
                            <?php echo VmHTML::editor('important_note', $this->product->important_note, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <label class="title"  for="trip_policy"><?php echo JText::_('Add trip policy') ?></label>
                            <?php echo VmHTML::editor('trip_policy', $this->product->trip_policy, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                        </div>
                        <div class="span6">
                            <label class="title"  for="term_condition"><?php echo JText::_('Term & condition') ?></label>
                            <?php echo VmHTML::editor('term_condition', $this->product->term_condition, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div role="tabpanel" class="tab-pane " id="activities">
            <div class="panel panel-default">
                <div class="panel-body">
                    <label class="activity-description"><?php echo JText::_('Activity description') ?></label>
                    <?php echo VmHTML::editor('activity_description', $this->product->activity_description, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                    <label class="select-activity"><?php echo JText::_('Select trip activity') ?></label>
                    <div class="list-activity">
                        <?php echo VmHTML::list_checkbox('list_activity_id', $this->activities, $this->product->list_activity_id, '', 'tsmart_activity_id', 'activity_name', 3); ?>
                    </div>
                </div>
            </div>


        </div>
        <div role="tabpanel" class="tab-pane" id="group_size">
            <div class="panel panel-default">
                <div class="panel-body">
                    <label class="price-type"><?php echo JText::_('Select price type') ?></label>
                    <div class="select-price-type">
                        <?php echo VmHTML::list_radio_price_type('price_type', $this->product->price_type, 'class="required"'); ?>
                    </div>
                    <div class="group-size" style="display: none">
                        <label class="select-group-size" style="text-align: center"><?php echo JText::_('select group size for multiply group price') ?></label>
                        <div class="select-list-group-size">
                            <?php echo VmHTML::list_checkbox_group_size('list_group_size_id', $this->product->list_group_size_id, '', false); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane" id="write_service">
            <div class="panel panel-default">
                <div class="panel-body">
                    <label class="lab-inclusions" style="text-align: left"><?php echo JText::_('Include service')?></label>
                    <?php echo VmHTML::editor('inclusions', $this->product->inclusions, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                    <label class="lab-exclusions" style="text-align: left"><?php echo JText::_('Excluded service')?></label>
                    <?php echo VmHTML::editor('exclusions', $this->product->exclusions, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                </div>
            </div>


        </div>
        <div role="tabpanel" class="tab-pane" id="trip_notes">
            <div class="panel panel-default">
                <div class="panel-body">
                    <label class="lab-trip-note-heading" style="text-align: left"><?php echo JText::_('Trip note heading')?></label>
                    <?php echo VmHTML::editor('trip_note_heading', $this->product->trip_note_heading, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                    <label class="lab-trip-note-content" style="text-align: left"><?php echo JText::_('Trip note content')?></label>
                    <?php echo VmHTML::editor('trip_note_content', $this->product->trip_note_content, '40%', 10, 10, 10, array('image', 'pagebreak', 'readmore', 'article', 'helix_shortcode')); ?>
                </div>
            </div>


        </div>
    </div>

</div>

<!-- Product pricing -->


<div class="clear"></div>

=======
<?php/** * * Main product information * * @package    tsmart * @subpackage Product * @author Max Milbers * @todo Price update calculations * @link http://www.tsmart.net * @copyright Copyright (c) 2004 - 2015 tsmart Team. All rights reserved. * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php * tsmart is free software. This version may have been modified pursuant * to the GNU General Public License, and as distributed it includes or * is derivative of works licensed under the GNU General Public License or * other free or open source software licenses. * @version $Id: product_edit_information.php 8982 2015-09-14 09:45:02Z Milbo $ */// Check to ensure this file is included in Joomla!defined('_JEXEC') or die('Restricted access');// set row counter$i = 0;?><div class="buid-information">    <h3 class="title"><?php echo JText::_('General information edit') ?></h3>    <div class="row-fluid general-information">        <div class="span4">            <fieldset class="general">                <legend>General</legend>                <?php echo VmHTML::row_control('input', 'write name', 'product_name', $this->product->product_name, 'required'); ?>                <?php echo VmHTML::row_control('input_number', 'Select length', 'tour_length', $this->product->tour_length, 'required'); ?>                <?php echo VmHTML::row_control('select', 'Add country', 'list_tsmart_country_id[]', $this->countries, $this->product->list_tsmart_country_id, 'multiple', 'tsmart_country_id', 'country_name', false); ?>                <?php echo VmHTML::row_control('location_city', 'Add start city', 'start_city', $this->product->start_city, ''); ?>                <?php echo VmHTML::row_control('location_city', 'Add end city', 'end_city', $this->product->end_city, ''); ?>                <?php echo VmHTML::row_control('select', 'Tour method', 'price_type', $this->list_tour_method, $this->product->tour_method, '', 'value', 'text', false); ?>                <?php echo VmHTML::row_control('input', 'Tour operator', 'tour_operator', $this->product->tour_operator, 'required'); ?>            </fieldset>        </div>        <div class="span4">            <fieldset class="particularity">                <legend>Particularity</legend>                <?php echo VmHTML::row_control('select', 'Tour type', 'tsmart_tour_type_id', $this->list_tour_type, $this->product->tsmart_tour_type_id, '', 'tsmart_tour_type_id', 'tour_type_name', false); ?>                <?php echo VmHTML::row_control('select', 'tour style', 'tsmart_tour_style_id', $this->list_tour_style, $this->product->tsmart_tour_style_id, '', 'tsmart_tour_style_id', 'tour_style_name', false); ?>                <?php echo VmHTML::row_control('select', 'physical grade', 'tsmart_physicalgrade_id', $this->list_physical_grade, $this->product->tsmart_physicalgrade_id, '', 'tsmart_physicalgrade_id', 'physicalgrade_name', false); ?>                <div class="control-group min-max-pers"><label class="control-label">Min Max pers</label>                    <div class="controls">                        <div class="row-fluid  ">                            <div class="span5">                                <?php echo VmHTML::input_number('min_person', $this->product->min_person, 'required min_person','',0,99); ?>                            </div>                            <div class="span1 offset1">                                <label class="control-label">To</label>                            </div>                            <div class="span5">                                <?php echo VmHTML::input_number('max_person', $this->product->max_person, 'required max_person','',0,99); ?>                            </div>                        </div>                    </div>                </div>                <div class="control-group min-max-pers"><label class="control-label">Min Max age</label>                    <div class="controls">                        <div class="row-fluid  ">                            <div class="span5">                                <?php echo VmHTML::input_number('min_age', $this->product->min_age, 'required min_age','',0,99); ?>                            </div>                            <div class="span1 offset1">                                <label class="control-label">To</label>                            </div>                            <div class="span5">                                <?php echo VmHTML::input_number('max_age', $this->product->max_age, 'required max_age','',0,99); ?>                            </div>                        </div>                    </div>                </div>            </fieldset>        </div>        <div class="span4">            <fieldset class="tour-section">                <legend>Tour Section</legend>                <?php echo VmHTML::list_radio('tsmart_tour_section_id', $this->list_tour_section, $this->product->tsmart_tour_section_id, '', 'tsmart_tour_section_id', 'tour_section_name', false, true, true, 3); ?>            </fieldset>            <fieldset class="list_tour_service_class">                <legend>Service class</legend>                <?php echo VmHTML::list_checkbox('list_tour_service_class_id', $this->list_tour_service_class, $this->product->list_tour_service_class_id, '', 'tsmart_service_class_id', 'service_class_name', 3); ?>            </fieldset>        </div>    </div></div><div class="row-fluid ">    <div class="span12">        <h3  class="pull-right title-edit-trip-detail">            <?php echo JText::_('Edit trip detail') ?>        </h3>    </div></div><div class="edit-trip-detail">    <div class="tab-control-button">        <div class="row-fluid ">            <div class="span12">                <div class="btn-toolbar" role="tablist">                    <div role="presentation" class="active btn-wrapper"><a class="btn" href="#overview"                                                                           aria-controls="overview" role="tab"                                                                           data-toggle="tab"><span                                class="icon-save"></span><?php echo JText::_('Overview') ?></a></div>                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#activities"                                                                     aria-controls="activities" role="tab"                                                                     data-toggle="tab"><span class="icon-save"></span>activities</a>                    </div>                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#group_size"                                                                     aria-controls="group_size" role="tab"                                                                     data-toggle="tab"><span class="icon-save"></span><?php echo JText::_('Price type') ?></a></div>                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#write_service"                                                                     aria-controls="write_service" role="tab"                                                                     data-toggle="tab"><span class="icon-save"></span><?php echo JText::_('Service') ?></a></div>                    <div role="presentation" class=" btn-wrapper"><a class="btn" href="#trip_notes"                                                                     aria-controls="trip_notes" role="tab"                                                                     data-toggle="tab"><span class="icon-save"></span><?php echo JText::_('Trip notes')?></a></div>                </div>            </div>        </div>    </div>    <div class="tab-content">        <div role="tabpanel" class="tab-pane active overview" id="overview">            <div class="panel panel-default">                <div class="panel-body">                    <div class="row-fluid">                        <div class="span6">                            <label class="title" for="meta_title"><?php echo JText::_('Add meta title') ?></label>                            <?php echo VmHTML::textarea('meta_title', $this->product->meta_title, 'class="required" style="width:97%;height:100px"', 30, 10); ?>                            <label class="title" for="meta_keyword"><?php echo JText::_('Add meta keyword') ?></label>                            <?php echo VmHTML::textarea('meta_keyword', $this->product->meta_keyword, 'class="required" style="width:97%;height:100px"', 30, 10); ?>                        </div>                        <div class="span6">                            <label class="title"  for="travel_route"><?php echo JText::_('Travel route') ?></label>                            <?php echo VmHTML::editor('travel_route', $this->product->travel_route, '40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>                        </div>                    </div>                    <div class="row-fluid">                        <div class="span6">                            <label class="title" for="meta_description"><?php echo JText::_('Meta description') ?></label>                            <?php echo VmHTML::textarea('meta_description', $this->product->meta_description, 'class="required" style="width:97%;height:269px"', 30, 10); ?>                        </div>                        <div class="span6">                            <label class="title"  for="trip_high_light"><?php echo JText::_('Trip highlights') ?></label>                            <?php echo VmHTML::editor('trip_high_light', $this->product->trip_high_light, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                        </div>                    </div>                    <div class="row-fluid">                        <div class="span6">                            <label class="title"  for="trip_overview"><?php echo JText::_('Add overview') ?></label>                            <?php echo VmHTML::editor('trip_overview', $this->product->trip_overview, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                        </div>                        <div class="span6">                            <label class="title" for="important_note"><?php echo JText::_('Important note') ?></label>                            <?php echo VmHTML::editor('important_note', $this->product->important_note, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                        </div>                    </div>                    <div class="row-fluid">                        <div class="span6">                            <label class="title"  for="trip_policy"><?php echo JText::_('Add trip policy') ?></label>                            <?php echo VmHTML::editor('trip_policy', $this->product->trip_policy, '40%', 10, 10, 10,tsmConfig::$list_editor_plugin_disable ); ?>                        </div>                        <div class="span6">                            <label class="title"  for="term_condition"><?php echo JText::_('Term & condition') ?></label>                            <?php echo VmHTML::editor('term_condition', $this->product->term_condition, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                        </div>                    </div>                    <div class="row-fluid">                        <div class="span6">                            <label class="title"  for="live_map"><?php echo JText::_('live map') ?></label>                            <?php echo VmHTML::google_map('latitude','longitude','location','radius', $this->product->latitude, $this->product->longitude, $this->product->location, $this->product->radius); ?>                        </div>                        <div class="span6">                            <label class="title"  for="image_map"><?php echo JText::_('Image map') ?></label>                            <?php echo VmHTML::image('image_map', $this->product->image_map); ?>                            <div class="view_image_map"><img src="<?php echo JUri::root().$this->product->image_map ?>"></div>                        </div>                    </div>                </div>            </div>        </div>        <div role="tabpanel" class="tab-pane " id="activities">            <div class="panel panel-default">                <div class="panel-body">                    <label class="activity-description"><?php echo JText::_('Activity description') ?></label>                    <?php echo VmHTML::editor('activity_description', $this->product->activity_description, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                    <label class="select-activity"><?php echo JText::_('Select trip activity') ?></label>                    <div class="list-activity">                        <?php echo VmHTML::list_checkbox('list_activity_id', $this->activities, $this->product->list_activity_id, '', 'tsmart_activity_id', 'activity_name', 3); ?>                    </div>                </div>            </div>        </div>        <div role="tabpanel" class="tab-pane" id="group_size">            <div class="panel panel-default">                <div class="panel-body">                    <label class="price-type"><?php echo JText::_('Select price type') ?></label>                    <div class="select-price-type">                        <?php echo VmHTML::list_radio_price_type('price_type', $this->product->price_type, 'class="required"'); ?>                    </div>                    <div class="group-size" style="display: none">                        <label class="select-group-size" style="text-align: center"><?php echo JText::_('select group size for multiply group price') ?></label>                        <div class="select-list-group-size">                            <?php echo VmHTML::list_checkbox_group_size('list_group_size_id', $this->product->list_group_size_id, '', false); ?>                        </div>                    </div>                </div>            </div>        </div>        <div role="tabpanel" class="tab-pane" id="write_service">            <div class="panel panel-default">                <div class="panel-body">                    <label class="lab-inclusions" style="text-align: left"><?php echo JText::_('Include service')?></label>                    <?php echo VmHTML::editor('inclusions', $this->product->inclusions, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                    <label class="lab-exclusions" style="text-align: left"><?php echo JText::_('Excluded service')?></label>                    <?php echo VmHTML::editor('exclusions', $this->product->exclusions, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                </div>            </div>        </div>        <div role="tabpanel" class="tab-pane" id="trip_notes">            <div class="panel panel-default">                <div class="panel-body">                    <label class="lab-trip-note-heading" style="text-align: left"><?php echo JText::_('Trip note heading')?></label>                    <?php echo VmHTML::editor('trip_note_heading', $this->product->trip_note_heading, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                    <label class="lab-trip-note-content" style="text-align: left"><?php echo JText::_('Trip note content')?></label>                    <?php echo VmHTML::editor('trip_note_content', $this->product->trip_note_content, '40%', 10, 10, 10, tsmConfig::$list_editor_plugin_disable); ?>                </div>            </div>        </div>    </div></div><!-- Product pricing --><div class="clear"></div>
>>>>>>> local
