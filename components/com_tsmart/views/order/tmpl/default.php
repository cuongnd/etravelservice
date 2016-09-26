<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_virtuemart/assets/js/view_order_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_virtuemart/assets/less/view_order_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/plugin/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');

$app = JFactory::getApplication();
$input = $app->input;
$virtuemart_price_id = $input->getInt('virtuemart_price_id', 0);
$privategrouptrip = $this->privategrouptrip;

$session = JFactory::getSession();
$build_room = $this->order_data->build_room;
$list_passenger = array_merge($this->order_data->list_passenger->senior_adult_teen, $this->order_data->list_passenger->children_infant);
$build_room = json_decode($build_room);
$build_pre_transfer = $this->order_data->build_pre_transfer;//  $session->get('build_pre_transfer', '');
$build_post_transfer = $this->order_data->build_pre_transfer;//  $session->get('build_post_transfer', '');
$extra_pre_night_hotel = $this->order_data->extra_pre_night_hotel;// $session->get('extra_pre_night_hotel', '');
$extra_post_night_hotel = $this->order_data->extra_post_night_hotel;// $session->get('extra_post_night_hotel', '');
$contact_data = $this->order_data->contact_data;// = $session->get('contact_data', '');
$total_price = $this->get_total_price($build_room, $build_pre_transfer, $build_post_transfer, $extra_pre_night_hotel, $extra_post_night_hotel);
$booking_date = $input->getString('booking_date', '');
$this->product = $this->order_data->tour;

$this->order_data->list_passenger->leader = reset($this->order_data->list_passenger->senior_adult_teen);
/*$departure->sale_promotion_price_senior=100;
$departure->sale_promotion_price_adult=100;
$departure->sale_promotion_price_teen=100;
$departure->sale_promotion_price_children1=100;
$departure->sale_promotion_price_children2=100;
$departure->sale_promotion_price_infant=100;
$departure->sale_promotion_price_private_room=100;
$departure->sale_promotion_price_extra_bed=100;*/


$passenger_config = tsmConfig::get_passenger_config();

