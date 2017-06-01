<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Utility class to fire onContentPrepare for non-article based content.
 *
 * @since  1.5
 */
abstract class TSMHtmlBuil_rooming_list
{
    /**
     * Fire onContentPrepare for content that isn't part of an article.
     *
     * @param   string $text The content to be transformed.
     * @param   array $params The content params.
     * @param   string $context The context of the content to be transformed.
     *
     * @return  string   The content after transformation.
     *
     * @since   1.5
     */
    public static function rooming_list($list_passenger = array(), $name = 'roomming_list', $default = '0', $departure, $passenger_config, $type, $extra_night_config, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet('/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_rooming_list/html_build_rooming_list.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_rooming_list/html_build_rooming_list.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $session = JFactory::getSession();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_extra_night_hotel_' . $name;
        $type = $type ? $type : 'pre_night';
        //$debug = true;
        $config = tsmConfig::get_config();
        $params = $config->params;
        if ($type == 'pre_night') {
            $hotel_night_booking_days_allow = $params->get('hotel_pre_night_booking_days_allow', 1);
        } else {
            $hotel_night_booking_days_allow = $params->get('hotel_post_night_booking_days_allow', 1);
        }
        $hoteladdon_helper = tsmHelper::getHepler('hoteladdon');
        $group_min_price = $hoteladdon_helper->get_group_min_price($departure->tsmart_product_id, $departure->departure_date, $type);
        $list_room_type = array(
            single => 1,
            double => 2,
            twin => 2,
            trip => 3
        );
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_rooming_list({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    extra_night_config:<?php echo json_encode($extra_night_config) ?>,
                    type: '<?php echo $type ?>',
                    list_room_type:<?php echo json_encode($list_room_type) ?>,
                    hotel_night_booking_days_allow:<?php echo (int)$hotel_night_booking_days_allow ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_rooming_list " id="<?php echo $id_element ?>">
            <div class="rooming-list">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 style="text-align: center"><?php echo JText::_('Rooming list') ?></h4>
                        <div class="table table-hover table-bordered table-rooming-list">
                            <div class="thead">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('Room') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('Room type') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('Room note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row-fluid div-item-room">
                                    <div class="span2">
                                        <div class="row-fluid-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-fluid-item-column">
                                            <div class="room_type"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="private-room"></div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="room_note"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-night-hotel">
                    <div style="display: none" class="move-room handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="list_room_type row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <?php
                                        $room_limit = 16;
                                        ?>
                                        <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                            <div class="span3">
                                                <div class="" style="text-align: center"><?php echo $room_type ?></div>
                                                <select disable_chosen="true" data-room_type="<?php echo $room_type ?>"
                                                        class="room_type <?php echo $room_type ?> ">
                                                    <?php for ($i = 0; $i < $room_limit; $i++) { ?>
                                                        <option <?php echo $i == 0 ? 'selected' : '' ?> ><?php echo $i ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row-fluid note">
                                <div class="span12">
                                    <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                            <button type="button" class="btn btn-primary random-text">Random text
                                            </button><?php } ?></h4>
                                    <textarea data-name="room_note" style="width: 100%;height: 50px"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="list_room_type_passenger">
                                <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                    <div class="room-item <?php echo $room_type ?>">
                                        <div class="passenger-item row-fluid <?php echo $room_type ?>"
                                             data-room_type="<?php echo $room_type ?>">
                                            <div class="span3">
                                                <div
                                                    class="title_room text-uppercase"><?php echo JText::_($room_type) ?></div>
                                            </div>
                                            <div class="span1">
                                                <button type="button" class="btn btn-primary btn-xs remove_room">X
                                                </button>
                                            </div>
                                            <div class="span8">
                                                <?php for ($i = 0; $i < $allow_passenger; $i++) { ?>
                                                    <select  disable_chosen="true" disabled style="width: 100%" class="">
                                                        <option selected
                                                                value="-1"><?php echo JText::_('Please select passenger') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" data-name="tsmart_hotel_addon_id" class="tsmart_hotel_addon_id">
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
}
