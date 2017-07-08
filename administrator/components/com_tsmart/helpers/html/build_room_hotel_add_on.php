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
abstract class TSMHtmlBuild_room_hotel_add_on
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
    public static function build_room($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $disable = false, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui');
        TSMHtmlJquery::numeric();
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('/administrator/components/com_tsmart/assets/js/controller/build_room_hotel_add_on/build_room_hotel_add_on.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet('/administrator/components/com_tsmart/assets/js/controller/build_room_hotel_add_on/build_room_hotel_add_on.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_room';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').build_room_hotel_add_on({
                    list_passenger:<?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    disable:<?php echo json_encode($disable) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_room row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-room">
                    <div class="row-fluid">
                        <div class="span12"><h3><?php echo JText::_('Room ') ?><span class="room-order">1</span></h3>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <h3 style="text-align: center;color: #820004"><?php echo JText::_('Select room type') ?></h3>
                            <div class="list-room">
                                <div class="row-fluid">
                                    <div class="span2" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Single') ?></br>
                                            <input type="radio" checked
                                                   data-name="room_type"
                                                   name="room_type" data-note="single"
                                                   value="single"></label>
                                    </div>
                                    <div class="span3" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Double') ?></br>
                                            <input type="radio" data-name="room_type" data-note="double"
                                                   name="room_type"
                                                   value="double"></label>
                                    </div>
                                    <div class="span3"></div>
                                    <div class="span2" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Twin') ?></br>
                                            <input type="radio" data-name="room_type" data-note="twin"
                                                   name="room_type"
                                                   value="twin"></label>
                                    </div>
                                    <div class="span2" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Triple') ?></br>
                                            <input type="radio" data-name="room_type" data-note="triple"
                                                   name="room_type"
                                                   value="triple"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row note ">
                                <div class="span12">
                                    <div class="price-note">
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <table class="passenger-price-summary">
                                                    <tr class="passenger-price">
                                                        <td><span class="order"></span></td>
                                                        <td><span class="full-name"></span></td>
                                                        <td><span class="price"></span></td>
                                                        <td><span class="">x</span></td>
                                                        <td><span class="night"></span> nites</td>
                                                        <td><span class="total"></span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row note">
                                <div class="span12">
                                    <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                            <button type="button" class="btn btn-primary random-text">Random text
                                            </button><?php } ?></h4>
                                    <textarea data-name="room_note" style="width: 100%;height: 50px"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <h3 class="" style="text-align: center;color: #820004"><?php echo JText::_('select person for room on your own') ?></h3>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span style="<?php echo !$debug ? 'display: none;' : '' ?>" class="in_room"></span></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="pull-right">
                                <button type="button"
                                        class="btn btn-primary remove-room"><?php echo JText::_('Remove room') ?></button>
                                &nbsp;&nbsp;
                                <button type="button"
                                        class="btn btn-primary add-more-room "><?php echo JText::_('Add more room') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rooming-list hide">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 style="text-align: center"><?php echo JText::_('Rooming list') ?></h4>
                        <div class="table table-hover table-bordered table-rooming-list">
                            <div class="thead">
                                <div class="row-fluid">
                                    <div class="span2 ">
                                        <div class="column-header-item"><?php echo JText::_('Room') ?></div>
                                    </div>
                                    <div class="span2 ">
                                        <div class="column-header-item"><?php echo JText::_('Room type') ?></div>
                                    </div>
                                    <div class="span3 ">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="span3 ">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="span2 ">
                                        <div class="column-header-item"><?php echo JText::_('Room note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row div-item-room">
                                    <div class="span2 ">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2 ">
                                        <div class="row-item-column">
                                            <div class="room_type"></div>
                                        </div>
                                    </div>
                                    <div class="span3 ">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="span3 ">
                                        <div class="row-item-column">
                                            <div class="private-room"></div>
                                        </div>
                                    </div>
                                    <div class="span2 ">
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
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
}