?>
    <div class="view-order-default">
        <form
            action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orderaddon&virtuemart_price_id=' . $virtuemart_price_id) ?>"
            method="post"
            id="order" name="order">
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="your-reservation"><?php echo JText::_('Your reservation') ?></h3>
                </div>
            </div>

            <div id="order-tabbed-nav">

                <!-- Tab Navigation Menu -->
                <ul>
                    <li><a><?php echo JText::_('Detail') ?></a></li>
                    <li><a>Features</a></li>
                    <li><a>Docs</a></li>
                    <li><a>Themes</a></li>
                    <li><a>Purchase</a></li>
                </ul>

                <!-- Content container -->
                <div>

                    <!-- Overview -->
                    <div>


                        <div class="row-fluid">
                            <div class="span12">
                                <div
                                    class="booking-info pull-right"><?php echo JText::sprintf('booking code <span>%s</span> booked on %s status %s', $this->order->order_number, $this->order->created_on, $this->order->order_status) ?></div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span9">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <h4 class="booking-summary"><?php echo JText::_('Booking summary') ?></h4>
                                    </div>
                                </div>
                                <div class="trip">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <?php echo JText::sprintf('My trip <span class="trip-name">%s</span>', $this->product->product_name) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="trip-overview">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div
                                                class="reference-no"><?php echo JText::sprintf('Reference no<br/><span class="reference">%s</span>', $this->order->order_number) ?></div>
                                        </div>
                                        <div class="span6">
                                            <div
                                                class="booking-status"><?php echo JText::sprintf('Booking status<br/><span class="status">%s</span>', $this->order->order_status) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-trip-overview">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="main-trip-left">
                                                <div><?php echo JText::sprintf('<span class="tour-name">tour name</span>', $this->product->product_name) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">tour code</span>', $this->product->product_sku) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">Booking on</span>', $this->product->created_on) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">Tour class</span>', $this->product->tour_class) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">Traveler</span>', count($list_passenger)) ?></div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="main-trip-right">
                                                <div><?php echo JText::sprintf('<span class="tour-name">tour name</span>', $this->product->product_name) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">tour code</span>', $this->product->product_sku) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">Booking on</span>', $this->product->created_on) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">Tour class</span>', $this->product->tour_class) ?></div>
                                                <div><?php echo JText::sprintf('<span class="tour-name">Traveler</span>', count($list_passenger)) ?></div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="leader-info">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div
                                                class="leader">
                                                <h4 class="leader-name"><span
                                                        class="title"><?php echo JText::_('lead passenger') ?></span>:<span><?php echo tsmConfig::get_full_name($this->order_data->list_passenger->leader) ?></span>
                                                </h4>
                                                <div class="thanks"><?php echo $this->lipsum->words(50) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="information">
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <?php echo VmHtml::edit_passenger_in_order($this->order, "edit_passenger_in_order", "", $privategrouptrip, $passenger_config, $this->product) ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="information">
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <?php echo VmHtml::passenger_rooming_list_in_order($this->order_data->list_passenger, "passenger_rooming_list_in_order", "", $privategrouptrip, $passenger_config, $this->product) ?>

                                        </div>
                                    </div>
                                </div>
                                <h3 class="add-on-service"><?php echo JText::sprintf('Addon service') ?></h3>
                                <div class="information">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <?php echo VmHtml::passenger_transfer($this->order_data->build_pre_transfer, "passenger_pre_transfer", 'pre') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="information">
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <?php echo VmHtml::passenger_transfer($this->order_data->build_post_transfer, "passenger_pre_transfer", 'post') ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="information">
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <?php echo VmHtml::passenger_hotel_night($this->order_data->list_passenger, "passenger_pre_hotel", "", $privategrouptrip, $passenger_config, $this->product) ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="information">
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <?php echo VmHtml::passenger_hotel_night($this->order_data->list_passenger, "passenger_post_hotel", "", $privategrouptrip, $passenger_config, $this->product) ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="payment-overview">
                                            <h4><?php echo JText::_('Tour fee summary') ?></h4>
                                            <div class="row-fluid">
                                                <div class="span4"><?php echo JText::_('Total fee') ?><span
                                                        class="total_fee price">$US <?php echo $total_price ?></span>
                                                </div>
                                                <div class="span4"><?php echo JText::_('Deposit') ?><span class="price">$US 780</span>
                                                </div>
                                                <div class="span4"><?php echo JText::_('Balance') ?><span class="price">$US 780</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <?php
                            $list_destination = explode(';', $privategrouptrip->list_destination);
                            $des_start = reset($list_destination);
                            $des_finish = end($list_destination);
                            $total_day = $privategrouptrip->total_day - 1;
                            $start_date = JFactory::getDate($privategrouptrip->departure_date);

                            $total_day = $total_day ? $total_day : 0;

                            ?>
                            <div class="span3">
                                <div class="booking-summary-content">
                                    <h3 class="title"><?php echo JText::_('Manager my booking') ?></h3>
                                    <div class="payment-info">
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="pull-right">
                                                    <?php echo JText::_('Tour fee') ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="pull-left">
                                                    <?php echo JText::_('Base price') ?>
                                                </div>
                                                <div class="pull-right">
                                                    <span>1232</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="pull-left">
                                                    <?php echo JText::_('Tax & fees') ?>
                                                </div>
                                                <div class="pull-right">
                                                    <span>1232</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="pull-left">
                                                    <?php echo JText::_('Discout') ?>
                                                </div>
                                                <div class="pull-right">
                                                    <span>1232</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="pull-right">
                                                    <?php echo JText::_('<span class="total">Total price</span><span class="price">1500</span>') ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="pull-right">
                                                    (<?php echo JText::_('Include all service taxes, no hidden fee') ?>)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer-payment">
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="pull-right">
                                                        <div class="payment"><?php echo JText::_('Payment') ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <?php echo JText::sprintf('<div>Trip deposit</div> : %s', 4000) ?>
                                                    <?php echo JText::sprintf('<div>1st payment</div> : %s', 4000) ?>
                                                    <?php echo JText::sprintf('<div>2st payment</div> : %s', 4000) ?>
                                                </div>
                                                <div class="span6">

                                                    <?php echo JText::sprintf('<div>Paid on</div> : %s', 4000) ?>
                                                    <?php echo JText::sprintf('<div>1st payment</div> : %s', 4000) ?>
                                                    <?php echo JText::sprintf('<div>2st payment</div> : %s', 4000) ?>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="pay_card">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <img src="<?php echo JUri::root() ?>images/stories/pay_card.jpg">
                                            </div>
                                            <div class="span6">
                                                <img src="<?php echo JUri::root() ?>images/stories/pay_card_trust.jpg">
                                                <button class="btn-link"><?php echo JText::_('Make payment') ?> <span
                                                        class="icon-next"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <h4 class="self-service"><?php echo JText::_('Self service') ?></h4>
                                    <div class="links">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Confirm my hold space') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Update passenger detail') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Resend my voucher') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Update arrival detail') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Update departure detail') ?>
                                                    </a></div>
                                            </div>
                                            <div class="span6">
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Cancel my booking') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Get my receipt') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('View all booking') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Download trip details') ?>
                                                    </a></div>
                                                <div><a href=""><span
                                                            class="icon-plus"></span><?php echo JText::_('Request more service') ?>
                                                    </a></div>

                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="my-contact">
                                        <h5 class="title"><?php echo JText::_('My contact') ?></h5>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <table class="table">
                                                    <tr>
                                                        <td><?php echo JText::_('Contact name') ?></td>
                                                        <td>:</td>
                                                        <td>dfsfsd</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo JText::_('Email address') ?></td>
                                                        <td>:</td>
                                                        <td>sdfsdf@df.dfd</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo JText::_('Phone number') ?></td>
                                                        <td>:</td>
                                                        <td>sdfsd</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <button class="btn btn-primary pull-right"><?php echo JText::_('Edit contact') ?></button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="need-help">
                                        <h5 class="title"><?php echo JText::_('Need some help ?') ?></h5>
                                        <div class="instant-support">
                                            <div><span class="icon-palette"></span><?php echo JText::_('Instant support') ?>:</div>
                                            <div class="body-instant-support">
                                                <div><span class="icon-call"></span> <?php echo JText::_('Speak to our expert 1-866-592-9658') ?></div>
                                                <div><span class="icon-chat"></span> <?php echo JText::_('Chat online with our travel expert') ?></div>
                                                <div><span class="icon-email"></span> <?php echo JText::_('Email for more information') ?></div>
                                            </div>
                                        </div>
                                        <div class="instant-support">
                                            <div><span class="icon-palette"></span><?php echo JText::_('Office hours') ?>:</div>
                                            <div class="body-instant-support office">
                                                <div> <?php echo JText::_('The office is open from 8h00 to 16h00, Mon. through fri. and 8h00 to 11h30 on Sat, 24/7 hotline support') ?></div>
                                            </div>
                                        </div>
                                        <div class="instant-support">
                                            <div><span class="icon-palette"></span><?php echo JText::_('Request a call back') ?>:</div>
                                            <div class="body-instant-support callback">
                                                <form class="form-vertical">
                                                    <div class="control-group">
                                                        <label class="control-label" for="country_code"><?php echo JText::_('Country code') ?></label>
                                                        <div class="controls">
                                                            <input type="text" name="country_code" id="country_code" >
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="phone_number"><?php echo JText::_('Phone number') ?></label>
                                                        <div class="controls">
                                                            <input type="text" name="phone_number" id="phone_number" >
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div>
                    <h4>Features</h4>
                    <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete account of the system, and expound the actual
                        teachings of the great explorer</p>
                </div>

                <!-- Docs -->
                <div>
                    <h4>Docs</h4>
                    <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete account of the system, and expound the actual
                        teachings of the great explorer</p>
                </div>

                <!-- Themes -->
                <div>
                    <h4>Themes</h4>
                    <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete account of the system, and expound the actual
                        teachings of the great explorer</p>
                </div>

                <!-- Purchase -->
                <div>
                    <h4>Purchase</h4>
                    <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete account of the system, and expound the actual
                        teachings of the great explorer</p>
                </div>

            </div>

    </div>


    <input name="option" value="com_virtuemart" type="hidden">
    <input name="booking_date" value="<?php echo $booking_date ?>" type="hidden">
    <input name="virtuemart_price_id" value="<?php echo $virtuemart_price_id ?>" type="hidden">
    <input name="controller" value="order" type="hidden">
    <input name="booking_summary" value="" type="hidden">
    <input type="hidden" value="order" name="view">
    <input name="task" value="" type="hidden">
    </form>


    </div>
<?php
$js_content = '';

ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-order-default').view_order_default({
                passenger_config:<?php  echo json_encode($passenger_config) ?>,
                departure:<?php  echo json_encode($privategrouptrip) ?>,
                tour_min_age:<?php echo $this->product->min_age ?>,
                build_room:<?php echo json_encode($build_room) ?>,
                build_pre_transfer:<?php echo $build_pre_transfer ?>,
                build_post_transfer:<?php echo $build_post_transfer ?>,
                extra_pre_night_hotel:<?php echo $extra_pre_night_hotel ?>,
                extra_post_night_hotel:<?php echo $extra_post_night_hotel ?>,
                total_price:<?php echo $total_price ?>,
                contact_data:<?php echo $contact_data ?>
            });


        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>