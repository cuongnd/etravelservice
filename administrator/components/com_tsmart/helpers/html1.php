<<<<<<< master
<?php
/**
 * HTML helper class
 *
 * This class was developed to provide some standard HTML functions.
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author RickG
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009 tsmart Team. All rights reserved.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML Helper
 *
 * @package tsmart
 * @subpackage Helpers
 * @author RickG
 */
class VmHtml
{

    /**
     * Default values for options. Organized by option group.
     *
     * @var     array
     * @since   11.1
     */
    static protected $_optionDefaults = array(
        'option' => array('option.attr' => null, 'option.disable' => 'disable', 'option.id' => null, 'option.key' => 'value',
            'option.key.toHtml' => true, 'option.label' => null, 'option.label.toHtml' => true, 'option.text' => 'text',
            'option.text.toHtml' => true));

    static protected $_usedId = array();

    static function ensureUniqueId($id)
    {

        if (isset(self::$_usedId[$id])) {
            $c = 1;
            while (isset(self::$_usedId[$id . $c])) {
                $c++;
            }
            $id = $id . $c;
        }
        self::$_usedId[$id] = 1;
        return $id;
    }

    /**
     * Converts all special chars to html entities
     *
     * @param string $string
     * @param string $quote_style
     * @param boolean $only_special_chars Only Convert Some Special Chars ? ( <, >, &, ... )
     * @return string
     */
    static function shopMakeHtmlSafe($string, $quote_style = 'ENT_QUOTES', $use_entities = false)
    {

        if (defined($quote_style)) {
            $quote_style = constant($quote_style);
        }
        if ($use_entities) {
            $string = @htmlentities($string, constant($quote_style), self::vmGetCharset());
        } else {
            $string = @htmlspecialchars($string, $quote_style, self::vmGetCharset());
        }
        return $string;
    }


    /**
     * Returns the charset string from the global _ISO constant
     *
     * @return string UTF-8 by default
     * @since 1.0.5
     */
    static function vmGetCharset()
    {
        $iso = explode('=', @constant('_ISO'));
        if (!empty($iso[1])) {
            return $iso[1];
        } else {
            return 'UTF-8';
        }
    }

    /**
     * Generate HTML code for a row using VmHTML function
     * works also with shopfunctions, for example
     * $html .= VmHTML::row (array('ShopFunctions', 'renderShopperGroupList'),
     *            'VMCUSTOM_BUYER_GROUP_SHOPPER', $field->shopper_groups, TRUE, 'custom_param['.$row.'][shopper_groups][]', ' ');
     *
     * @func string  : function to call
     * @label string : Text Label
     * @args array : arguments
     * @return string: HTML code for row table
     */
    static function row($func, $label)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }

        $html = '
		<tr>
			<td class="key">
				' . $label . '
			</td>
			<td>';
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }

        $html .= call_user_func_array($func, $args) . '
			</td>';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        $html .= '</tr>';
        return $html;
    }

    static function row_control($func, $label)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }
        $label = '<label class="control-label">' . $label . '</label>';

        $html = ' <div class="control-group ">' . $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }

        $html .= '
		 <div class="controls ">
                    ' . call_user_func_array($func, $args) . '
                </div>
			';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        $html .= '</div>';
        return $html;
    }

    static function row_control_v_1($func, $label, $controls_class = '')
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        array_shift($passedArgs);//remove control class
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }
        $label = '<label class="control-label">' . $label . '</label>';

        $html = ' <div class="control-group ' . $controls_class . '">' . $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }

        $html .= '
		 <div class="controls ">
                    ' . call_user_func_array($func, $args) . '
                </div>
			';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        $html .= '</div>';
        return $html;
    }

    static function row_basic($func, $label)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }

        $html = $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }

        $html .= '

                    ' . call_user_func_array($func, $args) . '
               ';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }

        return $html;
    }

    /* simple value display */
    static function value($value)
    {
        $lang = JFactory::getLanguage();
        return $lang->hasKey($value) ? tsmText::_($value) : $value;
    }

    /**
     * The sense is unclear !
     * @deprecated
     * @param $value
     * @return mixed
     */
    static function raw($value)
    {
        return $value;
    }

    /**
     * Generate HTML code for a checkbox
     *
     * @param string Name for the checkbox
     * @param mixed Current value of the checkbox
     * @param mixed Value to assign when checkbox is checked
     * @param mixed Value to assign when checkbox is not checked
     * @return string HTML code for checkbox
     */
    static function checkbox($name, $value, $checkedValue = 1, $uncheckedValue = 0, $extraAttribs = '', $id = null)
    {
        if (!$id) {
            $id = '';
        } else {
            $id = 'id="' . $id . '"';
        }

        if ($value == $checkedValue) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }

        $htmlcode = '<input type="hidden" name="' . $name . '" value="' . $uncheckedValue . '" />';
        $htmlcode .= '<input ' . $extraAttribs . ' ' . $id . ' type="checkbox" name="' . $name . '" value="' . $checkedValue . '" ' . $checked . ' />';
        return $htmlcode;
    }

    static function range_iterger($name, $value, $checkedValue = 1, $uncheckedValue = 0, $extraAttribs = '', $id = null)
    {
        if (!$id) {
            $id = '';
        } else {
            $id = 'id="' . $id . '"';
        }

        if ($value == $checkedValue) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }

        $htmlcode = '<input type="hidden" name="' . $name . '" value="' . $uncheckedValue . '" />';
        $htmlcode .= '<input ' . $extraAttribs . ' ' . $id . ' type="checkbox" name="' . $name . '" value="' . $checkedValue . '" ' . $checked . ' />';
        return $htmlcode;
    }

    /**
     *
     * @author Patrick Kohl
     * @param array $options ( value & text)
     * @param string $name option name
     * @param string $defaut defaut value
     * @param string $key option value
     * @param string $text option text
     * @param boolean $zero add  a '0' value in the option
     * return a select list
     */
    public static function select($name, $options, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $options);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';

        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }

    public static function select_state($name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $options = array();
        $options[] = array($key => "1", $text => tsmText::_('active'));
        $options[] = array($key => "0", $text => tsmText::_('unactive'));
        if ($zero == true) {
            $option = array($key => "", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $options);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';

        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }

    public static function select_state_province($name, $list_state, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $country_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_state_province/html_select_state_province.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_state_province/html_select_state_province.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_state)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmstates.php';
            $list_state = tmartstates::get_states();
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_state_province({
                    state_element: '<?php echo $country_element ?>',
                    tsmart_state_id:<?php echo $default ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);


        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $list_state = array_merge(array($option), $list_state);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';

        }
        return VmHtml::genericlist($list_state, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }

    public static function location_city1($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_location_city/html_select_select_location_city.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_location_city/html_select_select_location_city.less');
        $input = JFactory::getApplication()->input;

        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcities.php';
        $cities = tsmcities::get_cities();
        $option = array('id' => '', 'text' => 'Please select location');
        foreach ($cities as &$city) {
            $city->id = $city->tsmart_cityarea_id;
            $city->text = $city->city_area_name;
        }
        array_unshift($cities, $option);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_location_city({
                    cities:<?php echo json_encode($cities) ?>,
                    vituemart_cityarea_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);


        if ($zero == true) {
            $option = array('vituemart_cityarea_id' => "0", 'city_area_name' => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $cities);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= '  disable_chosen="true"';

        }

        $html = VmHtml::genericlist(array(), $name, $attrib, 'vituemart_cityarea_id', 'city_area_name', $default, false, $tranlsate);
        ob_start();
        ?>
        <div class="html_select_select_location_city">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_tour_type($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_tour_type/html_select_tour_type.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_tour_type/html_select_tour_type.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmtourtype.php';
        $list_tour_type = tsmtourtype::get_list_tour_type();
        $id_element = 'html_select_tour_type_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_tour_type({
                    list_tour_type:<?php echo json_encode($list_tour_type) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_tour_type_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select tour type') ?></option>
                <?php foreach ($list_tour_type as $tour_type) { ?>
                    <option <?php echo $tour_type->tsmart_tour_type_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $tour_type->tsmart_tour_type_id ?>"
                        data-price_type="<?php echo $tour_type->price_type ?>"><?php echo $tour_type->title ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_number_passenger($name, $text_header = '', $min = 0, $max = 100, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        if (!$text_header) {
            $text_header = "Passenger from 12 years old";
        }
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_number_passenger/html_select_number_passenger.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_number_passenger/html_select_number_passenger.less');
        $input = JFactory::getApplication()->input;
        $list_number = range($min, $max, 1);

        $element_id = "select_number_passenger_$name";
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_number_passenger({
                    list_number:<?php echo json_encode($list_number) ?>,
                    number_selected:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $element_id ?>" class="select_number_passenger">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>" <?php echo $attrib ?> >
                <option value=""><?php echo $text_header ?></option>
                <?php for ($i = $min; $i < $max; $i++) { ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_tour($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_tour/html_select_tour.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_tour/html_select_tour.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmproduct.php';
        $list_products = vmproduct::get_list_product();
        foreach ($list_products as &$tour) {
            $tour->id = $tour->tsmart_product_id;
            $tour->text = $tour->product_name;
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_tour({
                    list_tour:<?php echo json_encode($list_products) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);


        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= '  disable_chosen="true"';

        }
        $html = VmHtml::genericlist($list_products, $name, $attrib, 'tsmart_product_id', 'product_name', $default, false, $tranlsate);
        ob_start();
        ?>
        <div class="html_select_tour">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_trip_join_and_private($name, $coupon)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_trip_join_and_private/select_trip_join_and_private.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_trip_join_and_private/select_trip_join_and_private.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
        $list_service_class = tsmserviceclass::get_list_tour_service_class();
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmproduct.php';
        $list_products = vmproduct::get_list_product();


        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmdeparture.php';
        $list_departure = tsmDeparture::get_list_departure();

        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmtourtype.php';
        $list_tour_type = tsmtourtype::get_list_tour_type();
        $element_id = 'select_trip_join_and_private_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id ?>').select_trip_join_and_private({
                    list_tour:<?php echo json_encode($list_products) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);


        ob_start();
        ?>
        <?php
        $span_left = 'span4';
        $span_right = 'span6';
        ?>
        <div id="<?php echo $element_id ?>" class="select_trip_join_and_private">
            <div class="row-fluid">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select tour type') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <select name="private_or_joingroup" class="tour_type">
                        <option value=""><?php echo JText::_('Please select tour type') ?></option>
                        <?php foreach ($list_tour_type as $tour_type) {
                            ?>
                            <option
                                value="<?php echo $tour_type->tsmart_tour_type_id ?>"><?php echo $tour_type->title ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row-fluid">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select trip') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_tour">
                        <?php foreach ($list_products as $tour) {
                            ?>
                            <label class="checked"
                                   data-tsmart_product_id="<?php echo $tour->tsmart_product_id ?>"><?php echo $tour->product_name ?>
                                <input name="tour[]" class="checkbox tsmart_product_id" type="checkbox"
                                       value="<?php echo $tour->tsmart_product_id ?>"/></label>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid air-service-class">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select service class') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_service_class">
                        <?php foreach ($list_service_class as $service_class) {
                            ?>
                            <label class="checked"

                                   data-tsmart_service_class_id="<?php echo $service_class->tsmart_service_class_id ?>"><?php echo $service_class->service_class_name ?>
                                <input name="service_class[]" type="checkbox"
                                       value="<?php echo $service_class->tsmart_service_class_id ?>"/></label>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row-fluid air-departure">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select departure item') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_departure">
                        <?php foreach ($list_departure as $departure) {
                            ?>

                            <div class="row-fluid departure-item"
                                 data-tsmart_departure_id="<?php echo $departure->tsmart_departure_id ?>">
                                <div class="span6">
                                    <label>Departure:<?php echo JHtml::_('date', $departure->departure_date, tsmConfig::$date_format); ?>
                                        <input type="checkbox" value="<?php echo $departure->tsmart_departure_id ?>"
                                               name="departure[]"></label></div>
                                <div class="span2"></div>
                                <div class="span4">Code:<?php echo $departure->departure_code ?></div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_city($name="tsmart_cityarea_id", $list_city, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $state_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_city/html_select_city.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_city/html_select_city.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_city)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcities.php';
            $list_city = tsmcities::get_cities();
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_city({
                    element_name:"<?php echo  $name ?>",
                    state_element: '<?php echo $state_element ?>',
                    default:<?php echo $default ?>

                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);


        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $list_city = array_merge(array($option), $list_city);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';

        }
        return VmHtml::genericlist($list_city, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }

    public static function select_percent_amount($type_name, $amount_name, $type, $amount)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_percent_amount/html_select_percent_amount.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_percent_amount/html_select_percent_amount.less');
        $input = JFactory::getApplication()->input;
        $percent_amount_id = 'percent_amount_' . $type_name . '_' . $amount_name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $percent_amount_id ?>').html_select_percent_amount({
                    type_name: '<?php echo $type_name ?>',
                    amount_name: '<?php echo $amount_name ?>',
                    'percent_input': 'percent_input'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $percent_amount_id ?>">
            <div class="row-fluid">
                <div class="span6">
                    <input type="text" class="auto percent_input" <?php echo $type == 'amount' ? 'disabled' : '' ?>
                           value="<?php echo $type == 'percent' ? $amount : '' ?>" data-a-sign="%" data-v-min="0"
                           data-v-max="100" placeholder="write percent">
                </div>
                <div class="span6">
                    <input type="text" class="auto amount_input" <?php echo $type == 'percent' ? 'disabled' : '' ?>
                           value="<?php echo $type == 'amount' ? $amount : '' ?>" data-v-min="0" data-v-max="9999"
                           placeholder="write amount">
                </div>
                <input type="hidden" value="<?php echo $type ?>" name="<?php echo $type_name ?>">
                <input type="hidden" value="<?php echo $amount ?>" name="<?php echo $amount_name ?>">
            </div>
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function select_from_to($from_name, $to_name, $from = 0, $to = 10, $min = 0, $max = 100)
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css");
        $doc->addScript(JUri::root() . '/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');

        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_from_to/html_select_from_to.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_from_to/html_select_from_to.less');
        $input = JFactory::getApplication()->input;
        $id_select_from_to = 'select_from_to_' . $from_name . '_' . $to_name;
        $id_select_from_to = str_replace(array("[", "]"), "_", $id_select_from_to);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_select_from_to ?>').html_select_from_to({
                    from_name: "<?php echo $from_name ?>",
                    to_name: "<?php echo $to_name ?>",
                    from:<?php echo (int)$from ?>,
                    to:<?php echo (int)$to ?>,
                    min:<?php echo (int)$min ?>,
                    max:<?php echo (int)$max ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_select_from_to ?>">
            <div class="integer-range"></div>
            <input type="hidden" value="<?php echo $from ?>" name="<?php echo $from_name ?>">
            <input type="hidden" value="<?php echo $to ?>" name="<?php echo $to_name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function range_of_date($from_name, $to_name, $from_date = '', $to_date = '', $format = 'YYYY-MM-DD', $min_date = '', $max_date = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');

        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_range_of_date/html_select_range_of_date.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_range_of_date/html_select_range_of_date.less');
        $input = JFactory::getApplication()->input;
        $select_from_date_to_date = 'select_from_date_to_date_' . $from_name . '_' . $to_name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_from_date_to_date ?>').html_select_range_of_date({
                    format: "<?php echo $format ?>",
                    from_name: "<?php echo $from_name ?>",
                    to_name: "<?php echo $to_name ?>",
                    from_date: "<?php echo $from_date ?>",
                    to_date: "<?php echo $to_date ?>",
                    min_date: "<?php echo $min_date ?>",
                    max_date: "<?php echo $max_date ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $select_from_date_to_date ?>" class="select_range_of_date">
            <div class="input-append ">
                <input type="text" value="<?php echo $from_date ?>-<?php echo $to_date ?>" class="range_of_date"/>
                <span class="icon-calendar add-on"></span>
            </div>


            <input type="hidden" value="<?php echo $from_date ?>" name="<?php echo $from_name ?>">
            <input type="hidden" value="<?php echo $to_date ?>" name="<?php echo $to_name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function select_date($name, $value_selected = '', $format = 'mm/dd/yy', $view_format = 'mm/dd/yy', $min_date = '', $max_date = '', $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.maskedinput-master/dist/jquery.maskedinput.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_date/html_select_date.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_date/html_select_date.less');
        $input = JFactory::getApplication()->input;
        $select_date = 'select_date_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_date ?>').html_select_date({
                    format: "<?php echo $format ? $format : 'mm/dd/yy' ?>",
                    view_format: "<?php echo $view_format ? $view_format : 'mm/dd/yy' ?>",
                    input_name: "<?php echo $name ?>",
                    value_selected: "<?php echo $value_selected ?>",
                    min_date: "<?php echo $min_date ?>",
                    max_date: "<?php echo $max_date ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $select_date ?>" class="select_date">
            <div class="input-append ">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_date_picker_<?php echo $name ?>" class="select_date <?php echo $class ?>"/>
                <span class="icon-calendar add-on"></span>
            </div>
            <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function select_month($name, $value_selected = '', $format = 'MM/YYYY', $view_format = 'MM/YYYY', $min_month = 1, $max_month = 12, $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');

        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/button.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/monthpicker.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-ui-month-picker-master/src/MonthPicker.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_month/html_select_month.js');
        $doc->addLessStyleSheet(JUri::root() . '/media/system/js/jquery-ui-month-picker-master/src/MonthPicker.css');
        $input = JFactory::getApplication()->input;
        $select_month = 'select_month_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_month ?>').html_select_month({
                    format: "<?php echo $format ? $format : 'MM/YYYY' ?>",
                    view_format: "<?php echo $view_format ? $view_format : 'MM/YYYY' ?>",
                    input_name: "<?php echo $name ?>",
                    value_selected: "<?php echo $value_selected ?>",
                    min_month: "<?php echo $min_month ?>",
                    max_month: "<?php echo $max_month ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $select_month ?>" class="select_month">
            <div class="input-append ">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_month_picker_<?php echo $name ?>" class="select_month <?php echo $class ?>"/>
                <span class="icon-calendar add-on"></span>
            </div>
            <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function edit_price_add_on($name, $data = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/base64.js');
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");

        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_add_on/html_edit_price_add_on.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_add_on/html_edit_price_add_on.less');
        $input = JFactory::getApplication()->input;
        $edit_price_add_on = 'edit_price_add_on_' . $name;
        $data1 = base64_decode($data);

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $data1 = up_json_decode($data1, false, 512, JSON_PARSE_JAVASCRIPT);
        $items = $data1->items;
        for ($i = 0; $i < count($items); $i++) {
            if ($i > 19) {
                unset($data1->items[$i]);
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $edit_price_add_on ?>').html_edit_price_add_on({
                    output_name: "<?php echo $name ?>",
                    <?php if($data1){ ?>
                    data:<?php echo json_encode($data1) ?>
                    <?php } ?>


                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $edit_price_add_on ?>" class="edit-price-add-on">
            <div class="pull-right">
                <button type="button" class="btn btn-small btn-success more-group"><span
                        class="icon-new icon-white"></span>More group
                </button>
                <button type="button" class="btn btn-small btn-success delete-group"><span
                        class="icon-delete icon-white"></span>Delete last group
                </button>
                <button type="button" class="btn btn-small btn-success reset-all"><span
                        class="icon-reset icon-white"></span>Reset all
                </button>
            </div>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Net Price</th>
                    <th>Mark up %</th>
                    <th>Mark up amount</th>
                    <th>Tax</th>
                    <th>Sale price</th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 0; $i < 2; $i++) { ?>
                    <tr class="<?php echo $i < 1 ? 'item' : 'item-flat' ?>">
                        <td><span class="index"><?php if ($i < 1){ ?><?php echo($i + 1) ?></span>
                            Person<?php } else { ?>Flat price<?php } ?></td>
                        <td><input type="text" class="auto net-price" data-a-sign="US$ " placeholder="US$"
                                   data-v-min="0" data-v-max="999999"></td>
                        <td><input type="text" class="auto mark-up-percent" data-a-sign="%" placeholder="%"
                                   data-v-min="0" data-v-max="100"></td>
                        <td><input type="text" class="auto mark-up-amount" data-a-sign="US$ " placeholder="US$"></td>
                        <td><input type="text" class="auto tax" data-a-sign="%" placeholder="%" data-v-min="0"
                                   data-v-max="100"></td>
                        <td><input type="text" class="auto sale-price" readonly data-a-sign="US$ " placeholder="US$">
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Children discount</td>
                    <td><input type="text" class="auto children-discount-amount" data-a-sign="US$ " placeholder="US$"
                               data-v-min="0" data-v-max="999999"></td>
                    <td><input type="text" class="auto children-discount-percent" data-a-sign="%" placeholder="%"
                               data-v-min="0" data-v-max="100"></td>
                    <td>Children age</td>
                    <td colspan="2"><input type="text" class="auto children-under-year" data-v-min="0" data-v-max="120">
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="<?php echo $name ?>" value="<?php echo $data ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function edit_price_hotel_add_on($name, $data = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/base64.js');
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");

        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_hotel_add_on/html_edit_price_hotel_add_on.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_hotel_add_on/html_edit_price_hotel_add_on.less');
        $input = JFactory::getApplication()->input;
        $edit_price_add_on = 'edit_price_hotel_add_on_' . $name;
        $data1 = base64_decode($data);

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $data1 = up_json_decode($data1, false, 512, JSON_PARSE_JAVASCRIPT);
        $items = $data1->items;
        for ($i = 0; $i < count($items); $i++) {
            if ($i > 19) {
                unset($data1->items[$i]);
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $edit_price_add_on ?>').html_edit_price_hotel_add_on({
                    output_name: "<?php echo $name ?>",
                    <?php if($data1){ ?>
                    data:<?php echo json_encode($data1) ?>
                    <?php } ?>


                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $list_room = array(
            'single_room' => 'Single room',
            'double_twin_room' => 'Double/twin room',
            'triple_room' => 'Triple room'

        );
        ob_start();
        ?>
        <div id="<?php echo $edit_price_add_on ?>" class="edit-price-hotel-add-on">
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Net Price</th>
                    <th>Mark up %</th>
                    <th>Mark up amount</th>
                    <th>Tax</th>
                    <th>Sale price</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list_room as $key => $room) { ?>
                    <tr class="item" data-key-room="<?php echo $key ?>">
                        <td><span class="index"><?php echo $room ?></td>
                        <td><input type="text" class="auto net-price" data-a-sign="US$ " placeholder="US$"
                                   data-v-min="0" data-v-max="999999"></td>
                        <td><input type="text" class="auto mark-up-percent" data-a-sign="%" placeholder="%"
                                   data-v-min="0" data-v-max="100"></td>
                        <td><input type="text" class="auto mark-up-amount" data-a-sign="US$ " placeholder="US$"></td>
                        <td><input type="text" class="auto tax" data-a-sign="%" placeholder="%" data-v-min="0"
                                   data-v-max="100"></td>
                        <td><input type="text" class="auto sale-price" readonly data-a-sign="US$ " placeholder="US$">
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="<?php echo $name ?>" value="<?php echo $data ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    public static function select_amount_percent($amount_name, $percent_name, $amount, $percent)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_amount_percent/html_select_amount_percent.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_amount_percent/html_select_amount_percent.less');
        $input = JFactory::getApplication()->input;
        $amount_percent_id = 'amount_percent_' . $amount_name . '_' . $percent_name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $amount_percent_id ?>').html_select_amount_percent({
                    amount_name: '<?php echo $amount_name ?>',
                    percent_name: '<?php echo $percent_name ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $amount_percent_id ?>">
            <div class="row-fluid">
                <div class="span6">
                    <input type="text" value="<?php echo $amount ?>" class="auto amount_input" data-v-min="0"
                           data-v-max="9999" placeholder="write of No day">
                </div>
                <div class="span6">
                    <input type="text" value="<?php echo $percent ?>" class="auto percent_input" data-a-sign="%"
                           data-v-min="0" data-v-max="100" placeholder="write percent">
                    <input type="hidden" name="<?php echo $amount_name ?>" value="<?php echo $amount ?>">
                    <input type="hidden" name="<?php echo $percent_name ?>" value="<?php echo $percent ?>">
                </div>
            </div>
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }

    /**
     *
     * @author Patrick Kohl
     * @param array $options ( value & text)
     * @param string $name option name
     * @param string $defaut defaut value
     * @param string $key option value
     * @param string $text option text
     * @param boolean $zero add  a '0' value in the option
     * return a select list
     */
    public static function select_add_on($name, $options, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $iframe_link, $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_add_on.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_add_on.less');
        $input = JFactory::getApplication()->input;
        $reload_iframe_id = $input->get('iframe_id', '', 'string');
        $reload_ui_dialog_id = $input->get('ui_dialog_id', '', 'string');
        $remove_ui_dialog = $input->get('remove_ui_dialog', false, 'boolean');
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $options);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';

        }
        $select = VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
        $doc = JFactory::getDocument();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#div_<?php echo $name ?>').html_select_add_on({
                    id_field_edit_content_wrapper: 'field_edit_content_wrapper_<?php echo $name ?>',
                    iframe_link: '<?php echo $iframe_link ?>',
                    link_reload: '<?php echo base64_encode(JUri::getInstance()->toString()) ?>',
                    ui_dialog_id: 'dialog_content_<?php echo $name ?>',
                    iframe_id: 'iframe_<?php echo $name ?>',
                    reload_iframe_id: '<?php echo $reload_iframe_id ?>',
                    reload_ui_dialog_id: '<?php echo $reload_ui_dialog_id ?>',
                    remove_ui_dialog: '<?php echo json_encode($remove_ui_dialog) ?>'


                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>

        <div id="div_<?php echo $name ?>" class="html_select_add_on">
            <div class="input-append ">
                <div id="field_edit_content_wrapper_<?php echo $name ?>" style="display: none">
                    <iframe id="vm-iframe_<?php echo $name ?>" scrolling="no" src=""></iframe>
                </div>
                <?php echo $select ?>
                <button class="btn edit_content btn_<?php echo $name ?>" type="button"><span class="icon-plus"></span>
                </button>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function list_checkbox($name, $options, $list_selected = array(), $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $column = 3)
    {
        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <?php foreach ($list_options as $options) { ?>
        <div class="row-fluid">
            <?php foreach ($options as $option) { ?>
                <div class="span<?php echo round(12 / $column) ?>">
                    <label class="checkbox">
                        <input
                            name="<?php echo $name ?>[]" <?php echo in_array($option->$key, $list_selected) ? 'checked' : '' ?>
                            value="<?php echo $option->$key ?>" type="checkbox"> <?php echo $option->$text ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function list_checkbox_group_size($name, $list_selected = array(), $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/list_checkbox_group_size/html_list_checkbox_group_size.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/list_checkbox_group_size/html_list_checkbox_group_size.less');

        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmgroupsize.php';

        $tour_group_size = tsmGroupSize::get_list_group_size();

        $id_list_checkbox_group_size = 'list_checkbox_group_size_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_list_checkbox_group_size ?>').html_list_checkbox_group_size({
                    list_selected:<?php echo json_encode($list_selected) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();

        $html = '';
        $list_list_group_size = array_chunk($tour_group_size, $column);
        ob_start();
        ?>
        <div id="<?php echo $id_list_checkbox_group_size ?>" class="html_list_checkbox_group_size">
            <?php foreach ($list_list_group_size as $list_group_size) { ?>
                <div class="row-fluid">
                    <?php foreach ($list_group_size as $group_size) { ?>
                        <div class="span<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>[]" data-from="<?php echo $group_size->from ?>"
                                    data-to="<?php echo $group_size->to ?>" <?php echo in_array($group_size->tsmart_group_size_id, $list_selected) ? 'checked' : '' ?>
                                    value="<?php echo $group_size->tsmart_group_size_id ?>"
                                    type="checkbox"> <?php echo $group_size->group_name ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function list_radio($name, $options, $selected = 0, $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $id = "list-radio-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("<?php echo "#$id" ?>").find('input[name="<?php echo $name ?>"]').checkator({});
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);


        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <div id="<?php echo $id ?>" class="list-radio-box">
            <?php foreach ($list_options as $options) { ?>
                <div class="row-fluid">
                    <?php foreach ($options as $option) { ?>
                        <div class="span<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                    value="<?php echo $option->$key ?>" type="radio">
                                <br/>
                                <?php echo $option->$text ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function list_radio_price_type($name, $selected = 0, $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmprice.php';
        $options = tsmprice::get_list_price_type();

        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $id = "list-radio-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("<?php echo "#$id" ?>").find('input[name="<?php echo $name ?>"]').checkator({});
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $key = 'value';
        $text = 'text';

        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <div id="<?php echo $id ?>" class="list-radio-box">
            <?php foreach ($list_options as $options) { ?>
                <div class="row-fluid">
                    <?php foreach ($options as $option) { ?>
                        <div class="span<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                    value="<?php echo $option->$key ?>" type="radio">
                                <br/>
                                <?php echo $option->$text ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function view_list_tag($list_tag)
    {
        $html = '';
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/plugins/tag/style.less');
        ob_start();
        ?>
        <div class="tags">
            <?php foreach ($list_tag as $tag) { ?>
                <a class="tag-body yellow" href="javascript:void(0)"><span class="tag"><?php echo $tag ?></span></a>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function view_list($list_tag)
    {
        $html = '';
        $doc = JFactory::getDocument();
        ob_start();
        ?>
        <ul>
            <?php foreach ($list_tag as $tag) { ?>
                <li><?php echo $tag ?></li>
            <?php } ?>
        </ul>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function group_price($items = array(), $tranlsate = true, $column = 3)
    {
        $list_items = array_chunk($items, $column);
        ob_start();
        ?>
        <?php foreach ($list_items as $items) { ?>
        <div class="row">
            <?php foreach ($items as $item) { ?>
                <div class="span<?php echo round(12 / $column) ?>">
                    <label class="input">
                        <?php echo $item->text ?> <input style="width: 50px" name="<?php echo $item->name ?>"
                                                         value="<?php echo $item->value ?>">
                    </label>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function list_option($name, $options, $list_selected = array(), $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jQuery-Plugin-For-Bootstrap-Button-Group-Toggles/select-toggleizer.js');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').toggleize({});
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $attrib .= ' disable_chosen="true" ';
        $html = VmHtml::genericlist($options, $name, $attrib, $key, $text, $list_selected, false, $tranlsate);
        return $html;
    }

    /**
     * Generates an HTML selection list.
     * @author Joomla 2.5.14
     * @param   array $data An array of objects, arrays, or scalars.
     * @param   string $name The value of the HTML name attribute.
     * @param   mixed $attribs Additional HTML attributes for the <select> tag. This
     *                               can be an array of attributes, or an array of options. Treated as options
     *                               if it is the last argument passed. Valid options are:
     *                               Format options, see {@see JHtml::$formatOptions}.
     *                               Selection options, see {@see JHtmlSelect::options()}.
     *                               list.attr, string|array: Additional attributes for the select
     *                               element.
     *                               id, string: Value to use as the select element id attribute.
     *                               Defaults to the same as the name.
     *                               list.select, string|array: Identifies one or more option elements
     *                               to be selected, based on the option key values.
     * @param   string $optKey The name of the object variable for the option value. If
     *                               set to null, the index of the value array is used.
     * @param   string $optText The name of the object variable for the option text.
     * @param   mixed $selected The key that is selected (accepts an array or a string).
     * @param   mixed $idtag Value of the field id or null by default
     * @param   boolean $translate True to translate
     *
     * @return  string  HTML for the select list.
     *
     * @since   11.1
     */
    public static function genericlist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
                                       $translate = false)
    {
        // Set default options
        $options = array_merge(JHtml::$formatOptions, array('format.depth' => 0, 'id' => false));
        if (is_array($attribs) && func_num_args() == 3) {
            // Assume we have an options array
            $options = array_merge($options, $attribs);
        } else {
            // Get options from the parameters
            $options['id'] = $idtag;
            $options['list.attr'] = $attribs;
            $options['list.translate'] = $translate;
            $options['option.key'] = $optKey;
            $options['option.text'] = $optText;
            $options['list.select'] = $selected;
        }
        $attribs = '';
        if (isset($options['list.attr'])) {
            if (is_array($options['list.attr'])) {
                $attribs = JArrayHelper::toString($options['list.attr']);
            } else {
                $attribs = $options['list.attr'];
            }
            if ($attribs != '') {
                $attribs = ' ' . $attribs;
            }
        }

        $id = $options['id'] !== false ? $options['id'] : $name;
        $id = str_replace(array('[', ']'), '', $id);

        $baseIndent = str_repeat($options['format.indent'], $options['format.depth']++);
        $html = $baseIndent . '<select' . ($id !== '' ? ' id="' . $id . '"' : '') . ' name="' . $name . '"' . $attribs . '>' . $options['format.eol']
            . self::options($data, $options) . $baseIndent . '</select>' . $options['format.eol'];
        return $html;
    }

    /**
     * Generates the option tags for an HTML select list (with no select tag
     * surrounding the options).
     * @author Joomla 2.5.14
     * @param   array $arr An array of objects, arrays, or values.
     * @param   mixed $optKey If a string, this is the name of the object variable for
     *                               the option value. If null, the index of the array of objects is used. If
     *                               an array, this is a set of options, as key/value pairs. Valid options are:
     *                               -Format options, {@see JHtml::$formatOptions}.
     *                               -groups: Boolean. If set, looks for keys with the value
     *                                "&lt;optgroup>" and synthesizes groups from them. Deprecated. Defaults
     *                                true for backwards compatibility.
     *                               -list.select: either the value of one selected option or an array
     *                                of selected options. Default: none.
     *                               -list.translate: Boolean. If set, text and labels are translated via
     *                                vmText::_(). Default is false.
     *                               -option.id: The property in each option array to use as the
     *                                selection id attribute. Defaults to none.
     *                               -option.key: The property in each option array to use as the
     *                                selection value. Defaults to "value". If set to null, the index of the
     *                                option array is used.
     *                               -option.label: The property in each option array to use as the
     *                                selection label attribute. Defaults to null (none).
     *                               -option.text: The property in each option array to use as the
     *                               displayed text. Defaults to "text". If set to null, the option array is
     *                               assumed to be a list of displayable scalars.
     *                               -option.attr: The property in each option array to use for
     *                                additional selection attributes. Defaults to none.
     *                               -option.disable: The property that will hold the disabled state.
     *                                Defaults to "disable".
     *                               -option.key: The property that will hold the selection value.
     *                                Defaults to "value".
     *                               -option.text: The property that will hold the the displayed text.
     *                               Defaults to "text". If set to null, the option array is assumed to be a
     *                               list of displayable scalars.
     * @param   string $optText The name of the object variable for the option text.
     * @param   mixed $selected The key that is selected (accepts an array or a string)
     * @param   boolean $translate Translate the option values.
     *
     * @return  string  HTML for the select list
     *
     * @since   11.1
     */
    public static function options($arr, $optKey = 'value', $optText = 'text', $selected = null, $translate = false)
    {
        $options = array_merge(
            JHtml::$formatOptions,
            self::$_optionDefaults['option'],
            array('format.depth' => 0, 'groups' => true, 'list.select' => null, 'list.translate' => false)
        );

        if (is_array($optKey)) {
            // Set default options and overwrite with anything passed in
            $options = array_merge($options, $optKey);
        } else {
            // Get options from the parameters
            $options['option.key'] = $optKey;
            $options['option.text'] = $optText;
            $options['list.select'] = $selected;
            $options['list.translate'] = $translate;
        }

        $html = '';
        $baseIndent = str_repeat($options['format.indent'], $options['format.depth']);

        foreach ($arr as $elementKey => &$element) {
            $attr = '';
            $extra = '';
            $label = '';
            $id = '';
            if (is_array($element)) {
                $key = $options['option.key'] === null ? $elementKey : $element[$options['option.key']];
                $text = $element[$options['option.text']];
                if (isset($element[$options['option.attr']])) {
                    $attr = $element[$options['option.attr']];
                }
                if (isset($element[$options['option.id']])) {
                    $id = $element[$options['option.id']];
                }
                if (isset($element[$options['option.label']])) {
                    $label = $element[$options['option.label']];
                }
                if (isset($element[$options['option.disable']]) && $element[$options['option.disable']]) {
                    $extra .= ' disabled="disabled"';
                }
            } elseif (is_object($element)) {
                $key = $options['option.key'] === null ? $elementKey : $element->$options['option.key'];
                $text = $element->$options['option.text'];
                if (isset($element->$options['option.attr'])) {
                    $attr = $element->$options['option.attr'];
                }
                if (isset($element->$options['option.id'])) {
                    $id = $element->$options['option.id'];
                }
                if (isset($element->$options['option.label'])) {
                    $label = $element->$options['option.label'];
                }
                if (isset($element->$options['option.disable']) && $element->$options['option.disable']) {
                    $extra .= ' disabled="disabled"';
                }
            } else {
                // This is a simple associative array
                $key = $elementKey;
                $text = $element;
            }

            // The use of options that contain optgroup HTML elements was
            // somewhat hacked for J1.5. J1.6 introduces the grouplist() method
            // to handle this better. The old solution is retained through the
            // "groups" option, which defaults true in J1.6, but should be
            // deprecated at some point in the future.

            $key = (string)$key;

            // if no string after hyphen - take hyphen out
            $splitText = explode(' - ', $text, 2);
            $text = $splitText[0];
            if (isset($splitText[1])) {
                $text .= ' - ' . $splitText[1];
            }

            if ($options['list.translate'] && !empty($label)) {
                $label = tsmText::_($label);
            }
            if ($options['option.label.toHtml']) {
                $label = htmlentities($label);
            }
            if (is_array($attr)) {
                $attr = JArrayHelper::toString($attr);
            } else {
                $attr = trim($attr);
            }
            $extra = ($id ? ' id="' . $id . '"' : '') . ($label ? ' label="' . $label . '"' : '') . ($attr ? ' ' . $attr : '') . $extra;
            if (is_array($options['list.select'])) {
                foreach ($options['list.select'] as $val) {
                    $key2 = is_object($val) ? $val->$options['option.key'] : $val;
                    if ($key == $key2) {
                        $extra .= ' selected="selected"';
                        break;
                    }
                }
            } elseif ((string)$key == (string)$options['list.select']) {
                $extra .= ' selected="selected"';
            }

            if ($options['list.translate']) {
                $text = tsmText::_($text);
            }

            // Generate the option, encoding as required
            $html .= $baseIndent . '<option value="' . ($options['option.key.toHtml'] ? htmlspecialchars($key, ENT_COMPAT, 'UTF-8') : $key) . '"'
                . $extra . '>';
            $html .= $options['option.text.toHtml'] ? htmlentities(html_entity_decode($text, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8') : $text;
            $html .= '</option>' . $options['format.eol'];

        }

        return $html;
    }

    /**
     * Prints an HTML dropdown box named $name using $arr to
     * load the drop down.  If $value is in $arr, then $value
     * will be the selected option in the dropdown.
     * @author gday
     * @author soeren
     *
     * @param string $name The name of the select element
     * @param string $value The pre-selected value
     * @param array $arr The array containing $key and $val
     * @param int $size The size of the select element
     * @param string $multiple use "multiple=\"multiple\" to have a multiple choice select list
     * @param string $extra More attributes when needed
     * @return string HTML drop-down list
     */
    static function selectList($name, $value, $arrIn, $size = 1, $multiple = "", $extra = "", $data_placeholder = '')
    {

        $html = '';
        if (empty($arrIn)) {
            $arr = array();
        } else {
            if (!is_array($arrIn)) {
                $arr = array($arrIn);
            } else {
                $arr = $arrIn;
            }
        }
        if (!empty($data_placeholder)) {
            $data_placeholder = 'data-placeholder="' . tsmText::_($data_placeholder) . '"';
        }

        $html = '<select class="inputbox" id="' . $name . '" name="' . $name . '" size="' . $size . '" ' . $multiple . ' ' . $extra . ' ' . $data_placeholder . ' >';

        while (list($key, $val) = each($arr)) {
//		foreach ($arr as $key=>$val){
            $selected = "";
            if (is_array($value)) {
                if (in_array($key, $value)) {
                    $selected = 'selected="selected"';
                }
            } else {
                if (strtolower($value) == strtolower($key)) {
                    $selected = 'selected="selected"';
                }
            }

            $html .= '<option value="' . $key . '" ' . $selected . '>' . self::shopMakeHtmlSafe($val);
            $html .= '</option>';

        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Creates a Radio Input List
     *
     * @param string $name
     * @param string $value default value
     * @param string $arr
     * @param string $extra
     * @return string
     */
    static function radioList($name, $value, &$arr, $extra = "", $separator = '<br />')
    {
        $html = '';
        if (empty($arr)) {
            $arr = array();
        }
        $html = '';
        $i = 0;
        foreach ($arr as $key => $val) {
            $checked = '';
            if (is_array($value)) {
                if (in_array($key, $value)) {
                    $checked = 'checked="checked"';
                }
            } else {
                if (strtolower($value) == strtolower($key)) {
                    $checked = 'checked="checked"';
                }
            }
            $html .= '<input type="radio" name="' . $name . '" id="' . $name . $i . '" value="' . htmlspecialchars($key, ENT_QUOTES) . '" ' . $checked . ' ' . $extra . " />\n";
            $html .= '<label for="' . $name . $i++ . '">' . $val . "</label>" . $separator . "\n";
        }

        return $html;
    }

    /**
     * Creates radio List
     * @param array $radios
     * @param string $name
     * @param string $default
     * @return string
     */
    static function radio($name, $radios, $default, $key = 'value', $text = 'text')
    {
        return '<fieldset class="radio">' . JHtml::_('select.radiolist', $radios, $name, '', $key, $text, $default) . '</fieldset>';
    }

    /**
     * Creating rows with boolean list
     *
     * @author Patrick Kohl
     * @param string $label
     * @param string $name
     * @param string $value
     *
     */
    public static function booleanlist($name, $value, $class = 'class="inputbox"')
    {
        return '<fieldset class="radio">' . JHtml::_('select.booleanlist', $name, $class, $value) . '</fieldset>';
    }

    /**
     * Creating rows with boolean list
     *
     * @author Patrick Kohl
     * @param string $label
     * @param string $name
     * @param string $value
     *
     */
    public static function activelist($name, $value, $class = 'class="inputbox"')
    {
        return JHtml::_('select.booleanlist', $name, $class, $value,JText::_('Active'),JText::_('Unactive')) ;
    }
    public static function bootstrap_activelist($name, $value, $class = 'class="inputbox"')
    {
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/js/controller/bootstrap_activelist/bootstrap_activelist.less');
        $doc->addScript(JUri::root().'administrator/components/com_tsmart/assets/js/controller/bootstrap_activelist/bootstrap_activelist.js');
        $doc->addScript(JUri::root().'administrator/components/com_tsmart/assets/js/plugin/icheck-1.x/icheck.js');
        $doc->addStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/js/plugin/icheck-1.x/skins/all.css');

        ob_start();
        $id_element='bootstrap_activelist_'.$name;
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/utility.php';
        $id_element=TSMUtility::clean($id_element);
        $clean_name=TSMUtility::clean($name);

        ?>
        <div id="<?php echo $id_element ?>" class="bootstrap_activelist">
            <ul class="list">
                <li>
                    <input type="radio" id="<?php echo $clean_name ?>-minimal-radio-1" name="<?php echo $name ?>" value="<?php echo $value ?>" <?php echo $value==false?'checked':'' ?>>
                    <label for="<?php echo $clean_name ?>-minimal-radio-1"><?php echo JText::_('Active') ?></label>
                </li>
                <li>
                    <input  type="radio" id="<?php echo $clean_name ?>-minimal-radio-2" name="<?php echo $name ?>"  <?php echo $value==true?'checked':'' ?> >
                    <label for="<?php echo $clean_name ?>-minimal-radio-2"><?php echo JText::_('Unactive') ?></label>
                </li>
            </ul>
        </div>
        <?php
        $html=ob_get_clean();

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').bootstrap_activelist({

                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);





        return $html;
    }

    /**
     * Creating rows with input fields
     *
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function input($name, $value, $class = '', $readonly = false, $size = '', $maxlength = '255', $more = '')
    {
        return '<input type="text" ' . $readonly . ' ' . $class . ' id="' . $name . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '" value="' . ($value) . '" />' . $more;
    }

    public static function password($name, $value, $class = '', $readonly = false, $size = '30', $maxlength = '255', $more = '')
    {
        return '<input type="password" ' . $readonly . ' ' . $class . ' id="' . $name . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '" value="' . ($value) . '" />' . $more;
    }

    public static function input_percent($name, $value, $class = 'inputbox', $readonly = '', $min = 0, $max = 100, $more = '')
    {

        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.input_percent_<?php echo $name ?>').autoNumeric('init').change(function () {
                    var value_of_this = $(this).autoNumeric('get');
                    $('input[name="<?php echo $name ?>"]').val(value_of_this);
                });

            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <input type="text" value="<?php echo $value ?>" <?php echo $readonly ? 'readonly' : '' ?>
               class="inputbox <?php echo $class ?>   input_percent_<?php echo $name ?>" data-v-min="<?php echo $min ?>"
               data-v-max="<?php echo $max ?>" data-a-sign="%">
        <input type="hidden" value="<?php echo $value ?>" name="<?php echo $name ?>" id="<?php echo $name ?>">
        <?php
        return ob_get_clean();
    }

    public static function input_button($name, $value, $type = "submit", $size_class = "btn-large", $class_type = "btn-primary", $attr = array())
    {

        $doc = JFactory::getDocument();
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <button type="<?php echo $type ?>" name="<?php echo $name ?>"
                class="btn <?php echo $class_type ?> <?php echo $size_class ?>"><?php echo $value ?></button>
        <?php
        return ob_get_clean();
    }

    public static function input_number($name, $value, $class = 'inputbox', $readonly = '', $min = 0, $max = 100, $more = '', $option = array())
    {

        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.input_number_<?php echo $name ?>').autoNumeric('init',<?php echo json_encode($option) ?>).change(function () {
                    var value_of_this = $(this).autoNumeric('get');
                    $('input[name="<?php echo $name ?>"]').val(value_of_this).trigger("change");
                    ;
                });

            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <input type="text" value="<?php echo $value ?>" <?php echo $readonly ? 'readonly' : '' ?>
               class="inputbox <?php echo $class ?>   input_number_<?php echo $name ?>" data-v-min="<?php echo $min ?>"
               data-v-max="<?php echo $max ?>">
        <input type="hidden" value="<?php echo $value ?>" name="<?php echo $name ?>" id="<?php echo $name ?>">
        <?php
        return ob_get_clean();
    }

    public static function input_add_on($name, $value, $class = 'class="inputbox"', $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        $input = '<input type="text" ' . $readonly . ' ' . $class . ' id="' . $name . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '" value="' . ($value) . '" />' . $more;
        ob_start();
        ?>
        <div class="input-append">
            <?php echo $input ?>
            <button class="btn" type="button"><span class="icon-plus"></span></button>
        </div>

        <!-- /input-group -->

        <?php
        $html = ob_get_clean();
        return $html;

    }

    public static function google_map($latitude_name, $longitude_name, $location_name, $radius_name, $latitude = 0, $longitude = 0, $location = '', $radius = 0, $class = 'class="inputbox"', $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        $doc->addScript('http://maps.google.com/maps/api/js?sensor=false&libraries=places');
        $doc->addScript(JUri::root() . 'media/system/js/jquery-locationpicker-plugin-master/src/locationpicker.jquery.js');

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $latitude_name ?>-<?php echo $longitude_name ?>-map').locationpicker({
                    location: {latitude: <?php echo (int)$latitude ?>, longitude: <?php echo (int)$longitude ?>},
                    radius: 300,
                    inputBinding: {
                        latitudeInput: $('#<?php echo $latitude_name ?>'),
                        longitudeInput: $('#<?php echo $longitude_name ?>'),
                        radiusInput: $('#<?php echo $radius_name ?>'),
                        locationNameInput: $('#<?php echo $location_name ?>')
                    },
                    enableAutocomplete: true,
                    onchanged: function (currentLocation, radius, isMarkerDropped) {
                        $('input[name="<?php echo $latitude_name ?>"]').val(currentLocation.latitude);
                        $('input[name="<?php echo $longitude_name ?>"]').val(currentLocation.longitude);
                    }

                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        Location: <input type="text" id="<?php echo $location_name ?>" name="<?php echo $location_name ?>"
                         value="<?php echo $location ?>" style="width: 200px"/>
        Radius: <input type="text" value="<?php echo $radius ?>" id="<?php echo $radius_name ?>"
                       name="<?php echo $radius_name ?>"/>
        <div id="<?php echo $latitude_name ?>-<?php echo $longitude_name ?>-map"
             style="width: 100%; height: 400px;"></div>
        Lat.: <input type="text" name="<?php echo $latitude_name ?>" value="<?php echo $latitude ?>"
                     id="<?php echo $latitude_name ?>"/>
        Long.: <input type="text" name="<?php echo $longitude_name ?>" value="<?php echo $longitude ?>"
                      id="<?php echo $longitude_name ?>"/>

        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function text_view($value, $class, $more = '')
    {
        return '<input type="text" disabled class="text-view"    value="' . ($value) . '" />' . $more;
    }

    public static function textarea_view($name, $value, $attr = array(), $more = '')
    {
        $attr = implode(' , ', array_map(
            function ($v, $k) {
                return $k . '="' . $v . '"';
            },
            $attr,
            array_keys($attr)
        ));
        ob_start();
        ?>
        <textarea name="<?php echo $name ?>" readonly <?php echo $attr ?> ><?php echo $value ?></textarea>
        <?php
        $html = ob_get_clean();
        return $html . $more;
    }

    public static function text_view_from_to($from, $to, $text, $attr1, $attr1, $more = '')
    {
        return '<input type="text" ' . $attr1 . '  disabled class="text-view"    value="' . ($from) . '" /><span>' . $text . '</span><input type="text" ' . $attr1 . ' disabled class="text-view"    value="' . ($to) . '" />' . $more;
    }

    public static function text_view2($value, $class, $more = '')
    {
        return $value . $more;
    }


    public static function show_image($value, $class = 'class="inputbox"', $width = 40, $height = 40)
    {
        return '<img ' . $class . ' src="' . ($value) . '" width="' . $width . '" height="' . $height . '" />';
    }

    /**
     * Creating rows with input fields
     *
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function image($name, $value, $class = 'class="inputbox"', $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        require_once JPATH_ROOT . '/libraries/cms/form/field/media.php';
        $media_field = new JFormFieldMedia();
        $media_string = <<<XML

<field name="$name"  type="media" default="1" label="">
</field>

XML;

        $element_media = simplexml_load_string($media_string);
        $media_field->setup($element_media, $value, '');
        return $media_field->renderField();
    }

    /**
     * Creating rows with input fields
     *
     * @author Patrick Kohl
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function textarea($name, $value, $class = 'class="inputbox"', $cols = '100', $rows = "4")
    {
        return '<textarea ' . $class . ' id="' . $name . '" name="' . $name . '" cols="' . $cols . '" rows="' . $rows . '">' . $value . '</textarea >';
    }

    /**
     * render editor code
     *
     * @author Patrick Kohl
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function editor($name, $value, $size = '100%', $height = '10', $col = 10, $row = 10, $hide = array('pagebreak', 'readmore'))
    {
        $editor = JFactory::getEditor();
        return $editor->display($name, $value, $size, $height, $col, $row, $hide);
    }


    /**
     * renders the hidden input
     * @author Max Milbers
     */
    public static function inputHidden($values)
    {
        $html = '';
        foreach ($values as $k => $v) {
            $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
        }
        return $html;
    }

    /**
     * @author Patrick Kohl
     * @var $type type of regular Expression to validate
     * $type can be I integer, F Float, A date, M, time, T text, L link, U url, P phone
     * @bool $required field is required
     * @Int $min minimum of char
     * @Int $max max of char
     * @var $match original ID field to compare with this such as Email, passsword
     *@ Return $html class for validate javascript
     **/
    public static function validate($type = '', $required = true, $min = null, $max = null, $match = null)
    {

        if ($required) $validTxt = 'required';
        else $validTxt = 'optional';
        if (isset($min)) $validTxt .= ',minSize[' . $min . ']';
        if (isset($max)) $validTxt .= ',maxSize[' . $max . ']';
        static $validateID = 0;
        $validateID++;
        if ($type == 'S') return 'id="validate' . $validateID . '" class="validate[required,minSize[2],maxSize[255]]"';
        $validate = array('I' => 'onlyNumberSp', 'F' => 'number', 'D' => 'dateTime', 'A' => 'date', 'M' => 'time', 'T' => 'Text', 'L' => 'link', 'U' => 'url', 'P' => 'phone');
        if (isset ($validate[$type])) $validTxt .= ',custom[' . $validate[$type] . ']';
        $html = 'id="validate' . $validateID . '" class="validate[' . $validTxt . ']"';

        return $html;
    }

    public static function select_service_class($list_service_class = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_service_class/html_select_language.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_service_class/html_select_service_class.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_service_class)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
            $list_service_class = tsmserviceclass::get_list_service_class();
        }

        $id_element = 'html_select_service_class_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_service_class({
                    list_tour:<?php echo json_encode($list_service_class) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_language_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select Service class') ?></option>
                <?php foreach ($list_service_class as $service_class) { ?>
                    <option <?php echo $service_class->tsmart_service_class_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $service_class->tsmart_service_class_id ?>"><?php echo $service_class->service_class_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_currency($list_currency = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_currency/html_select_currency.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_currency/html_select_currency.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_currency)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcurrency.php';
            $list_currency = tsmcurrency::get_list_currency();
        }

        $id_element = 'html_select_service_class_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_currency({
                    list_tour:<?php echo json_encode($list_currency) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_language_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select currency') ?></option>
                <?php foreach ($list_currency as $currency) { ?>
                    <option <?php echo $currency->tsmart_currency_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $currency->tsmart_currency_id ?>"><?php echo $currency->currency_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_language($list_language = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_language/html_select_language.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_language/html_select_language.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_language)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmlanguage.php';
            $list_language = tsmlanguage::get_list_language();
        }

        $id_element = 'html_select_language_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_language({
                    list_language:<?php echo json_encode($list_language) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_language_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select Language') ?></option>
                <?php foreach ($list_language as $language) { ?>
                    <option <?php echo $language->tsmart_language_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $language->tsmart_language_id ?>"><?php echo $language->language_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_type_percent_or_amount($name, $default)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_type_percent_or_amount/html_select_type_percent_or_amount.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_type_percent_or_amount/html_select_type_percent_or_amount.less');
        $input = JFactory::getApplication()->input;

        $id_element = 'html_select_type_percent_or_amount_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_type_percent_or_amount({});
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please type percent or amount') ?></option>
                <option value="percent"><?php echo JText::_('percent') ?></option>
                <option value="amount"><?php echo JText::_('amount') ?></option>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function select_range_of_date($list_rang_of_date = array(), $name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_list_range_of_date/html_select_list_range_of_date.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_list_range_of_date/html_select_list_range_of_date.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_select_range_of_date_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_list_range_of_date({
                    list_range_of_date:<?php echo json_encode($list_rang_of_date) ?>,
                    select_name: "<?php echo $name ?>",
                    id_selected:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div class="html_select_list_range_of_date" id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select range of date') ?></option>
                <?php foreach ($list_rang_of_date as $range_of_date) { ?>
                    <option <?php echo $range_of_date->id == $default ? ' selected ' : '' ?>
                        value="<?php echo $range_of_date->$key ?>"><?php echo $range_of_date->$text ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function generate_code($name, $default = '0')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/generate_code/html_generate_code.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/generate_code/html_generate_code.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_generate_code_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_generate_code({});
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div class="html_generate_code" id="<?php echo $id_element ?>">
            <div class="row-fluid">
                <div class="span8">
                    <input name="<?php echo $name ?>" value="<?php echo $default ?>" type="text" class="code">
                </div>
                <div class="span4">
                    <button type="button" class="btn generate_code"><?php echo JText::_('Go') ?></button>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function input_passenger($list_passenger = array(), $name = '', $default = '0', $min_age = 0, $max_age = 99, $departure, $passenger_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/Create-A-Tooltip/js/jquery.tooltip.js');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');

        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/input_passenger/html_input_passenger.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/input_passenger/html_input_passenger.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/system/js/Create-A-Tooltip/css/tooltip.less');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/Create-A-Tooltip/css/tooltip.css');

        $model = tmsModel::getModel('country');

        $list_country = $model->getItemList();
        foreach ($list_country AS &$country) {
            $country->id = $country->tsmart_country_id;
            $country->text = $country->country_name;
        }
        $input = JFactory::getApplication()->input;
        $id_element = 'html_input_passenger';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_input_passenger({
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    min_age: <?php echo $min_age ?>,
                    max_age: <?php echo $max_age != 0 ? $max_age : 99 ?>,
                    debug: true,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    list_country:<?php echo json_encode($list_country) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="html_input_passenger">
            <div class="row-fluid">
                <div class="span10">
                    <div class="row-fluid person-type">
                        <div class="span10">
                            <h4 class="">
                                <span title=""
                                      class="travel-icon">n</span> <?php echo JText::_('SENIOR/ADULT/TEEN(12-99 years)') ?>
                                <button type="button" class="btn btn-primary auto-fill-date">auto fill data</button>
                            </h4>
                        </div>
                    </div>
                    <div class="row-fluid herder">
                        <div class="span1"></div>
                        <div class="span1"><?php echo JText::_('Gender') ?></div>
                        <div class="span1"><?php echo JText::_('First name') ?></div>
                        <div class="span1"><?php echo JText::_('Middle name') ?></div>
                        <div class="span1"><?php echo JText::_('Last name') ?></div>
                        <div class="span1"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1"><?php echo JText::_('Date of birth') ?></div>
                        <div class="span1"></div>
                        <div class="span1"></div>
                    </div>
                    <div class="input-passenger-list-passenger senior-adult-teen">
                        <div class="row-fluid item-passenger">
                            <div class="span1"><?php echo JText::_('Person ') ?><span class="passenger-index">1</span>
                            </div>
                            <div class="span1">
                                <select data-name="gender" ">
                                <option value="mr">Mr</option>
                                <option value="ms">Ms</option>
                                </select>
                            </div>
                            <div class="span1"><input required data-name="first_name"
                                                      placeholder="<?php echo JText::_('First name') ?>"
                                                      type="text"></div>
                            <div class="span1"><input data-name="middle_name"
                                                      placeholder="<?php echo JText::_('Middle name') ?>"
                                                      type="text"></div>
                            <div class="span1"><input required data-name="last_name"
                                                      placeholder="<?php echo JText::_('Last name') ?>"
                                                      type="text"></div>
                            <div class="span1"><input required data-name="nationality"
                                                      placeholder="<?php echo JText::_('Nationality') ?>"
                                                      type="text"></div>
                            <div class="span1"><input required class="date readonly" data-name="date_of_birth" readonly
                                                      placeholder="<?php echo JText::_('Date of birth') ?>"
                                                      type="text"></div>
                            <div class="span1">
                                <button type="button" class="btn remove"><span class="icon-remove " title=""></span>
                                </button>
                            </div>
                            <div class="span1">
                                <button type="button" class="btn add "><span class="icon-plus " title=""></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid person-type">
                        <div class="span10">
                            <h4 class=""><span title=""
                                               class="travel-icon">n</span> <?php echo JText::_('Children/infant(0-11 years)') ?>
                            </h4>
                        </div>
                    </div>
                    <div class="input-passenger-list-passenger children-infant">

                    </div>
                    <input type="hidden" name="<?php echo $name ?>">
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_room($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));

        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_room/html_build_room.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_room/html_build_room.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_room';
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_build_room({
                    list_passenger:<?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div class="html_build_room row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-room">
                    <div class="move-room handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h3><?php echo JText::_('Room ') ?><span class="room-order">1</span></h3>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <h3><?php echo JText::_('Select room type') ?></h3>
                            <div class="list-room">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <label><?php echo JText::_('Single') ?><input type="radio" checked
                                                                                      data-name="room_type"
                                                                                      name="room_type"
                                                                                      value="single"></label>
                                    </div>
                                    <div class="span3">
                                        <label><?php echo JText::_('Double') ?><input type="radio" data-name="room_type"
                                                                                      name="room_type"
                                                                                      value="double"></label>
                                    </div>
                                    <div class="span3"></div>
                                    <div class="span2">
                                        <label><?php echo JText::_('Twin') ?><input type="radio" data-name="room_type"
                                                                                    name="room_type"
                                                                                    value="twin"></label>
                                    </div>
                                    <div class="span2">
                                        <label><?php echo JText::_('Triple') ?><input type="radio" data-name="room_type"
                                                                                      name="room_type"
                                                                                      value="triple"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid note">
                                <div class="span12">
                                    <?php echo $lipsum->words(50) ?>
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
                        <div class="span7">
                            <h3><?php echo JText::_('select person for room on your own') ?></h3>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_room"></span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-room pull-right"><?php echo JText::_('Add more room') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-room pull-right"><?php echo JText::_('Remove room') ?></button>
                        </div>
                    </div>
                </div>
            </div>
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
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
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
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_extra_night_hotel($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $type, $extra_night_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));

        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_extra_night_hotel/html_build_extra_night_hotel.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_extra_night_hotel/html_build_extra_night_hotel.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');


        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $session = JFactory::getSession();
        $list_passenger = $session->get('list_passenger');
        $list_passenger = json_decode($list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_room_' . $name;
        $type = $type ? $type : 'pre';
        $debug = true;
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
                $('#<?php  echo $id_element ?>').html_build_extra_night_hotel({
                    list_passenger: cookie_list_passenger,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    extra_night_config:<?php echo json_encode($extra_night_config) ?>,
                    type: '<?php echo $type ?>',
                    list_room_type:<?php echo json_encode($list_room_type) ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div class="html_night_hotel row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-night-hotel">
                    <div class="move-room handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h3><?php echo JText::_($type . ' ') ?><span class="room-order">1</span>
                            </h3>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <h5><?php echo JText::_('Select date from to') ?></h5>
                            <div class="row-fluid">
                                <div class="span12">
                                    <input style="width: 100%" type="text" class="date">
                                </div>
                            </div>
                            <div class="row-fluid note">
                                <div class="span12">
                                    <?php echo $lipsum->words(50) ?>
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
                        <div class="span7">
                            <h3><?php echo JText::_('select person for room on your own') ?></h3>
                            <div class="list_room_type row-fluid">
                                <div class="span3">

                                </div>
                                <div class="span9">
                                    <div class="row-fluid">
                                        <?php
                                        $room_limit = 16;
                                        ?>
                                        <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                            <div class="span3">
                                                <div class="" style="text-align: center"><?php echo $room_type ?></div>
                                                <select data-room_type="<?php echo $room_type ?>"
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
                            <div class="list_room_type_passenger">
                                <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                    <div class="<?php echo $room_type ?>">
                                        <div class="passenger-item row-fluid <?php echo $room_type ?>"
                                             data-room_type="<?php echo $room_type ?>">
                                            <div class="span2">
                                                <div class="title_room"><?php echo JText::_($room_type) ?></div>
                                            </div>
                                            <div class="span1">
                                                <button type="button" class="btn btn-primary remove_room">X</button>
                                            </div>
                                            <div class="span9">
                                                <?php for ($i = 0; $i < $allow_passenger; $i++) { ?>
                                                    <select disabled style="width: 100%" class="">
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
                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-room pull-right"><?php echo JText::_('Book more') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-room pull-right"><?php echo JText::_('Remove this ' . $type) ?></button>
                        </div>
                    </div>
                </div>
            </div>
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
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
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
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_passenger_summary($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);

        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary/html_build_passenger_summary.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary/html_build_passenger_summary.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_passenger_summary' . $name;
        $session = JFactory::getSession();
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        $build_room = $session->get('build_room');
        $build_room = json_decode($build_room);

        foreach ($build_room as $item_room) {
            $tour_cost_and_room_price = $item_room->tour_cost_and_room_price;
            foreach ($tour_cost_and_room_price as $item_price) {
                $passenger_index = $item_price->passenger_index;
                $list_passenger[$passenger_index]->room_type = $item_room->room_type;
                $list_passenger[$passenger_index]->tour_service_price = $item_price->tour_cost;
                $list_passenger[$passenger_index]->room_price = $item_price->room_price;
                $list_passenger[$passenger_index]->extra_bed_price = $item_price->extra_bed_price;
                $list_passenger[$passenger_index]->msg = $item_price->msg;
                $list_passenger[$passenger_index]->bed_note = $item_price->bed_note;
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_passenger_summary({
                    list_passenger: cookie_list_passenger,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_aobject row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <h4
                    style="text-align: center"><?php echo JText::_('Service total') ?></h4>
                <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                    <?php
                    $passenger = $list_passenger[$i];
                    $room_type = $passenger->room_type;


                    ?>
                    <div class="item-aobject">
                        <div class="move-aobject handle"><span title="" class="icon-move "></span></div>
                        <div class="row-fluid">
                            <div class="span12"><h3 class="passenger-title"><?php echo JText::_('passenger ') ?><span
                                        class="aobject-order"><?php echo JText::sprintf("%s :$passenger->first_name $passenger->middle_name $passenger->last_name", $i + 1) ?></span>
                                </h3></div>
                        </div>


                        <div class="passenger-service-list">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="table table-hover table-service-list">
                                        <div class="thead">
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div
                                                        class="column-header-item"><?php echo JText::_('Service detail') ?></div>
                                                </div>
                                                <div class="span6">
                                                    <div class="column-header-item"></div>
                                                </div>
                                                <div class="span2">
                                                    <div
                                                        class="column-header-item"><?php echo JText::_('Service price') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tbody">
                                            <div class="row-fluid div-item-aobject">
                                                <div class="span4">
                                                    <div
                                                        class="row-item-column"><?php echo JText::_('Package tour') ?></div>
                                                </div>
                                                <div class="span6">
                                                    <div class="row-item-column">
                                                        <div
                                                            class="tour-detail"><?php echo JText::sprintf("%s Trip from %s to %s. %s include", $tour->product_name, JHtml::_('date', $booking_date, tsmConfig::$date_format), $end_date->format(tsmConfig::$date_format), $room_type) ?></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="row-item-column">
                                                        <div
                                                            class="tour-price"><?php echo $passenger->tour_service_price ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid div-item-aobject">
                                                <div class="span4">
                                                    <div
                                                        class="row-item-column"><?php echo JText::_('Extra service') ?></div>
                                                </div>
                                                <div class="span6">
                                                    <div class="row-item-column">
                                                        <div class="extra-service"></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="row-item-column">
                                                        <div class="extra-price"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid div-item-aobject">
                                                <div class="offset10 span2">
                                                    <div class="row-item-column">
                                                        <div class="total-price"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                <?php } ?>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_passenger_summary_confirm($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);

        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary_confirm/html_build_passenger_summary_confirm.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary_confirm/html_build_passenger_summary_confirm.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_passenger_summary' . $name;
        $session = JFactory::getSession();
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        $build_room = $session->get('build_room');
        $build_room = json_decode($build_room);

        foreach ($build_room as $item_room) {
            $tour_cost_and_room_price = $item_room->tour_cost_and_room_price;
            foreach ($tour_cost_and_room_price as $item_price) {
                $passenger_index = $item_price->passenger_index;
                $list_passenger[$passenger_index]->room_type = $item_room->room_type;
                $list_passenger[$passenger_index]->tour_service_price = $item_price->tour_cost;
                $list_passenger[$passenger_index]->room_price = $item_price->room_price;
                $list_passenger[$passenger_index]->extra_bed_price = $item_price->extra_bed_price;
                $list_passenger[$passenger_index]->msg = $item_price->msg;
                $list_passenger[$passenger_index]->bed_note = $item_price->bed_note;
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_passenger_summary_confirm({
                    list_passenger: cookie_list_passenger,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>

        <div class="html_build_passenger_summary_confirm row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1"><?php echo JText::_('Gender') ?></div>
                        <div class="span3"><?php echo JText::_('Name & surname') ?></div>
                        <div class="span1"><?php echo JText::_('Date of birth') ?></div>
                        <div class="span1"><?php echo JText::_('Nationality') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;


                        ?>

                        <div class="row-fluid row-item">
                            <div class="span1"><?php echo $passenger->title ?></div>
                            <div class="span3"><?php echo $passenger->first_name ?></div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                        </div>


                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function edit_passenger_in_order($order, $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $order->order_data=json_decode($order->order_data);
        $list_passenger=$order->order_data->list_passenger;

        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_edit_passenger_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_edit_passenger_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    order: <?php echo json_encode($order) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>

        <div class="html_edit_passenger_in_order row-fluid" id="<?php echo $id_element ?>">

            <div class="<?php echo $id_element ?>_list_aobject">

                <div class="toolbar">
                    <div class="row-fluid">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                    </div>
                </div>
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="span2 column"><?php echo JText::_('Name') ?></div>
                        <div class="span2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="span1 column"><?php echo JText::_('Title') ?></div>
                        <div class="span2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="span1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="span1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="span1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;


                        ?>

                        <div class="row-fluid row-item" data-passenger_id="<?php echo $i ?>">
                            <div class="span1 column id"><?php echo $i + 1 ?></div>
                            <div class="span2 column"><?php echo $passenger->first_name ?></div>
                            <div class="span2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="span1 column"><?php echo $passenger->title ?></div>
                            <div class="span2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="span1 column"><?php echo $passenger->nationality ?></div>
                            <div class="span1 column"></div>
                            <div class="span1 column"></div>
                            <div class="span1 column">
                                <div class="buttons pull-right">
                                    <button type="button"  class="btn-link edit"><span class="icon-edit"></span></button>
                                    <button type="button" class="btn-link pull-right"><span class="icon-cancel"></span>
                                    </button>
                                </div>
                            </div>
                        </div>


                    <?php } ?>
                </div>
            </div>
            <!-- Modal -->
            <div id="edit_passenger" class="modal hide fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <h3 id="myModalLabel"><?php echo JText::_('Edit passenger') ?>:<span class="full-name"></span></h3>
                </div>
                <div class="modal-body form-horizontal">
                    <h4><?php echo JText::_('Passenger detail') ?></h4>
                    <div class="row-fluid">

                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="title"><?php echo JText::_('Title') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="title" value="Title">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="first_name"><?php echo JText::_('First name') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="first_name" value="First name">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="last_name"><?php echo JText::_('Last name') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="last_name" value="Last name">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="gender"><?php echo JText::_('Gender') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="gender" value="">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date_of_birth"><?php echo JText::_('Date of birth') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="date_of_birth" value="">
                                </div>
                            </div>

                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="passport_no"><?php echo JText::_('Nationality') ?></label>
                                <div class="controls">
                                    <input type="text" name="nationality" id="nationality">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="passport_no"><?php echo JText::_('Passport no') ?></label>
                                <div class="controls">
                                    <input type="text" name="passport_no" id="passport_no">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="p_issue_date"><?php echo JText::_('P. Issue date') ?></label>
                                <div class="controls">
                                    <input type="text" name="p_issue_date" id="p_issue_date">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="p_expiry_date"><?php echo JText::_('P.expiry Date') ?></label>
                                <div class="controls">
                                    <input type="text" name="p_expiry_date" id="p_expiry_date">
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr/>
                    <h4><?php echo JText::_('Contact detail') ?></h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="passport_no"><?php echo JText::_('Phone no') ?></label>
                                <div class="controls">
                                    <input type="text" name="phone_no" id="phone_no">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email"><?php echo JText::_('Email address') ?></label>
                                <div class="controls">
                                    <input type="text" name="res_country" id="res_country">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="confirm_email"><?php echo JText::_('Confirm Email') ?></label>
                                <div class="controls">
                                    <input type="text" name="confirm_email" id="confirm_email">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="street_address"><?php echo JText::_('Street address') ?></label>
                                <div class="controls">
                                    <input type="text" name="street_address" id="street_address">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="suburb_town"><?php echo JText::_('Suburb/Town') ?></label>
                                <div class="controls">
                                    <input type="text" name="suburb_town" id="suburb_town">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="state_province"><?php echo JText::_('State/Province') ?></label>
                                <div class="controls">
                                    <input type="text" name="state_province" id="state_province">
                                </div>
                            </div>

                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="postcode_zip"><?php echo JText::_('Postcode/Zip') ?></label>
                                <div class="controls">
                                    <input type="text" name="postcode_zip" id="postcode_zip">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="res_country"><?php echo JText::_('Re. Country') ?></label>
                                <div class="controls">
                                    <input type="text" name="res_country" id="res_country">
                                </div>
                            </div>
                            <h5 class="emergency">
                                <?php echo JText::_('Emergency contact') ?>
                            </h5>
                            <div class="control-group">
                                <label class="control-label" for="emergency_contact_name"><?php echo JText::_('Contact name') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_name" id="emergency_contact_name">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="emergency_contact_email_address"><?php echo JText::_('Email Address') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_email_address" id="emergency_contact_email_address">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"  for="emergency_contact_phone_no"><?php echo JText::_('Phone no') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_phone_no"  id="emergency_contact_phone_no">
                                </div>
                            </div>

                        </div>
                    </div>
                    <h4><?php echo JText::_('addtion information') ?></h4>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label" for="conditions"><?php echo JText::_('Do you have any pre-existing medical condition ?') ?></label>
                                <div class="controls">
                                    <textarea name="conditions" id="conditions" style="width: 80%">

                                    </textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="requirement"><?php echo JText::_('Do you have any pre-existing medical condition ?') ?></label>
                                <div class="controls">
                                    <textarea name="requirement" id="requirement" style="width: 80%">

                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn close-edit-passenger" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary save-passenger">Save changes</button>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function passenger_information_in_order($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/passenger_information_in_order/html_passenger_information_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/passenger_information_in_order/html_passenger_information_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_passenger_information_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_passenger_information_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>


        <div class="html_passenger_information_in_order row-fluid" id="<?php echo $id_element ?>">

            <div class="<?php echo $id_element ?>_list_aobject">
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
            </div>

            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function passenger_rooming_list_in_order($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        return '<h3>rooming list</h3>';
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_edit_passenger_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_edit_passenger_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>

        <div class="html_edit_passenger_in_order row-fluid" id="<?php echo $id_element ?>">

            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row-fluid">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                        <div class="buttons pull-right">
                            <button class="btn btn-primary"><?php echo JText::_('Save change') ?></button>
                            <button class="btn btn-primary"><?php echo JText::_('Cancel change') ?></button>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="span2 column"><?php echo JText::_('Name') ?></div>
                        <div class="span2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="span1 column"><?php echo JText::_('Title') ?></div>
                        <div class="span2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="span1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="span1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="span1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;


                        ?>

                        <div class="row-fluid row-item">
                            <div class="span1 column id"><?php echo $i + 1 ?></div>
                            <div class="span2 column"><?php echo $passenger->first_name ?></div>
                            <div class="span2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="span1 column"><?php echo $passenger->title ?></div>
                            <div class="span2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="span1 column"><?php echo $passenger->nationality ?></div>
                            <div class="span1 column"></div>
                            <div class="span1 column"></div>
                            <div class="span1 column">
                                <div class="buttons pull-right">
                                    <button type="button" class="btn-link pull-left"><span class="icon-edit"></span>
                                    </button>
                                    <button type="button" class="btn-link pull-right"><span class="icon-cancel"></span>
                                    </button>
                                </div>
                            </div>
                        </div>


                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function passenger_transfer($list_transfer = array(), $name = '', $type)
    {
        $doc = JFactory::getDocument();
        $id_element = 'html_edit_passenger_in_order' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $list_list_transfer = array_chunk($list_transfer, 2);
        ob_start();
        ?>

        <div class="passenger_transfer row-fluid" id="<?php echo $id_element ?>">

            <div class="<?php echo $id_element ?>_list_aobject">
                <?php

                ?>
                <?php foreach ($list_list_transfer as $list_transfer) { ?>
                    <div class="row-fluid">
                        <?php for ($i = 0; $i < count($list_transfer); $i++) { ?>
                            <div class="span6">
                                <?php
                                $list_room_type = $list_transfer[$i]->list_room_type;
                                $list_room_type1 = array();
                                foreach ($list_room_type as $room) {
                                    $list_room_type1[] = "$room->total_room $room->room_type";
                                }
                                $list_room_type1 = implode(',', $list_room_type1);
                                $check_in = JFactory::getDate();
                                $check_in = JHtml::_('date', $check_in);
                                $check_out = JFactory::getDate();
                                $check_out = JHtml::_('date', $check_out);
                                ?>
                                <h4><?php echo $type == 'pre' ? JText::sprintf('pre transfer %s', $i + 1) : JText::sprintf('post transfer %s', $i + 1) ?></h4>
                                <div class="block">
                                    <?php echo JText::sprintf('%s <br/> check in date: %s,Check out date: %s', $list_room_type1, $check_in, $check_out) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function passenger_hotel_night($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_edit_passenger_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_edit_passenger_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>

        <div class="html_edit_passenger_in_order row-fluid" id="<?php echo $id_element ?>">

            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row-fluid">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                        <div class="buttons pull-right">
                            <button class="btn btn-primary"><?php echo JText::_('Save change') ?></button>
                            <button class="btn btn-primary"><?php echo JText::_('Cancel change') ?></button>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="span2 column"><?php echo JText::_('Name') ?></div>
                        <div class="span2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="span1 column"><?php echo JText::_('Title') ?></div>
                        <div class="span2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="span1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="span1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="span1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;


                        ?>

                        <div class="row-fluid row-item">
                            <div class="span1 column id"><?php echo $i + 1 ?></div>
                            <div class="span2 column"><?php echo $passenger->first_name ?></div>
                            <div class="span2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="span1 column"><?php echo $passenger->title ?></div>
                            <div class="span2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="span1 column"><?php echo $passenger->nationality ?></div>
                            <div class="span1 column"></div>
                            <div class="span1 column"></div>
                            <div class="span1 column">
                                <div class="buttons pull-right">
                                    <button type="button" class="btn-link pull-left"><span class="icon-edit"></span>
                                    </button>
                                    <button type="button" class="btn-link pull-right"><span class="icon-cancel"></span>
                                    </button>
                                </div>
                            </div>
                        </div>


                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_payment_cardit_card($name, $full_payment, $deposit)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);

        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_payment_cardit_card/html_build_payment_cardit_card.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_payment_cardit_card/html_build_payment_cardit_card.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $id_element = 'html_build_payment_cardit_card_' . $name;
        $debug = true;

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_build_payment_cardit_card({
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_payment_cardit_card row-fluid" id="<?php echo $id_element ?>">
            <fieldset>
                <legend><?php echo JText::_('Payment detail') ?></legend>
                <div class="payment-detail">
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Payment amout: CW') ?>
                        </div>
                        <div class="span4">
                            <label><input type="radio"
                                          name="payment_type"> <?php echo JText::sprintf('$US %s (full payment)', $full_payment) ?>
                            </label>
                        </div>
                        <div class="span4">
                            <label><input type="radio"
                                          name="payment_type"> <?php echo JText::sprintf('$US %s (Deposit)', $deposit) ?>
                            </label>

                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Card type') ?>
                        </div>
                        <div class="span3">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Visa card') ?></label>
                        </div>
                        <div class="span2">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Master card') ?></label>
                        </div>
                        <div class="span3">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Amex card') ?></label>

                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Card number') ?>:
                        </div>
                        <div class="span8">
                            <input type="text" class="card_number" name="card_number">
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Expri date') ?>:
                        </div>
                        <div class="span3">
                            <select name="month" class="month">
                                <option value=""><?php echo JText::_('select month') ?></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="span2">
                            <select name="year" class="year">
                                <option value=""><?php echo JText::_('select year') ?></option>
                                <?php for ($i = 2016; $i <= 2020; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="span3">
                            <label class="icon-question"><span></span><input type="text" class="last_three_number"
                                                                             name="last_three_number"></label>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Card holder name') ?>:
                        </div>
                        <div class="span8">
                            <input type="text" class="card_holder_name" name="card_holder_name">
                        </div>
                    </div>

                </div>
            </fieldset>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_form_contact($name, $full_payment, $deposit)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);

        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_build_form_contact/html_build_form_contact.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_build_form_contact/html_build_form_contact.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $id_element = 'html_build_form_contact_' . $name;
        $debug = true;

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_build_form_contact({
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_form_contact row-fluid" id="<?php echo $id_element ?>">
            <div class="form-contact form-horizontal">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 class="title contact-detail"><span class="icon-location "
                                                               title=""></span><?php echo JText::_('Contact detail') ?>
                        </h4>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('input', JText::_('Contact name'), 'contact_name', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Phone No'), 'phone_number', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Email Address'), 'email_address', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Confirm Email'), 'confirm_email', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Street address'), 'street_address', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Suburb/Town'), 'suburb_town', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('State/province'), 'state_province', '', 'class="required"'); ?>

                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('input', JText::_('Postcode/Zip'), 'post_code_zip', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Res. Country'), 'res_country', '', 'class="required"'); ?>
                        <h3 class=" emergency-contact"><?php echo JText::_('Emergency contact') ?></h3>
                        <?php echo VmHTML::row_control('input', JText::_('Contact Name'), 'emergency_contact_name', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Street address'), 'emergency_email_address', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Phone No'), 'emergency_phone_number', '', 'class="required"'); ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function build_pickup_transfer($name, $list_passenger = array(), $default = '0', $departure, $passenger_config, $transfer_item_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));

        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_pickup_transfer/html_build_pickup_transfer.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_pickup_transfer/html_build_pickup_transfer.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');

        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_pickup_transfer_' . $name;
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_pickup_transfer({
                    list_passenger: cookie_list_passenger,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    transfer_item_config:<?php echo json_encode($transfer_item_config) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div class="html_build_pickup_transfer row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_transfer">
                <div class="item-transfer">
                    <div class="move-transfer handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h4><?php echo JText::_('transfer ') ?><span class="transfer-order">1</span>
                            </h4></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div
                                class="air-price"><?php echo JText::sprintf('price<br/> from <span class="price">%d</span> /pers', $transfer_item->sale_price) ?></div>
                        </div>
                        <div class="span8">
                            <h5><?php echo JText::_('Air port pickup & transfer') ?></h5>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_transfer"></span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="transfer-fluid note">
                        <div class="span12">
                            <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                    <button type="button" class="btn btn-primary random-text">Random text
                                    </button><?php } ?></h4>
                            <textarea data-name="transfer_note" style="width: 96%;height: 50px"></textarea>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-transfer pull-right"><?php echo JText::_('Add more transfer') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-transfer pull-right"><?php echo JText::_('Remove transfer') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="transfering-list">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 style="text-align: center"><?php echo JText::_('transfering list') ?></h4>
                        <div class="table table-hover table-bordered table-transfering-list">
                            <div class="thead">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer type') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row-fluid div-item-transfer">
                                    <div class="span2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_type"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="private-transfer"></div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_note"></div>
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

    public static function build_excursion_addon($name, $list_passenger = array(), $default = '0', $departure, $passenger_config, $list_excursion_addon)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $json_list_passenger = json_decode($json_list_passenger);

        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_excursion_addon/html_build_excursion_addon.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_excursion_addon/html_build_excursion_addon.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');

        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_pickup_transfer_' . $name;
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_excursion_addon({
                    list_passenger: cookie_list_passenger,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    list_excursion_addon:<?php echo json_encode($list_excursion_addon) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        ob_start();
        ?>
        <div class="html_build_pickup_transfer row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_transfer">
                <div class="item-transfer">
                    <div class="move-transfer handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h4><?php echo JText::_('transfer ') ?><span class="transfer-order">1</span>
                            </h4></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div
                                class="air-price"><?php echo JText::sprintf('price<br/> from <span class="price">%d</span> /pers', $list_excursion_addon->sale_price) ?></div>
                        </div>
                        <div class="span8">
                            <h5><?php echo JText::_('Air port pickup & transfer') ?></h5>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_transfer"></span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="transfer-fluid note">
                        <div class="span12">
                            <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                    <button type="button" class="btn btn-primary random-text">Random text
                                    </button><?php } ?></h4>
                            <textarea data-name="transfer_note" style="width: 96%;height: 50px"></textarea>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-transfer pull-right"><?php echo JText::_('Add more transfer') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-transfer pull-right"><?php echo JText::_('Remove transfer') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="transfering-list">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 style="text-align: center"><?php echo JText::_('transfering list') ?></h4>
                        <div class="table table-hover table-bordered table-transfering-list">
                            <div class="thead">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer type') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row-fluid div-item-transfer">
                                    <div class="span2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_type"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="private-transfer"></div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_note"></div>
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
=======
<?php
/**
 * HTML helper class
 *
 * This class was developed to provide some standard HTML functions.
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author RickG
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009 tsmart Team. All rights reserved.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
/**
 * HTML Helper
 *
 * @package tsmart
 * @subpackage Helpers
 * @author RickG
 */
class VmHtml
{
    /**
     * Default values for options. Organized by option group.
     *
     * @var     array
     * @since   11.1
     */
    static protected $_optionDefaults = array(
        'option' => array('option.attr' => null, 'option.disable' => 'disable', 'option.id' => null, 'option.key' => 'value',
            'option.key.toHtml' => true, 'option.label' => null, 'option.label.toHtml' => true, 'option.text' => 'text',
            'option.text.toHtml' => true));
    static protected $_usedId = array();
    static function ensureUniqueId($id)
    {
        if (isset(self::$_usedId[$id])) {
            $c = 1;
            while (isset(self::$_usedId[$id . $c])) {
                $c++;
            }
            $id = $id . $c;
        }
        self::$_usedId[$id] = 1;
        return $id;
    }
    /**
     * Converts all special chars to html entities
     *
     * @param string $string
     * @param string $quote_style
     * @param boolean $only_special_chars Only Convert Some Special Chars ? ( <, >, &, ... )
     * @return string
     */
    static function shopMakeHtmlSafe($string, $quote_style = 'ENT_QUOTES', $use_entities = false)
    {
        if (defined($quote_style)) {
            $quote_style = constant($quote_style);
        }
        if ($use_entities) {
            $string = @htmlentities($string, constant($quote_style), self::vmGetCharset());
        } else {
            $string = @htmlspecialchars($string, $quote_style, self::vmGetCharset());
        }
        return $string;
    }
    /**
     * Returns the charset string from the global _ISO constant
     *
     * @return string UTF-8 by default
     * @since 1.0.5
     */
    static function vmGetCharset()
    {
        $iso = explode('=', @constant('_ISO'));
        if (!empty($iso[1])) {
            return $iso[1];
        } else {
            return 'UTF-8';
        }
    }
    /**
     * Generate HTML code for a row using VmHTML function
     * works also with shopfunctions, for example
     * $html .= VmHTML::row (array('ShopFunctions', 'renderShopperGroupList'),
     *            'VMCUSTOM_BUYER_GROUP_SHOPPER', $field->shopper_groups, TRUE, 'custom_param['.$row.'][shopper_groups][]', ' ');
     *
     * @func string  : function to call
     * @label string : Text Label
     * @args array : arguments
     * @return string: HTML code for row table
     */
    static function row($func, $label)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }
        $html = '
		<tr>
			<td class="key">
				' . $label . '
			</td>
			<td>';
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }
        $html .= call_user_func_array($func, $args) . '
			</td>';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        $html .= '</tr>';
        return $html;
    }
    static function row_control($func, $label)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }
        $label = '<label class="control-label">' . $label . '</label>';
        $html = ' <div class="control-group ">' . $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }
        $html .= '
		 <div class="controls ">
                    ' . call_user_func_array($func, $args) . '
                </div>
			';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        $html .= '</div>';
        return $html;
    }
    static function row_control_v_1($func, $label, $controls_class = '')
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        array_shift($passedArgs);//remove control class
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }
        $label = '<label class="control-label">' . $label . '</label>';
        $html = ' <div class="control-group ' . $controls_class . '">' . $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }
        $html .= '
		 <div class="controls ">
                    ' . call_user_func_array($func, $args) . '
                </div>
			';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        $html .= '</div>';
        return $html;
    }
    static function row_basic($func, $label)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        $args = array();
        foreach ($passedArgs as $k => $v) {
            $args[] = &$passedArgs[$k];
        }
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($label . '_TIP')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_TIP')) . '">' . tsmText::_($label) . '</span>';
        } //Fallback
        else if ($lang->hasKey($label . '_EXPLAIN')) {
            $label = '<span class="hasTip" title="' . htmlentities(tsmText::_($label . '_EXPLAIN')) . '">' . tsmText::_($label) . '</span>';
        } else {
            $label = tsmText::_($label);
        }
        $html = $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }
        $html .= '
                    ' . call_user_func_array($func, $args) . '
               ';
        if ($func[1] == 'radioList') {
            $html .= '</fieldset>';
        }
        return $html;
    }
    /* simple value display */
    static function value($value)
    {
        $lang = JFactory::getLanguage();
        return $lang->hasKey($value) ? tsmText::_($value) : $value;
    }
    /**
     * The sense is unclear !
     * @deprecated
     * @param $value
     * @return mixed
     */
    static function raw($value)
    {
        return $value;
    }
    /**
     * Generate HTML code for a checkbox
     *
     * @param string Name for the checkbox
     * @param mixed Current value of the checkbox
     * @param mixed Value to assign when checkbox is checked
     * @param mixed Value to assign when checkbox is not checked
     * @return string HTML code for checkbox
     */
    static function checkbox($name, $value, $checkedValue = 1, $uncheckedValue = 0, $extraAttribs = '', $id = null)
    {
        if (!$id) {
            $id = '';
        } else {
            $id = 'id="' . $id . '"';
        }
        if ($value == $checkedValue) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }
        $htmlcode = '<input type="hidden" name="' . $name . '" value="' . $uncheckedValue . '" />';
        $htmlcode .= '<input ' . $extraAttribs . ' ' . $id . ' type="checkbox" name="' . $name . '" value="' . $checkedValue . '" ' . $checked . ' />';
        return $htmlcode;
    }
    static function range_iterger($name, $value, $checkedValue = 1, $uncheckedValue = 0, $extraAttribs = '', $id = null)
    {
        if (!$id) {
            $id = '';
        } else {
            $id = 'id="' . $id . '"';
        }
        if ($value == $checkedValue) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }
        $htmlcode = '<input type="hidden" name="' . $name . '" value="' . $uncheckedValue . '" />';
        $htmlcode .= '<input ' . $extraAttribs . ' ' . $id . ' type="checkbox" name="' . $name . '" value="' . $checkedValue . '" ' . $checked . ' />';
        return $htmlcode;
    }
    /**
     *
     * @author Patrick Kohl
     * @param array $options ( value & text)
     * @param string $name option name
     * @param string $defaut defaut value
     * @param string $key option value
     * @param string $text option text
     * @param boolean $zero add  a '0' value in the option
     * return a select list
     */
    public static function select($name, $options, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $options);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';
        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function photo_store($name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $options = array();
        $options[] = array($key => "header", $text => tsmText::_('header'));
        $options[] = array($key => "gallery", $text => tsmText::_('gallery'));
        $options[] = array($key => "reviews", $text => tsmText::_('reviews'));
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function product_display($name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $options = array();
        for($i=1;$i<=10;$i++)
        {
            $options[] = array($key => "$i", $text => $i);

        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function select_state($name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $options = array();
        $options[] = array($key => "1", $text => tsmText::_('active'));
        $options[] = array($key => "0", $text => tsmText::_('unactive'));
        if ($zero == true) {
            $option = array($key => "", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $options);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';
        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function select_state_province($name, $list_state, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $country_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_state_province/html_select_state_province.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_state_province/html_select_state_province.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_state)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmstates.php';
            $list_state = tmartstates::get_states();
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_state_province({
                    state_element: '<?php echo $country_element ?>',
                    tsmart_state_id:<?php echo (int)$default ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $list_state = array_merge(array($option), $list_state);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';
        }
        return VmHtml::genericlist($list_state, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function location_city($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_location_city/html_select_select_location_city.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_location_city/html_select_select_location_city.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcities.php';
        $cities = tsmcities::get_cities();

        foreach ($cities as &$city) {
            $city->id = $city->tsmart_cityarea_id;
            $city->text = $city->city_area_name;
        }
        $option = (object)array('id' => '', 'text' => 'Please select location');
        array_unshift($cities, $option);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_location_city({
                    activities:<?php echo json_encode($cities) ?>,
                    tsmart_cityarea_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= '  disable_chosen="true"';
        }
        $html = VmHtml::genericlist($cities, $name, $attrib, 'id', 'text', $default, false, $tranlsate);
        ob_start();
        ?>
        <div class="html_select_select_location_city">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_activity($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_activity/html_select_select_activity.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_activity/html_select_select_activity.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmactivities.php';
        $activities = tsmactivities::get_activities();

        foreach ($activities as &$activity) {
            $activity->id = $activity->tsmart_activity_id;
            $activity->text = $activity->activity_name;
        }
        $option = (object)array('id' =>0, text => 'Please select activity');
        array_unshift($activities, $option);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $('select[name="<?php echo $name ?>"]').html_select_select_activity({
                    activities:<?php echo json_encode($activities) ?>,
                    tsmart_ativity_id:<?php echo $default ? $default : 0 ?>
                });

            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= '  disable_chosen="true"';
        }
        $html = VmHtml::genericlist($activities, $name, $attrib, 'id', 'text', $default, false, $tranlsate);
        ob_start();
        ?>
        <div class="html_select_select_activity">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_question_category($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_question_category/html_select_category_question.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_question_category/html_select_category_question.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcategoryquestion.php';
        $list_category_question = tsmcategoryquestion::get_list_category_question();

        foreach ($list_category_question as &$category_question) {
            $category_question->id = $category_question->tsmart_categoryfaq_id;
            $category_question->text = $category_question->categoryfaq_name;
        }
        $option = (object)array('id' =>0, text => 'Please select category question');
        array_unshift($list_category_question, $option);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $('select[name="<?php echo $name ?>"]').html_select_category_question({
                    categoryfaq:<?php echo json_encode($list_category_question) ?>,
                    tsmart_categoryfaq_id:<?php echo $default ? $default : 0 ?>
                });

            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= '  disable_chosen="true"';
        }
        $html = VmHtml::genericlist($list_category_question, $name, $attrib, 'id', 'text', $default, false, $tranlsate);
        ob_start();
        ?>
        <div class="html_select_select_categoryfaq">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_question($name, $default = '0', $attrib = "onchange='submit();'", $category_question_element = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_question/html_select_question.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_question/html_select_question.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmquestion.php';
        $list_question = tsmquestion::get_list_question();

        foreach ($list_question as &$question) {
            $question->id = $question->tsmart_categoryfaq_id;
            $question->text = $question->categoryfaq_name;
        }
        $option = (object)array('id' =>0, text => 'Please select question');
        array_unshift($list_question, $option);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $('select[name="<?php echo $name ?>"]').html_select_question({
                    questions:<?php echo json_encode($list_question) ?>,
                    tsmart_faq_id:<?php echo $default ? $default : 0 ?>,
                    category_question_element:'<?php echo $category_question_element ?>'
                });

            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $attrib .= '  disable_chosen="true"';
        $html = VmHtml::genericlist($list_question, $name, $attrib, 'id', 'text', $default, false);
        ob_start();
        ?>
        <div class="html_select_select_categoryfaq">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_tour_type($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_tour_type/html_select_tour_type.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_tour_type/html_select_tour_type.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmtourtype.php';
        $list_tour_type = tsmtourtype::get_list_tour_type();
        $id_element = 'html_select_tour_type_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_tour_type({
                    list_tour_type:<?php echo json_encode($list_tour_type) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_tour_type_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select tour type') ?></option>
                <?php foreach ($list_tour_type as $tour_type) { ?>
                    <option <?php echo $tour_type->tsmart_tour_type_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $tour_type->tsmart_tour_type_id ?>"
                        data-price_type="<?php echo $tour_type->price_type ?>"><?php echo $tour_type->title ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_number_passenger($name, $text_header = '', $min = 0, $max = 100, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        if (!$text_header) {
            $text_header = "Passenger from 12 years old";
        }
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_number_passenger/html_select_number_passenger.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_number_passenger/html_select_number_passenger.less');
        $input = JFactory::getApplication()->input;
        $list_number = range($min, $max, 1);
        $element_id = "select_number_passenger_$name";
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_number_passenger({
                    list_number:<?php echo json_encode($list_number) ?>,
                    number_selected:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $element_id ?>" class="select_number_passenger">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>" <?php echo $attrib ?> >
                <option value=""><?php echo $text_header ?></option>
                <?php for ($i = $min; $i < $max; $i++) { ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_tour($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_tour/html_select_tour.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_tour/html_select_tour.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmproduct.php';
        $list_products = tsmproduct::get_list_product();
        foreach ($list_products as &$tour) {
            $tour->id = $tour->tsmart_product_id;
            $tour->text = $tour->product_name;
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_tour({
                    list_tour:<?php echo json_encode($list_products) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= '  disable_chosen="true"';
        }
        $html = VmHtml::genericlist($list_products, $name, $attrib, 'tsmart_product_id', 'product_name', $default, false, $tranlsate);
        ob_start();
        ?>
        <div class="html_select_tour">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_trip_join_and_private($name, $coupon)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_trip_join_and_private/select_trip_join_and_private.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_trip_join_and_private/select_trip_join_and_private.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
        $list_service_class = tsmserviceclass::get_list_tour_service_class();
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmproduct.php';
        $list_products = tsmproduct::get_list_product();
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmdeparture.php';
        $list_departure = tsmDeparture::get_list_departure();
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmtourtype.php';
        $list_tour_type = tsmtourtype::get_list_tour_type();
        $element_id = 'select_trip_join_and_private_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id ?>').select_trip_join_and_private({
                    list_tour:<?php echo json_encode($list_products) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <?php
        $span_left = 'span4';
        $span_right = 'span6';
        ?>
        <div id="<?php echo $element_id ?>" class="select_trip_join_and_private">
            <div class="row-fluid">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select tour type') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <select name="private_or_joingroup" class="tour_type">
                        <option value=""><?php echo JText::_('Please select tour type') ?></option>
                        <?php foreach ($list_tour_type as $tour_type) {
                            ?>
                            <option
                                value="<?php echo $tour_type->tsmart_tour_type_id ?>"><?php echo $tour_type->title ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row-fluid">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select trip') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_tour">
                        <?php foreach ($list_products as $tour) {
                            ?>
                            <label class="checked"
                                   data-tsmart_product_id="<?php echo $tour->tsmart_product_id ?>"><?php echo $tour->product_name ?>
                                <input name="tour[]" class="checkbox tsmart_product_id" type="checkbox"
                                       value="<?php echo $tour->tsmart_product_id ?>"/></label>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid air-service-class">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select service class') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_service_class">
                        <?php foreach ($list_service_class as $service_class) {
                            ?>
                            <label class="checked"
                                   data-tsmart_service_class_id="<?php echo $service_class->tsmart_service_class_id ?>"><?php echo $service_class->service_class_name ?>
                                <input name="service_class[]" type="checkbox"
                                       value="<?php echo $service_class->tsmart_service_class_id ?>"/></label>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid air-departure">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select departure item') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_departure">
                        <?php foreach ($list_departure as $departure) {
                            ?>
                            <div class="row-fluid departure-item"
                                 data-tsmart_departure_id="<?php echo $departure->tsmart_departure_id ?>">
                                <div class="span6">
                                    <label>Departure:<?php echo JHtml::_('date', $departure->departure_date, tsmConfig::$date_format); ?>
                                        <input type="checkbox" value="<?php echo $departure->tsmart_departure_id ?>"
                                               name="departure[]"></label></div>
                                <div class="span2"></div>
                                <div class="span4">Code:<?php echo $departure->departure_code ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_city($name="tsmart_cityarea_id", $list_city, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $state_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_city/html_select_city.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_city/html_select_city.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_city)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcities.php';
            $list_city = tsmcities::get_cities();
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_city({
                    element_name:"<?php echo  $name ?>",
                    state_element: '<?php echo $state_element ?>',
                    default:<?php echo $default ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $list_city = array_merge(array($option), $list_city);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';
        }
        return VmHtml::genericlist($list_city, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function select_room($name="tsmart_room_id", $list_room, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $hotel_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_room/html_select_room.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_room/html_select_room.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_room)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmroom.php';
            $list_room = tsmroom::get_list_room();
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_room({
                    element_name:"<?php echo  $name ?>",
                    state_element: '<?php echo $hotel_element ?>',
                    default:<?php echo (int)$default ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $list_room = array_merge(array($option), $list_room);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';
        }
        return VmHtml::genericlist($list_room, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function select_percent_amount($type_name, $amount_name, $type, $amount)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_percent_amount/html_select_percent_amount.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_percent_amount/html_select_percent_amount.less');
        $input = JFactory::getApplication()->input;
        $percent_amount_id = 'percent_amount_' . $type_name . '_' . $amount_name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $percent_amount_id ?>').html_select_percent_amount({
                    type_name: '<?php echo $type_name ?>',
                    amount_name: '<?php echo $amount_name ?>',
                    'percent_input': 'percent_input'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $percent_amount_id ?>">
            <div class="row-fluid">
                <div class="span6">
                    <input type="text" class="auto percent_input" <?php echo $type == 'amount' ? 'disabled' : '' ?>
                           value="<?php echo $type == 'percent' ? $amount : '' ?>" data-a-sign="%" data-v-min="0"
                           data-v-max="100" placeholder="write percent">
                </div>
                <div class="span6">
                    <input type="text" class="auto amount_input" <?php echo $type == 'percent' ? 'disabled' : '' ?>
                           value="<?php echo $type == 'amount' ? $amount : '' ?>" data-v-min="0" data-v-max="9999"
                           placeholder="write amount">
                </div>
                <input type="hidden" value="<?php echo $type ?>" name="<?php echo $type_name ?>">
                <input type="hidden" value="<?php echo $amount ?>" name="<?php echo $amount_name ?>">
            </div>
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function select_from_to($from_name, $to_name, $from = 0, $to = 10, $min = 0, $max = 100)
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css");
        $doc->addScript(JUri::root() . '/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_from_to/html_select_from_to.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_from_to/html_select_from_to.less');
        $input = JFactory::getApplication()->input;
        $id_select_from_to = 'select_from_to_' . $from_name . '_' . $to_name;
        $id_select_from_to = str_replace(array("[", "]"), "_", $id_select_from_to);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_select_from_to ?>').html_select_from_to({
                    from_name: "<?php echo $from_name ?>",
                    to_name: "<?php echo $to_name ?>",
                    from:<?php echo (int)$from ?>,
                    to:<?php echo (int)$to ?>,
                    min:<?php echo (int)$min ?>,
                    max:<?php echo (int)$max ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_select_from_to ?>">
            <div class="integer-range"></div>
            <input type="hidden" value="<?php echo $from ?>" name="<?php echo $from_name ?>">
            <input type="hidden" value="<?php echo $to ?>" name="<?php echo $to_name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function range_of_date($from_name, $to_name, $from_date = '', $to_date = '', $format = 'YYYY-MM-DD', $min_date = '', $max_date = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_range_of_date/html_select_range_of_date.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_range_of_date/html_select_range_of_date.less');
        $input = JFactory::getApplication()->input;
        $select_from_date_to_date = 'select_from_date_to_date_' . $from_name . '_' . $to_name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_from_date_to_date ?>').html_select_range_of_date({
                    format: "<?php echo $format ?>",
                    from_name: "<?php echo $from_name ?>",
                    to_name: "<?php echo $to_name ?>",
                    from_date: "<?php echo $from_date ?>",
                    to_date: "<?php echo $to_date ?>",
                    min_date: "<?php echo $min_date ?>",
                    max_date: "<?php echo $max_date ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $select_from_date_to_date ?>" class="select_range_of_date">
            <div class="input-append ">
                <input type="text" value="<?php echo $from_date ?>-<?php echo $to_date ?>" class="range_of_date"/>
                <span class="icon-calendar add-on"></span>
            </div>
            <input type="hidden" value="<?php echo $from_date ?>" name="<?php echo $from_name ?>">
            <input type="hidden" value="<?php echo $to_date ?>" name="<?php echo $to_name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function select_date($name, $value_selected = '', $format = 'mm/dd/yy', $view_format = 'mm/dd/yy', $min_date = '', $max_date = '', $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.maskedinput-master/dist/jquery.maskedinput.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_date/html_select_date.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_date/html_select_date.less');
        $input = JFactory::getApplication()->input;
        $select_date = 'select_date_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_date ?>').html_select_date({
                    format: "<?php echo $format ? $format : 'mm/dd/yy' ?>",
                    view_format: "<?php echo $view_format ? $view_format : 'mm/dd/yy' ?>",
                    input_name: "<?php echo $name ?>",
                    value_selected: "<?php echo $value_selected ?>",
                    min_date: "<?php echo $min_date ?>",
                    max_date: "<?php echo $max_date ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $select_date ?>" class="select_date">
            <div class="input-append ">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_date_picker_<?php echo $name ?>" class="select_date <?php echo $class ?>"/>
                <span class="icon-calendar add-on"></span>
            </div>
            <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function select_month($name, $value_selected = '', $format = 'MM/YYYY', $view_format = 'MM/YYYY', $min_month = 1, $max_month = 12, $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/button.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/monthpicker.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-ui-month-picker-master/src/MonthPicker.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_month/html_select_month.js');
        $doc->addLessStyleSheet(JUri::root() . '/media/system/js/jquery-ui-month-picker-master/src/MonthPicker.css');
        $input = JFactory::getApplication()->input;
        $select_month = 'select_month_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_month ?>').html_select_month({
                    format: "<?php echo $format ? $format : 'MM/YYYY' ?>",
                    view_format: "<?php echo $view_format ? $view_format : 'MM/YYYY' ?>",
                    input_name: "<?php echo $name ?>",
                    value_selected: "<?php echo $value_selected ?>",
                    min_month: "<?php echo $min_month ?>",
                    max_month: "<?php echo $max_month ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $select_month ?>" class="select_month">
            <div class="input-append ">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_month_picker_<?php echo $name ?>" class="select_month <?php echo $class ?>"/>
                <span class="icon-calendar add-on"></span>
            </div>
            <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function edit_price_add_on($name, $data = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/base64.js');
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_add_on/html_edit_price_add_on.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_add_on/html_edit_price_add_on.less');
        $input = JFactory::getApplication()->input;
        $edit_price_add_on = 'edit_price_add_on_' . $name;
        $data1 = base64_decode($data);
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $data1 = up_json_decode($data1, false, 512, JSON_PARSE_JAVASCRIPT);
        $items = $data1->items;
        for ($i = 0; $i < count($items); $i++) {
            if ($i > 19) {
                unset($data1->items[$i]);
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $edit_price_add_on ?>').html_edit_price_add_on({
                    output_name: "<?php echo $name ?>",
                    <?php if($data1){ ?>
                    data:<?php echo json_encode($data1) ?>
                    <?php } ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $edit_price_add_on ?>" class="edit-price-add-on">
            <div class="pull-right">
                <button type="button" class="btn btn-small btn-success more-group"><span
                        class="icon-new icon-white"></span>More group
                </button>
                <button type="button" class="btn btn-small btn-success delete-group"><span
                        class="icon-delete icon-white"></span>Delete last group
                </button>
                <button type="button" class="btn btn-small btn-success reset-all"><span
                        class="icon-reset icon-white"></span>Reset all
                </button>
            </div>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Net Price</th>
                    <th>Mark up %</th>
                    <th>Mark up amount</th>
                    <th>Tax</th>
                    <th>Sale price</th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 0; $i < 2; $i++) { ?>
                    <tr class="<?php echo $i < 1 ? 'item' : 'item-flat' ?>">
                        <td><span class="index"><?php if ($i < 1){ ?><?php echo($i + 1) ?></span>
                            Person<?php } else { ?>Flat price<?php } ?></td>
                        <td><input type="text" class="auto net-price" data-a-sign="US$ " placeholder="US$"
                                   data-v-min="0" data-v-max="999999"></td>
                        <td><input type="text" class="auto mark-up-percent" data-a-sign="%" placeholder="%"
                                   data-v-min="0" data-v-max="100"></td>
                        <td><input type="text" class="auto mark-up-amount" data-a-sign="US$ " placeholder="US$"></td>
                        <td><input type="text" class="auto tax" data-a-sign="%" placeholder="%" data-v-min="0"
                                   data-v-max="100"></td>
                        <td><input type="text" class="auto sale-price" readonly data-a-sign="US$ " placeholder="US$">
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Children discount</td>
                    <td><input type="text" class="auto children-discount-amount" data-a-sign="US$ " placeholder="US$"
                               data-v-min="0" data-v-max="999999"></td>
                    <td><input type="text" class="auto children-discount-percent" data-a-sign="%" placeholder="%"
                               data-v-min="0" data-v-max="100"></td>
                    <td>Children age</td>
                    <td colspan="2"><input type="text" class="auto children-under-year" data-v-min="0" data-v-max="120">
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="<?php echo $name ?>" value="<?php echo $data ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function edit_price_hotel_add_on($name, $data = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/base64.js');
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_hotel_add_on/html_edit_price_hotel_add_on.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/edit_price_hotel_add_on/html_edit_price_hotel_add_on.less');
        $input = JFactory::getApplication()->input;
        $edit_price_add_on = 'edit_price_hotel_add_on_' . $name;
        $data1 = base64_decode($data);
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $data1 = up_json_decode($data1, false, 512, JSON_PARSE_JAVASCRIPT);
        $items = $data1->items;
        for ($i = 0; $i < count($items); $i++) {
            if ($i > 19) {
                unset($data1->items[$i]);
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $edit_price_add_on ?>').html_edit_price_hotel_add_on({
                    output_name: "<?php echo $name ?>",
                    <?php if($data1){ ?>
                    data:<?php echo json_encode($data1) ?>
                    <?php } ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $list_room = array(
            'single_room' => 'Single room',
            'double_twin_room' => 'Double/twin room',
            'triple_room' => 'Triple room'
        );
        ob_start();
        ?>
        <div id="<?php echo $edit_price_add_on ?>" class="edit-price-hotel-add-on">
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Net Price</th>
                    <th>Mark up %</th>
                    <th>Mark up amount</th>
                    <th>Tax</th>
                    <th>Sale price</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list_room as $key => $room) { ?>
                    <tr class="item" data-key-room="<?php echo $key ?>">
                        <td><span class="index"><?php echo $room ?></td>
                        <td><input type="text" class="auto net-price" data-a-sign="US$ " placeholder="US$"
                                   data-v-min="0" data-v-max="999999"></td>
                        <td><input type="text" class="auto mark-up-percent" data-a-sign="%" placeholder="%"
                                   data-v-min="0" data-v-max="100"></td>
                        <td><input type="text" class="auto mark-up-amount" data-a-sign="US$ " placeholder="US$"></td>
                        <td><input type="text" class="auto tax" data-a-sign="%" placeholder="%" data-v-min="0"
                                   data-v-max="100"></td>
                        <td><input type="text" class="auto sale-price" readonly data-a-sign="US$ " placeholder="US$">
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="<?php echo $name ?>" value="<?php echo $data ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function select_amount_percent($amount_name, $percent_name, $amount, $percent)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_amount_percent/html_select_amount_percent.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_amount_percent/html_select_amount_percent.less');
        $input = JFactory::getApplication()->input;
        $amount_percent_id = 'amount_percent_' . $amount_name . '_' . $percent_name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $amount_percent_id ?>').html_select_amount_percent({
                    amount_name: '<?php echo $amount_name ?>',
                    percent_name: '<?php echo $percent_name ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $amount_percent_id ?>">
            <div class="row-fluid">
                <div class="span6">
                    <input type="text" value="<?php echo $amount ?>" class="auto amount_input" data-v-min="0"
                           data-v-max="9999" placeholder="write of No day">
                </div>
                <div class="span6">
                    <input type="text" value="<?php echo $percent ?>" class="auto percent_input" data-a-sign="%"
                           data-v-min="0" data-v-max="100" placeholder="write percent">
                    <input type="hidden" name="<?php echo $amount_name ?>" value="<?php echo $amount ?>">
                    <input type="hidden" name="<?php echo $percent_name ?>" value="<?php echo $percent ?>">
                </div>
            </div>
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    /**
     *
     * @author Patrick Kohl
     * @param array $options ( value & text)
     * @param string $name option name
     * @param string $defaut defaut value
     * @param string $key option value
     * @param string $text option text
     * @param boolean $zero add  a '0' value in the option
     * return a select list
     */
    public static function select_add_on($name, $options, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $iframe_link, $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_add_on.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_select_add_on.less');
        $input = JFactory::getApplication()->input;
        $reload_iframe_id = $input->get('iframe_id', '', 'string');
        $reload_ui_dialog_id = $input->get('ui_dialog_id', '', 'string');
        $remove_ui_dialog = $input->get('remove_ui_dialog', false, 'boolean');
        if ($zero == true) {
            $option = array($key => "0", $text => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
            $options = array_merge(array($option), $options);
        }
        if ($chosenDropDowns) {
            vmJsApi::chosenDropDowns();
            $attrib .= ' class="vm-chzn-select"';
        }
        $select = VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
        $doc = JFactory::getDocument();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#div_<?php echo $name ?>').html_select_add_on({
                    id_field_edit_content_wrapper: 'field_edit_content_wrapper_<?php echo $name ?>',
                    iframe_link: '<?php echo $iframe_link ?>',
                    link_reload: '<?php echo base64_encode(JUri::getInstance()->toString()) ?>',
                    ui_dialog_id: 'dialog_content_<?php echo $name ?>',
                    iframe_id: 'iframe_<?php echo $name ?>',
                    reload_iframe_id: '<?php echo $reload_iframe_id ?>',
                    reload_ui_dialog_id: '<?php echo $reload_ui_dialog_id ?>',
                    remove_ui_dialog: '<?php echo json_encode($remove_ui_dialog) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="div_<?php echo $name ?>" class="html_select_add_on">
            <div class="input-append ">
                <div id="field_edit_content_wrapper_<?php echo $name ?>" style="display: none">
                    <iframe id="vm-iframe_<?php echo $name ?>" scrolling="no" src=""></iframe>
                </div>
                <?php echo $select ?>
                <button class="btn edit_content btn_<?php echo $name ?>" type="button"><span class="icon-plus"></span>
                </button>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_checkbox($name, $options, $list_selected = array(), $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $column = 3)
    {
        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <?php foreach ($list_options as $options) { ?>
        <div class="row-fluid">
            <?php foreach ($options as $option) { ?>
                <div class="span<?php echo round(12 / $column) ?>">
                    <label class="checkbox">
                        <input
                            name="<?php echo $name ?>[]" <?php echo in_array($option->$key, $list_selected) ? 'checked' : '' ?>
                            value="<?php echo $option->$key ?>" type="checkbox"> <?php echo $option->$text ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_checkbox_group_size($name, $list_selected = array(), $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/list_checkbox_group_size/html_list_checkbox_group_size.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/list_checkbox_group_size/html_list_checkbox_group_size.less');
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
        $tour_group_size = tsmGroupSize::get_list_group_size();
        $id_list_checkbox_group_size = 'list_checkbox_group_size_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_list_checkbox_group_size ?>').html_list_checkbox_group_size({
                    list_selected:<?php echo json_encode($list_selected) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        $html = '';
        $list_list_group_size = array_chunk($tour_group_size, $column);
        ob_start();
        ?>
        <div id="<?php echo $id_list_checkbox_group_size ?>" class="html_list_checkbox_group_size">
            <?php foreach ($list_list_group_size as $list_group_size) { ?>
                <div class="row-fluid">
                    <?php foreach ($list_group_size as $group_size) { ?>
                        <div class="span<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>[]" data-from="<?php echo $group_size->from ?>"
                                    data-to="<?php echo $group_size->to ?>" <?php echo in_array($group_size->tsmart_group_size_id, $list_selected) ? 'checked' : '' ?>
                                    value="<?php echo $group_size->tsmart_group_size_id ?>"
                                    type="checkbox"> <?php echo $group_size->group_name ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_radio($name, $options, $selected = 0, $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $id = "list-radio-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("<?php echo "#$id" ?>").find('input[name="<?php echo $name ?>"]').checkator({});
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <div id="<?php echo $id ?>" class="list-radio-box">
            <?php foreach ($list_options as $options) { ?>
                <div class="row-fluid">
                    <?php foreach ($options as $option) { ?>
                        <div class="span<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                    value="<?php echo $option->$key ?>" type="radio">
                                <br/>
                                <?php echo $option->$text ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_radio_price_type($name, $selected = 0, $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmprice.php';
        $options = tsmprice::get_list_price_type();
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $id = "list-radio-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("<?php echo "#$id" ?>").find('input[name="<?php echo $name ?>"]').checkator({});
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <div id="<?php echo $id ?>" class="list-radio-box">
            <?php foreach ($list_options as $options) { ?>
                <div class="row-fluid">
                    <?php foreach ($options as $option) { ?>
                        <div class="span6">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>" <?php echo $option->value == $selected ? 'checked' : '' ?>
                                    value="<?php echo $option->value ?>" type="radio">
                                <br/>
                                <?php echo $option->text ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_radio_price_type1($name, $selected = 0, $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmprice.php';
        $options = tsmprice::get_list_price_type();
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $id_element = "list-radio-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("<?php echo "#$id_element" ?>").find('input[name="<?php echo $name ?>"]').checkator({

                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $key = 'value';
        $text = 'text';
        $html = '';
        $list_options = array_chunk($options, $column);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="list-radio-box">
            <?php foreach ($list_options as $options) { ?>
                <div class="row-fluid">
                    <?php foreach ($options as $option) { ?>
                        <div class="span<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                    value="<?php echo $option->$key ?>" type="radio">
                                <br/>
                                <?php echo $option->$text ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function view_list_tag($list_tag)
    {
        $html = '';
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/plugins/tag/style.less');
        ob_start();
        ?>
        <div class="tags">
            <?php foreach ($list_tag as $tag) { ?>
                <a class="tag-body yellow" href="javascript:void(0)"><span class="tag"><?php echo $tag ?></span></a>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function view_list($list_tag)
    {
        $html = '';
        $doc = JFactory::getDocument();
        ob_start();
        ?>
        <ul>
            <?php foreach ($list_tag as $tag) { ?>
                <li><?php echo $tag ?></li>
            <?php } ?>
        </ul>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function group_price($items = array(), $tranlsate = true, $column = 3)
    {
        $list_items = array_chunk($items, $column);
        ob_start();
        ?>
        <?php foreach ($list_items as $items) { ?>
        <div class="row">
            <?php foreach ($items as $item) { ?>
                <div class="span<?php echo round(12 / $column) ?>">
                    <label class="input">
                        <?php echo $item->text ?> <input style="width: 50px" name="<?php echo $item->name ?>"
                                                         value="<?php echo $item->value ?>">
                    </label>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_option($name, $options, $list_selected = array(), $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jQuery-Plugin-For-Bootstrap-Button-Group-Toggles/select-toggleizer.js');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').toggleize({});
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $attrib .= ' disable_chosen="true" ';
        $html = VmHtml::genericlist($options, $name, $attrib, $key, $text, $list_selected, false, $tranlsate);
        return $html;
    }
    /**
     * Generates an HTML selection list.
     * @author Joomla 2.5.14
     * @param   array $data An array of objects, arrays, or scalars.
     * @param   string $name The value of the HTML name attribute.
     * @param   mixed $attribs Additional HTML attributes for the <select> tag. This
     *                               can be an array of attributes, or an array of options. Treated as options
     *                               if it is the last argument passed. Valid options are:
     *                               Format options, see {@see JHtml::$formatOptions}.
     *                               Selection options, see {@see JHtmlSelect::options()}.
     *                               list.attr, string|array: Additional attributes for the select
     *                               element.
     *                               id, string: Value to use as the select element id attribute.
     *                               Defaults to the same as the name.
     *                               list.select, string|array: Identifies one or more option elements
     *                               to be selected, based on the option key values.
     * @param   string $optKey The name of the object variable for the option value. If
     *                               set to null, the index of the value array is used.
     * @param   string $optText The name of the object variable for the option text.
     * @param   mixed $selected The key that is selected (accepts an array or a string).
     * @param   mixed $idtag Value of the field id or null by default
     * @param   boolean $translate True to translate
     *
     * @return  string  HTML for the select list.
     *
     * @since   11.1
     */
    public static function genericlist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
                                       $translate = false)
    {
        // Set default options
        $options = array_merge(JHtml::$formatOptions, array('format.depth' => 0, 'id' => false));
        if (is_array($attribs) && func_num_args() == 3) {
            // Assume we have an options array
            $options = array_merge($options, $attribs);
        } else {
            // Get options from the parameters
            $options['id'] = $idtag;
            $options['list.attr'] = $attribs;
            $options['list.translate'] = $translate;
            $options['option.key'] = $optKey;
            $options['option.text'] = $optText;
            $options['list.select'] = $selected;
        }
        if (isset($options['list.attr'])) {
            if (is_array($options['list.attr'])) {
                $attribs = JArrayHelper::toString($options['list.attr']);
            } else {
                $attribs = $options['list.attr'];
            }
            if ($attribs != '') {
                $attribs = ' ' . $attribs;
            }
        }
        $id = $options['id'] !== false ? $options['id'] : $name;
        $id = str_replace(array('[', ']'), '', $id);
        $baseIndent = str_repeat($options['format.indent'], $options['format.depth']++);
        $html = $baseIndent . '<select' . ($id !== '' ? ' id="' . $id . '"' : '') . ' name="' . $name . '"' . $attribs . '>' . $options['format.eol']
            . self::options($data, $options) . $baseIndent . '</select>' . $options['format.eol'];
        return $html;
    }
    /**
     * Generates the option tags for an HTML select list (with no select tag
     * surrounding the options).
     * @author Joomla 2.5.14
     * @param   array $arr An array of objects, arrays, or values.
     * @param   mixed $optKey If a string, this is the name of the object variable for
     *                               the option value. If null, the index of the array of objects is used. If
     *                               an array, this is a set of options, as key/value pairs. Valid options are:
     *                               -Format options, {@see JHtml::$formatOptions}.
     *                               -groups: Boolean. If set, looks for keys with the value
     *                                "&lt;optgroup>" and synthesizes groups from them. Deprecated. Defaults
     *                                true for backwards compatibility.
     *                               -list.select: either the value of one selected option or an array
     *                                of selected options. Default: none.
     *                               -list.translate: Boolean. If set, text and labels are translated via
     *                                vmText::_(). Default is false.
     *                               -option.id: The property in each option array to use as the
     *                                selection id attribute. Defaults to none.
     *                               -option.key: The property in each option array to use as the
     *                                selection value. Defaults to "value". If set to null, the index of the
     *                                option array is used.
     *                               -option.label: The property in each option array to use as the
     *                                selection label attribute. Defaults to null (none).
     *                               -option.text: The property in each option array to use as the
     *                               displayed text. Defaults to "text". If set to null, the option array is
     *                               assumed to be a list of displayable scalars.
     *                               -option.attr: The property in each option array to use for
     *                                additional selection attributes. Defaults to none.
     *                               -option.disable: The property that will hold the disabled state.
     *                                Defaults to "disable".
     *                               -option.key: The property that will hold the selection value.
     *                                Defaults to "value".
     *                               -option.text: The property that will hold the the displayed text.
     *                               Defaults to "text". If set to null, the option array is assumed to be a
     *                               list of displayable scalars.
     * @param   string $optText The name of the object variable for the option text.
     * @param   mixed $selected The key that is selected (accepts an array or a string)
     * @param   boolean $translate Translate the option values.
     *
     * @return  string  HTML for the select list
     *
     * @since   11.1
     */
    public static function options($arr, $optKey = 'value', $optText = 'text', $selected = null, $translate = false)
    {
        $options = array_merge(
            JHtml::$formatOptions,
            self::$_optionDefaults['option'],
            array('format.depth' => 0, 'groups' => true, 'list.select' => null, 'list.translate' => false)
        );
        if (is_array($optKey)) {
            // Set default options and overwrite with anything passed in
            $options = array_merge($options, $optKey);
        } else {
            // Get options from the parameters
            $options['option.key'] = $optKey;
            $options['option.text'] = $optText;
            $options['list.select'] = $selected;
            $options['list.translate'] = $translate;
        }
        $html = '';
        $baseIndent = str_repeat($options['format.indent'], $options['format.depth']);
        foreach ($arr as $elementKey => &$element) {
            $attr = '';
            $extra = '';
            $label = '';
            $id = '';
            if (is_array($element)) {
                $key = $options['option.key'] === null ? $elementKey : $element[$options['option.key']];
                $text = $element[$options['option.text']];
                if (isset($element[$options['option.attr']])) {
                    $attr = $element[$options['option.attr']];
                }
                if (isset($element[$options['option.id']])) {
                    $id = $element[$options['option.id']];
                }
                if (isset($element[$options['option.label']])) {
                    $label = $element[$options['option.label']];
                }
                if (isset($element[$options['option.disable']]) && $element[$options['option.disable']]) {
                    $extra .= ' disabled="disabled"';
                }
            } elseif (is_object($element)) {
                $key = $options['option.key'] === null ? $elementKey : $element->$options['option.key'];
                $text = $element->$options['option.text'];
                if (isset($element->$options['option.attr'])) {
                    $attr = $element->$options['option.attr'];
                }
                if (isset($element->$options['option.id'])) {
                    $id = $element->$options['option.id'];
                }
                if (isset($element->$options['option.label'])) {
                    $label = $element->$options['option.label'];
                }
                if (isset($element->$options['option.disable']) && $element->$options['option.disable']) {
                    $extra .= ' disabled="disabled"';
                }
            } else {
                // This is a simple associative array
                $key = $elementKey;
                $text = $element;
            }
            // The use of options that contain optgroup HTML elements was
            // somewhat hacked for J1.5. J1.6 introduces the grouplist() method
            // to handle this better. The old solution is retained through the
            // "groups" option, which defaults true in J1.6, but should be
            // deprecated at some point in the future.
            $key = (string)$key;
            // if no string after hyphen - take hyphen out
            $splitText = explode(' - ', $text, 2);
            $text = $splitText[0];
            if (isset($splitText[1])) {
                $text .= ' - ' . $splitText[1];
            }
            if ($options['list.translate'] && !empty($label)) {
                $label = tsmText::_($label);
            }
            if ($options['option.label.toHtml']) {
                $label = htmlentities($label);
            }
            if (is_array($attr)) {
                $attr = JArrayHelper::toString($attr);
            } else {
                $attr = trim($attr);
            }
            $extra = ($id ? ' id="' . $id . '"' : '') . ($label ? ' label="' . $label . '"' : '') . ($attr ? ' ' . $attr : '') . $extra;
            if (is_array($options['list.select'])) {
                foreach ($options['list.select'] as $val) {
                    $key2 = is_object($val) ? $val->$options['option.key'] : $val;
                    if ($key == $key2) {
                        $extra .= ' selected="selected"';
                        break;
                    }
                }
            } elseif ((string)$key == (string)$options['list.select']) {
                $extra .= ' selected="selected"';
            }
            if ($options['list.translate']) {
                $text = tsmText::_($text);
            }
            // Generate the option, encoding as required
            $html .= $baseIndent . '<option value="' . ($options['option.key.toHtml'] ? htmlspecialchars($key, ENT_COMPAT, 'UTF-8') : $key) . '"'
                . $extra . '>';
            $html .= $options['option.text.toHtml'] ? htmlentities(html_entity_decode($text, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8') : $text;
            $html .= '</option>' . $options['format.eol'];
        }
        return $html;
    }
    /**
     * Prints an HTML dropdown box named $name using $arr to
     * load the drop down.  If $value is in $arr, then $value
     * will be the selected option in the dropdown.
     * @author gday
     * @author soeren
     *
     * @param string $name The name of the select element
     * @param string $value The pre-selected value
     * @param array $arr The array containing $key and $val
     * @param int $size The size of the select element
     * @param string $multiple use "multiple=\"multiple\" to have a multiple choice select list
     * @param string $extra More attributes when needed
     * @return string HTML drop-down list
     */
    static function selectList($name, $value, $arrIn, $size = 1, $multiple = "", $extra = "", $data_placeholder = '')
    {
        $html = '';
        if (empty($arrIn)) {
            $arr = array();
        } else {
            if (!is_array($arrIn)) {
                $arr = array($arrIn);
            } else {
                $arr = $arrIn;
            }
        }
        if (!empty($data_placeholder)) {
            $data_placeholder = 'data-placeholder="' . tsmText::_($data_placeholder) . '"';
        }
        $html = '<select class="inputbox" id="' . $name . '" name="' . $name . '" size="' . $size . '" ' . $multiple . ' ' . $extra . ' ' . $data_placeholder . ' >';
        while (list($key, $val) = each($arr)) {
//		foreach ($arr as $key=>$val){
            $selected = "";
            if (is_array($value)) {
                if (in_array($key, $value)) {
                    $selected = 'selected="selected"';
                }
            } else {
                if (strtolower($value) == strtolower($key)) {
                    $selected = 'selected="selected"';
                }
            }
            $html .= '<option value="' . $key . '" ' . $selected . '>' . self::shopMakeHtmlSafe($val);
            $html .= '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    /**
     * Creates a Radio Input List
     *
     * @param string $name
     * @param string $value default value
     * @param string $arr
     * @param string $extra
     * @return string
     */
    static function radioList($name, $value, &$arr, $extra = "", $separator = '<br />')
    {
        $html = '';
        if (empty($arr)) {
            $arr = array();
        }
        $html = '';
        $i = 0;
        foreach ($arr as $key => $val) {
            $checked = '';
            if (is_array($value)) {
                if (in_array($key, $value)) {
                    $checked = 'checked="checked"';
                }
            } else {
                if (strtolower($value) == strtolower($key)) {
                    $checked = 'checked="checked"';
                }
            }
            $html .= '<input type="radio" name="' . $name . '" id="' . $name . $i . '" value="' . htmlspecialchars($key, ENT_QUOTES) . '" ' . $checked . ' ' . $extra . " />\n";
            $html .= '<label for="' . $name . $i++ . '">' . $val . "</label>" . $separator . "\n";
        }
        return $html;
    }
    /**
     * Creates radio List
     * @param array $radios
     * @param string $name
     * @param string $default
     * @return string
     */
    static function radio($name, $radios, $default, $key = 'value', $text = 'text')
    {
        return '<fieldset class="radio">' . JHtml::_('select.radiolist', $radios, $name, '', $key, $text, $default) . '</fieldset>';
    }
    /**
     * Creating rows with boolean list
     *
     * @author Patrick Kohl
     * @param string $label
     * @param string $name
     * @param string $value
     *
     */
    public static function booleanlist($name, $value, $class = 'class="inputbox"')
    {
        return '<fieldset class="radio">' . JHtml::_('select.booleanlist', $name, $class, $value) . '</fieldset>';
    }
    /**
     * Creating rows with boolean list
     *
     * @author Patrick Kohl
     * @param string $label
     * @param string $name
     * @param string $value
     *
     */
    public static function activelist($name, $value, $class = 'class="inputbox"')
    {
        return JHtml::_('select.booleanlist', $name, $class, $value,JText::_('Active'),JText::_('Unactive')) ;
    }
    public static function bootstrap_activelist($name, $value, $class = 'class="inputbox"')
    {
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/js/controller/bootstrap_activelist/bootstrap_activelist.less');
        $doc->addScript(JUri::root().'administrator/components/com_tsmart/assets/js/controller/bootstrap_activelist/bootstrap_activelist.js');
        $doc->addScript(JUri::root().'administrator/components/com_tsmart/assets/js/plugin/icheck-1.x/icheck.js');
        $doc->addStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/js/plugin/icheck-1.x/skins/all.css');
        ob_start();
        $id_element='bootstrap_activelist_'.$name;
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/utility.php';
        $id_element=TSMUtility::clean($id_element);
        $clean_name=TSMUtility::clean($name);
        ?>
        <div id="<?php echo $id_element ?>" class="bootstrap_activelist">
            <ul class="list">
                <li>
                    <input type="radio" id="<?php echo $clean_name ?>-minimal-radio-1" name="<?php echo $name ?>" value="<?php echo $value ?>" <?php echo $value==false?'checked':'' ?>>
                    <label for="<?php echo $clean_name ?>-minimal-radio-1"><?php echo JText::_('Active') ?></label>
                </li>
                <li>
                    <input  type="radio" id="<?php echo $clean_name ?>-minimal-radio-2" name="<?php echo $name ?>"  <?php echo $value==true?'checked':'' ?> >
                    <label for="<?php echo $clean_name ?>-minimal-radio-2"><?php echo JText::_('Unactive') ?></label>
                </li>
            </ul>
        </div>
        <?php
        $html=ob_get_clean();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').bootstrap_activelist({
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        return $html;
    }
    /**
     * Creating rows with input fields
     *
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function input($name, $value, $class = '', $readonly = false, $size = '', $maxlength = '255', $more = '')
    {
        return '<input type="text" ' . $readonly . ' ' . $class . ' id="' . $name . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '" value="' . ($value) . '" />' . $more;
    }
    public static function password($name, $value, $class = '', $readonly = false, $size = '30', $maxlength = '255', $more = '')
    {
        return '<input type="password" ' . $readonly . ' ' . $class . ' id="' . $name . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '" value="' . ($value) . '" />' . $more;
    }
    public static function input_percent($name, $value, $class = 'inputbox', $readonly = '', $min = 0, $max = 100, $more = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.input_percent_<?php echo $name ?>').autoNumeric('init').change(function () {
                    var value_of_this = $(this).autoNumeric('get');
                    $('input[name="<?php echo $name ?>"]').val(value_of_this);
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <input type="text" value="<?php echo $value ?>" <?php echo $readonly ? 'readonly' : '' ?>
               class="inputbox <?php echo $class ?>   input_percent_<?php echo $name ?>" data-v-min="<?php echo $min ?>"
               data-v-max="<?php echo $max ?>" data-a-sign="%">
        <input type="hidden" value="<?php echo $value ?>" name="<?php echo $name ?>" id="<?php echo $name ?>">
        <?php
        return ob_get_clean();
    }
    public static function input_button($name, $value, $type = "submit", $size_class = "btn-large", $class_type = "btn-primary", $attr = array())
    {
        $doc = JFactory::getDocument();
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <button type="<?php echo $type ?>" name="<?php echo $name ?>"
                class="btn <?php echo $class_type ?> <?php echo $size_class ?>"><?php echo $value ?></button>
        <?php
        return ob_get_clean();
    }
    public static function input_number($name, $value, $class = 'inputbox', $readonly = '', $min = 0, $max = 100, $more = '', $option = array())
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $('input[name="input_number_<?php
                    echo $name ?>"]').autoNumeric('init',<?php echo json_encode($option) ?>).change(function () {
                    var value_of_this = $(this).autoNumeric('get');
                    $('input[name="<?php echo $name ?>"]').val(value_of_this).trigger("change");
                    ;
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <input type="text" value="<?php echo $value ?>" <?php echo $readonly ? 'readonly' : '' ?>
               class="inputbox <?php echo $class ?> " name="input_number_<?php echo $name ?>" data-v-min="<?php echo $min ?>"
               data-v-max="<?php echo $max ?>">
        <input type="hidden" value="<?php echo $value ?>" name="<?php echo $name ?>" id="<?php echo $name ?>">
        <?php
        return ob_get_clean();
    }
    public static function input_add_on($name, $value, $class = 'class="inputbox"', $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        $input = '<input type="text" ' . $readonly . ' ' . $class . ' id="' . $name . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '" value="' . ($value) . '" />' . $more;
        ob_start();
        ?>
        <div class="input-append">
            <?php echo $input ?>
            <button class="btn" type="button"><span class="icon-plus"></span></button>
        </div>
        <!-- /input-group -->
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function google_map($latitude_name, $longitude_name, $location_name, $radius_name, $latitude = 0, $longitude = 0, $location = '', $radius = 0, $class = 'class="inputbox"', $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        $doc->addScript('http://maps.google.com/maps/api/js?sensor=false&libraries=places');
        $doc->addScript(JUri::root() . 'media/system/js/jquery-locationpicker-plugin-master/src/locationpicker.jquery.js');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $latitude_name ?>-<?php echo $longitude_name ?>-map').locationpicker({
                    location: {latitude: <?php echo (int)$latitude ?>, longitude: <?php echo (int)$longitude ?>},
                    radius: 300,
                    inputBinding: {
                        latitudeInput: $('#<?php echo $latitude_name ?>'),
                        longitudeInput: $('#<?php echo $longitude_name ?>'),
                        radiusInput: $('#<?php echo $radius_name ?>'),
                        locationNameInput: $('#<?php echo $location_name ?>')
                    },
                    enableAutocomplete: true,
                    onchanged: function (currentLocation, radius, isMarkerDropped) {
                        $('input[name="<?php echo $latitude_name ?>"]').val(currentLocation.latitude);
                        $('input[name="<?php echo $longitude_name ?>"]').val(currentLocation.longitude);
                        $('input[name="<?php echo $location_name ?>"]').val(currentLocation.location);
                    }
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        Location: <input type="text" id="<?php echo $location_name ?>" name="<?php echo $location_name ?>"
                         value="<?php echo $location ?>" style="width: 200px"/>
        Radius: <input type="text" value="<?php echo $radius ?>" id="<?php echo $radius_name ?>"
                       name="<?php echo $radius_name ?>"/>
        <div id="<?php echo $latitude_name ?>-<?php echo $longitude_name ?>-map"
             style="width: 100%; height: 400px;"></div>
        Lat.: <input type="text" name="<?php echo $latitude_name ?>" value="<?php echo $latitude ?>"
                     id="<?php echo $latitude_name ?>"/>
        Long.: <input type="text" name="<?php echo $longitude_name ?>" value="<?php echo $longitude ?>"
                      id="<?php echo $longitude_name ?>"/>
        <?php
        $content = ob_get_clean();
        return $content;
    }
    public static function text_view($value, $class, $more = '')
    {
        return '<input type="text" disabled class="text-view"    value="' . ($value) . '" />' . $more;
    }
    public static function textarea_view($name, $value, $attr = array(), $more = '')
    {
        $attr = implode(' , ', array_map(
            function ($v, $k) {
                return $k . '="' . $v . '"';
            },
            $attr,
            array_keys($attr)
        ));
        ob_start();
        ?>
        <textarea name="<?php echo $name ?>" readonly <?php echo $attr ?> ><?php echo $value ?></textarea>
        <?php
        $html = ob_get_clean();
        return $html . $more;
    }
    public static function text_view_from_to($from, $to, $text, $attr1, $attr1, $more = '')
    {
        return '<input type="text" ' . $attr1 . '  disabled class="text-view"    value="' . ($from) . '" /><span>' . $text . '</span><input type="text" ' . $attr1 . ' disabled class="text-view"    value="' . ($to) . '" />' . $more;
    }
    public static function text_view2($value, $class, $more = '')
    {
        return $value . $more;
    }
    public static function show_image($value, $class = 'class="inputbox"', $width = 40, $height = 40)
    {
        return '<img ' . $class . ' src="' . ($value) . '" width="' . $width . '" height="' . $height . '" />';
    }
    public static function show_tip($name="",$value, $option_tip=array(
        speed=>400,
        background=>"#55b555"
    ))
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        ob_start();
        ?>
        <a href="javascript:void(0)" class="show-content <?php echo $name ?>"><span class="icon-eye"></span></a>
        <?php
        $html=ob_get_clean();
        $option_tip=(array)$option_tip;
        $option_tip1=array(
            speed=>400,
            background=>"#55b555",
            content=>$value
        );
        $option_tip=array_merge($option_tip,$option_tip1);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.show-content.<?php echo $name ?>').tipso(<?php echo json_encode($option_tip)?>);
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);

        return $html;
    }
    /**
     * Creating rows with input fields
     *
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function image($name, $value, $attri="", $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        require_once JPATH_ROOT . '/libraries/cms/form/field/media.php';
        $media_field = new JFormFieldMedia();
        $media_string = <<<XML
<field name="$name" class="select-image"  type="media" default="1" label="">
</field>
XML;
        $element_media = simplexml_load_string($media_string);
        $media_field->setup($element_media, $value, '');
        return '<div '.$attri.'>'.$media_field->renderField().'</div>';
    }

    public static function file_browser($name, $value, $attri="", $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        ob_start();
        ?>
        <input type="file" name="<?php echo $name ?>" />
        <?php echo $value ?>
        <?php
        $html=ob_get_clean();
        return $html;
    }
    /**
     * Creating rows with input fields
     *
     * @author Patrick Kohl
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function textarea($name, $value, $class = 'class="inputbox"', $cols = '100', $rows = "4")
    {
        return '<textarea ' . $class . ' id="' . $name . '" name="' . $name . '" cols="' . $cols . '" rows="' . $rows . '">' . $value . '</textarea >';
    }
    /**
     * render editor code
     *
     * @author Patrick Kohl
     * @param string $text
     * @param string $name
     * @param string $value
     */
    public static function editor($name, $value, $size = '100%', $height = '10', $col = 10, $row = 10, $hide = array('pagebreak', 'readmore'))
    {
        $editor = JFactory::getEditor();
        return $editor->display($name, $value, $size, $height, $col, $row, $hide);
    }
    /**
     * renders the hidden input
     * @author Max Milbers
     */
    public static function inputHidden($values)
    {
        $html = '';
        foreach ($values as $k => $v) {
            $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
        }
        return $html;
    }
    /**
     * @author Patrick Kohl
     * @var $type type of regular Expression to validate
     * $type can be I integer, F Float, A date, M, time, T text, L link, U url, P phone
     * @bool $required field is required
     * @Int $min minimum of char
     * @Int $max max of char
     * @var $match original ID field to compare with this such as Email, passsword
     *@ Return $html class for validate javascript
     **/
    public static function validate($type = '', $required = true, $min = null, $max = null, $match = null)
    {
        if ($required) $validTxt = 'required';
        else $validTxt = 'optional';
        if (isset($min)) $validTxt .= ',minSize[' . $min . ']';
        if (isset($max)) $validTxt .= ',maxSize[' . $max . ']';
        static $validateID = 0;
        $validateID++;
        if ($type == 'S') return 'id="validate' . $validateID . '" class="validate[required,minSize[2],maxSize[255]]"';
        $validate = array('I' => 'onlyNumberSp', 'F' => 'number', 'D' => 'dateTime', 'A' => 'date', 'M' => 'time', 'T' => 'Text', 'L' => 'link', 'U' => 'url', 'P' => 'phone');
        if (isset ($validate[$type])) $validTxt .= ',custom[' . $validate[$type] . ']';
        $html = 'id="validate' . $validateID . '" class="validate[' . $validTxt . ']"';
        return $html;
    }
    public static function select_service_class($list_service_class = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_service_class/html_select_service_class.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_service_class/html_select_service_class.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_service_class)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
            $list_service_class = tsmserviceclass::get_list_service_class();
        }
        $id_element = 'html_select_service_class_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_service_class({
                    list_tour:<?php echo json_encode($list_service_class) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_language_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select Service class') ?></option>
                <?php foreach ($list_service_class as $service_class) { ?>
                    <option <?php echo $service_class->tsmart_service_class_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $service_class->tsmart_service_class_id ?>"><?php echo $service_class->service_class_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_currency($list_currency = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_currency/html_select_currency.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_currency/html_select_currency.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_currency)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcurrency.php';
            $list_currency = tsmcurrency::get_list_currency();
        }
        $id_element = 'html_select_service_class_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_currency({
                    list_tour:<?php echo json_encode($list_currency) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_language_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select currency') ?></option>
                <?php foreach ($list_currency as $currency) { ?>
                    <option <?php echo $currency->tsmart_currency_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $currency->tsmart_currency_id ?>"><?php echo $currency->currency_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_language($list_language = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_language/html_select_language.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/select_language/html_select_language.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_language)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmlanguage.php';
            $list_language = tsmlanguage::get_list_language();
        }
        $id_element = 'html_select_language_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_language({
                    list_language:<?php echo json_encode($list_language) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_language_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select Language') ?></option>
                <?php foreach ($list_language as $language) { ?>
                    <option <?php echo $language->tsmart_language_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $language->tsmart_language_id ?>"><?php echo $language->language_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_type_percent_or_amount($name, $default)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_type_percent_or_amount/html_select_type_percent_or_amount.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_type_percent_or_amount/html_select_type_percent_or_amount.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_select_type_percent_or_amount_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_type_percent_or_amount({});
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please type percent or amount') ?></option>
                <option value="percent"><?php echo JText::_('percent') ?></option>
                <option value="amount"><?php echo JText::_('amount') ?></option>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_range_of_date($list_rang_of_date = array(), $name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_list_range_of_date/html_select_list_range_of_date.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/select_list_range_of_date/html_select_list_range_of_date.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_select_range_of_date_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_list_range_of_date({
                    list_range_of_date:<?php echo json_encode($list_rang_of_date) ?>,
                    select_name: "<?php echo $name ?>",
                    id_selected:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_select_list_range_of_date" id="<?php echo $id_element ?>">
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select range of date') ?></option>
                <?php foreach ($list_rang_of_date as $range_of_date) { ?>
                    <option <?php echo $range_of_date->id == $default ? ' selected ' : '' ?>
                        value="<?php echo $range_of_date->$key ?>"><?php echo $range_of_date->$text ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function generate_code($name, $default = '0')
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/generate_code/html_generate_code.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/generate_code/html_generate_code.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_generate_code_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_generate_code({});
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_generate_code" id="<?php echo $id_element ?>">
            <div class="row-fluid">
                <div class="span8">
                    <input name="<?php echo $name ?>" value="<?php echo $default ?>" type="text" class="code">
                </div>
                <div class="span4">
                    <button type="button" class="btn generate_code"><?php echo JText::_('Go') ?></button>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function input_passenger($list_passenger = array(), $name = '', $default = '0', $min_age = 0, $max_age = 99, $departure, $passenger_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/Create-A-Tooltip/js/jquery.tooltip.js');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/input_passenger/html_input_passenger.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/input_passenger/html_input_passenger.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/system/js/Create-A-Tooltip/css/tooltip.less');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/Create-A-Tooltip/css/tooltip.css');
        $model = tmsModel::getModel('country');
        $list_country = $model->getItemList();
        foreach ($list_country AS &$country) {
            $country->id = $country->tsmart_country_id;
            $country->text = $country->country_name;
        }
        $input = JFactory::getApplication()->input;
        $id_element = 'html_input_passenger';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_input_passenger({
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    min_age: <?php echo $min_age ?>,
                    max_age: <?php echo $max_age != 0 ? $max_age : 99 ?>,
                    debug: true,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    list_country:<?php echo json_encode($list_country) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="html_input_passenger">
            <div class="row-fluid">
                <div class="span10">
                    <div class="row-fluid person-type">
                        <div class="span10">
                            <h4 class="">
                                <span title=""
                                      class="travel-icon">n</span> <?php echo JText::_('SENIOR/ADULT/TEEN(12-99 years)') ?>
                                <button type="button" class="btn btn-primary auto-fill-date">auto fill data</button>
                            </h4>
                        </div>
                    </div>
                    <div class="row-fluid herder">
                        <div class="span1"></div>
                        <div class="span1"><?php echo JText::_('Gender') ?></div>
                        <div class="span1"><?php echo JText::_('First name') ?></div>
                        <div class="span1"><?php echo JText::_('Middle name') ?></div>
                        <div class="span1"><?php echo JText::_('Last name') ?></div>
                        <div class="span1"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1"><?php echo JText::_('Date of birth') ?></div>
                        <div class="span1"></div>
                        <div class="span1"></div>
                    </div>
                    <div class="input-passenger-list-passenger senior-adult-teen">
                        <div class="row-fluid item-passenger">
                            <div class="span1"><?php echo JText::_('Person ') ?><span class="passenger-index">1</span>
                            </div>
                            <div class="span1">
                                <select data-name="gender" ">
                                <option value="mr">Mr</option>
                                <option value="ms">Ms</option>
                                </select>
                            </div>
                            <div class="span1"><input required data-name="first_name"
                                                      placeholder="<?php echo JText::_('First name') ?>"
                                                      type="text"></div>
                            <div class="span1"><input data-name="middle_name"
                                                      placeholder="<?php echo JText::_('Middle name') ?>"
                                                      type="text"></div>
                            <div class="span1"><input required data-name="last_name"
                                                      placeholder="<?php echo JText::_('Last name') ?>"
                                                      type="text"></div>
                            <div class="span1"><input required data-name="nationality"
                                                      placeholder="<?php echo JText::_('Nationality') ?>"
                                                      type="text"></div>
                            <div class="span1"><input required class="date readonly" data-name="date_of_birth" readonly
                                                      placeholder="<?php echo JText::_('Date of birth') ?>"
                                                      type="text"></div>
                            <div class="span1">
                                <button type="button" class="btn remove"><span class="icon-remove " title=""></span>
                                </button>
                            </div>
                            <div class="span1">
                                <button type="button" class="btn add "><span class="icon-plus " title=""></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid person-type">
                        <div class="span10">
                            <h4 class=""><span title=""
                                               class="travel-icon">n</span> <?php echo JText::_('Children/infant(0-11 years)') ?>
                            </h4>
                        </div>
                    </div>
                    <div class="input-passenger-list-passenger children-infant">
                    </div>
                    <input type="hidden" name="<?php echo $name ?>">
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_room($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_room/html_build_room.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_room/html_build_room.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_room';
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_build_room({
                    list_passenger:<?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_room row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-room">
                    <div class="move-room handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h3><?php echo JText::_('Room ') ?><span class="room-order">1</span></h3>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <h3><?php echo JText::_('Select room type') ?></h3>
                            <div class="list-room">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <label><?php echo JText::_('Single') ?><input type="radio" checked
                                                                                      data-name="room_type"
                                                                                      name="room_type"
                                                                                      value="single"></label>
                                    </div>
                                    <div class="span3">
                                        <label><?php echo JText::_('Double') ?><input type="radio" data-name="room_type"
                                                                                      name="room_type"
                                                                                      value="double"></label>
                                    </div>
                                    <div class="span3"></div>
                                    <div class="span2">
                                        <label><?php echo JText::_('Twin') ?><input type="radio" data-name="room_type"
                                                                                    name="room_type"
                                                                                    value="twin"></label>
                                    </div>
                                    <div class="span2">
                                        <label><?php echo JText::_('Triple') ?><input type="radio" data-name="room_type"
                                                                                      name="room_type"
                                                                                      value="triple"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid note">
                                <div class="span12">
                                    <?php echo $lipsum->words(50) ?>
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
                        <div class="span7">
                            <h3><?php echo JText::_('select person for room on your own') ?></h3>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_room"></span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-room pull-right"><?php echo JText::_('Add more room') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-room pull-right"><?php echo JText::_('Remove room') ?></button>
                        </div>
                    </div>
                </div>
            </div>
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
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
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
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_extra_night_hotel($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $type, $extra_night_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_extra_night_hotel/html_build_extra_night_hotel.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_extra_night_hotel/html_build_extra_night_hotel.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $session = JFactory::getSession();
        $list_passenger = $session->get('list_passenger');
        $list_passenger = json_decode($list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_room_' . $name;
        $type = $type ? $type : 'pre';
        $debug = true;
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
                $('#<?php  echo $id_element ?>').html_build_extra_night_hotel({
                    list_passenger: cookie_list_passenger,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    extra_night_config:<?php echo json_encode($extra_night_config) ?>,
                    type: '<?php echo $type ?>',
                    list_room_type:<?php echo json_encode($list_room_type) ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_night_hotel row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-night-hotel">
                    <div class="move-room handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h3><?php echo JText::_($type . ' ') ?><span class="room-order">1</span>
                            </h3>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <h5><?php echo JText::_('Select date from to') ?></h5>
                            <div class="row-fluid">
                                <div class="span12">
                                    <input style="width: 100%" type="text" class="date">
                                </div>
                            </div>
                            <div class="row-fluid note">
                                <div class="span12">
                                    <?php echo $lipsum->words(50) ?>
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
                        <div class="span7">
                            <h3><?php echo JText::_('select person for room on your own') ?></h3>
                            <div class="list_room_type row-fluid">
                                <div class="span3">
                                </div>
                                <div class="span9">
                                    <div class="row-fluid">
                                        <?php
                                        $room_limit = 16;
                                        ?>
                                        <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                            <div class="span3">
                                                <div class="" style="text-align: center"><?php echo $room_type ?></div>
                                                <select data-room_type="<?php echo $room_type ?>"
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
                            <div class="list_room_type_passenger">
                                <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                    <div class="<?php echo $room_type ?>">
                                        <div class="passenger-item row-fluid <?php echo $room_type ?>"
                                             data-room_type="<?php echo $room_type ?>">
                                            <div class="span2">
                                                <div class="title_room"><?php echo JText::_($room_type) ?></div>
                                            </div>
                                            <div class="span1">
                                                <button type="button" class="btn btn-primary remove_room">X</button>
                                            </div>
                                            <div class="span9">
                                                <?php for ($i = 0; $i < $allow_passenger; $i++) { ?>
                                                    <select disabled style="width: 100%" class="">
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
                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-room pull-right"><?php echo JText::_('Book more') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-room pull-right"><?php echo JText::_('Remove this ' . $type) ?></button>
                        </div>
                    </div>
                </div>
            </div>
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
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
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
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_passenger_summary($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary/html_build_passenger_summary.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary/html_build_passenger_summary.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_passenger_summary' . $name;
        $session = JFactory::getSession();
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        $build_room = $session->get('build_room');
        $build_room = json_decode($build_room);
        foreach ($build_room as $item_room) {
            $tour_cost_and_room_price = $item_room->tour_cost_and_room_price;
            foreach ($tour_cost_and_room_price as $item_price) {
                $passenger_index = $item_price->passenger_index;
                $list_passenger[$passenger_index]->room_type = $item_room->room_type;
                $list_passenger[$passenger_index]->tour_service_price = $item_price->tour_cost;
                $list_passenger[$passenger_index]->room_price = $item_price->room_price;
                $list_passenger[$passenger_index]->extra_bed_price = $item_price->extra_bed_price;
                $list_passenger[$passenger_index]->msg = $item_price->msg;
                $list_passenger[$passenger_index]->bed_note = $item_price->bed_note;
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_passenger_summary({
                    list_passenger: cookie_list_passenger,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_aobject row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <h4
                    style="text-align: center"><?php echo JText::_('Service total') ?></h4>
                <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                    <?php
                    $passenger = $list_passenger[$i];
                    $room_type = $passenger->room_type;
                    ?>
                    <div class="item-aobject">
                        <div class="move-aobject handle"><span title="" class="icon-move "></span></div>
                        <div class="row-fluid">
                            <div class="span12"><h3 class="passenger-title"><?php echo JText::_('passenger ') ?><span
                                        class="aobject-order"><?php echo JText::sprintf("%s :$passenger->first_name $passenger->middle_name $passenger->last_name", $i + 1) ?></span>
                                </h3></div>
                        </div>
                        <div class="passenger-service-list">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="table table-hover table-service-list">
                                        <div class="thead">
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div
                                                        class="column-header-item"><?php echo JText::_('Service detail') ?></div>
                                                </div>
                                                <div class="span6">
                                                    <div class="column-header-item"></div>
                                                </div>
                                                <div class="span2">
                                                    <div
                                                        class="column-header-item"><?php echo JText::_('Service price') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tbody">
                                            <div class="row-fluid div-item-aobject">
                                                <div class="span4">
                                                    <div
                                                        class="row-item-column"><?php echo JText::_('Package tour') ?></div>
                                                </div>
                                                <div class="span6">
                                                    <div class="row-item-column">
                                                        <div
                                                            class="tour-detail"><?php echo JText::sprintf("%s Trip from %s to %s. %s include", $tour->product_name, JHtml::_('date', $booking_date, tsmConfig::$date_format), $end_date->format(tsmConfig::$date_format), $room_type) ?></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="row-item-column">
                                                        <div
                                                            class="tour-price"><?php echo $passenger->tour_service_price ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid div-item-aobject">
                                                <div class="span4">
                                                    <div
                                                        class="row-item-column"><?php echo JText::_('Extra service') ?></div>
                                                </div>
                                                <div class="span6">
                                                    <div class="row-item-column">
                                                        <div class="extra-service"></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="row-item-column">
                                                        <div class="extra-price"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid div-item-aobject">
                                                <div class="offset10 span2">
                                                    <div class="row-item-column">
                                                        <div class="total-price"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_passenger_summary_confirm($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary_confirm/html_build_passenger_summary_confirm.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_passenger_summary_confirm/html_build_passenger_summary_confirm.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_passenger_summary' . $name;
        $session = JFactory::getSession();
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        $build_room = $session->get('build_room');
        $build_room = json_decode($build_room);
        foreach ($build_room as $item_room) {
            $tour_cost_and_room_price = $item_room->tour_cost_and_room_price;
            foreach ($tour_cost_and_room_price as $item_price) {
                $passenger_index = $item_price->passenger_index;
                $list_passenger[$passenger_index]->room_type = $item_room->room_type;
                $list_passenger[$passenger_index]->tour_service_price = $item_price->tour_cost;
                $list_passenger[$passenger_index]->room_price = $item_price->room_price;
                $list_passenger[$passenger_index]->extra_bed_price = $item_price->extra_bed_price;
                $list_passenger[$passenger_index]->msg = $item_price->msg;
                $list_passenger[$passenger_index]->bed_note = $item_price->bed_note;
            }
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_passenger_summary_confirm({
                    list_passenger: cookie_list_passenger,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>
        <div class="html_build_passenger_summary_confirm row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1"><?php echo JText::_('Gender') ?></div>
                        <div class="span3"><?php echo JText::_('Name & surname') ?></div>
                        <div class="span1"><?php echo JText::_('Date of birth') ?></div>
                        <div class="span1"><?php echo JText::_('Nationality') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row-fluid row-item">
                            <div class="span1"><?php echo $passenger->title ?></div>
                            <div class="span3"><?php echo $passenger->first_name ?></div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function edit_passenger_in_order($order, $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $order->order_data=json_decode($order->order_data);
        $list_passenger=$order->order_data->list_passenger;
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_edit_passenger_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_edit_passenger_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    order: <?php echo json_encode($order) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>
        <div class="html_edit_passenger_in_order row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row-fluid">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                    </div>
                </div>
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="span2 column"><?php echo JText::_('Name') ?></div>
                        <div class="span2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="span1 column"><?php echo JText::_('Title') ?></div>
                        <div class="span2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="span1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="span1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="span1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row-fluid row-item" data-passenger_id="<?php echo $i ?>">
                            <div class="span1 column id"><?php echo $i + 1 ?></div>
                            <div class="span2 column"><?php echo $passenger->first_name ?></div>
                            <div class="span2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="span1 column"><?php echo $passenger->title ?></div>
                            <div class="span2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="span1 column"><?php echo $passenger->nationality ?></div>
                            <div class="span1 column"></div>
                            <div class="span1 column"></div>
                            <div class="span1 column">
                                <div class="buttons pull-right">
                                    <button type="button"  class="btn-link edit"><span class="icon-edit"></span></button>
                                    <button type="button" class="btn-link pull-right"><span class="icon-cancel"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- Modal -->
            <div id="edit_passenger" class="modal hide fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <h3 id="myModalLabel"><?php echo JText::_('Edit passenger') ?>:<span class="full-name"></span></h3>
                </div>
                <div class="modal-body form-horizontal">
                    <h4><?php echo JText::_('Passenger detail') ?></h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="title"><?php echo JText::_('Title') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="title" value="Title">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="first_name"><?php echo JText::_('First name') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="first_name" value="First name">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="last_name"><?php echo JText::_('Last name') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="last_name" value="Last name">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="gender"><?php echo JText::_('Gender') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="gender" value="">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date_of_birth"><?php echo JText::_('Date of birth') ?></label>
                                <div class="controls">
                                    <input disabled type="text" name="date_of_birth" value="">
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="passport_no"><?php echo JText::_('Nationality') ?></label>
                                <div class="controls">
                                    <input type="text" name="nationality" id="nationality">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="passport_no"><?php echo JText::_('Passport no') ?></label>
                                <div class="controls">
                                    <input type="text" name="passport_no" id="passport_no">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="p_issue_date"><?php echo JText::_('P. Issue date') ?></label>
                                <div class="controls">
                                    <input type="text" name="p_issue_date" id="p_issue_date">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="p_expiry_date"><?php echo JText::_('P.expiry Date') ?></label>
                                <div class="controls">
                                    <input type="text" name="p_expiry_date" id="p_expiry_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <h4><?php echo JText::_('Contact detail') ?></h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="passport_no"><?php echo JText::_('Phone no') ?></label>
                                <div class="controls">
                                    <input type="text" name="phone_no" id="phone_no">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email"><?php echo JText::_('Email address') ?></label>
                                <div class="controls">
                                    <input type="text" name="res_country" id="res_country">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="confirm_email"><?php echo JText::_('Confirm Email') ?></label>
                                <div class="controls">
                                    <input type="text" name="confirm_email" id="confirm_email">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="street_address"><?php echo JText::_('Street address') ?></label>
                                <div class="controls">
                                    <input type="text" name="street_address" id="street_address">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="suburb_town"><?php echo JText::_('Suburb/Town') ?></label>
                                <div class="controls">
                                    <input type="text" name="suburb_town" id="suburb_town">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="state_province"><?php echo JText::_('State/Province') ?></label>
                                <div class="controls">
                                    <input type="text" name="state_province" id="state_province">
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="postcode_zip"><?php echo JText::_('Postcode/Zip') ?></label>
                                <div class="controls">
                                    <input type="text" name="postcode_zip" id="postcode_zip">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="res_country"><?php echo JText::_('Re. Country') ?></label>
                                <div class="controls">
                                    <input type="text" name="res_country" id="res_country">
                                </div>
                            </div>
                            <h5 class="emergency">
                                <?php echo JText::_('Emergency contact') ?>
                            </h5>
                            <div class="control-group">
                                <label class="control-label" for="emergency_contact_name"><?php echo JText::_('Contact name') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_name" id="emergency_contact_name">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="emergency_contact_email_address"><?php echo JText::_('Email Address') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_email_address" id="emergency_contact_email_address">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"  for="emergency_contact_phone_no"><?php echo JText::_('Phone no') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_phone_no"  id="emergency_contact_phone_no">
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4><?php echo JText::_('addtion information') ?></h4>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label" for="conditions"><?php echo JText::_('Do you have any pre-existing medical condition ?') ?></label>
                                <div class="controls">
                                    <textarea name="conditions" id="conditions" style="width: 80%">
                                    </textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="requirement"><?php echo JText::_('Do you have any pre-existing medical condition ?') ?></label>
                                <div class="controls">
                                    <textarea name="requirement" id="requirement" style="width: 80%">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn close-edit-passenger" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary save-passenger">Save changes</button>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function passenger_information_in_order($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/passenger_information_in_order/html_passenger_information_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/passenger_information_in_order/html_passenger_information_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_passenger_information_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_passenger_information_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_passenger_information_in_order row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
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
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function passenger_rooming_list_in_order($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        return '<h3>rooming list</h3>';
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_edit_passenger_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_edit_passenger_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>
        <div class="html_edit_passenger_in_order row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row-fluid">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                        <div class="buttons pull-right">
                            <button class="btn btn-primary"><?php echo JText::_('Save change') ?></button>
                            <button class="btn btn-primary"><?php echo JText::_('Cancel change') ?></button>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="span2 column"><?php echo JText::_('Name') ?></div>
                        <div class="span2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="span1 column"><?php echo JText::_('Title') ?></div>
                        <div class="span2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="span1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="span1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="span1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row-fluid row-item">
                            <div class="span1 column id"><?php echo $i + 1 ?></div>
                            <div class="span2 column"><?php echo $passenger->first_name ?></div>
                            <div class="span2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="span1 column"><?php echo $passenger->title ?></div>
                            <div class="span2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="span1 column"><?php echo $passenger->nationality ?></div>
                            <div class="span1 column"></div>
                            <div class="span1 column"></div>
                            <div class="span1 column">
                                <div class="buttons pull-right">
                                    <button type="button" class="btn-link pull-left"><span class="icon-edit"></span>
                                    </button>
                                    <button type="button" class="btn-link pull-right"><span class="icon-cancel"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function passenger_transfer($list_transfer = array(), $name = '', $type)
    {
        $doc = JFactory::getDocument();
        $id_element = 'html_edit_passenger_in_order' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $list_list_transfer = array_chunk($list_transfer, 2);
        ob_start();
        ?>
        <div class="passenger_transfer row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <?php
                ?>
                <?php foreach ($list_list_transfer as $list_transfer) { ?>
                    <div class="row-fluid">
                        <?php for ($i = 0; $i < count($list_transfer); $i++) { ?>
                            <div class="span6">
                                <?php
                                $list_room_type = $list_transfer[$i]->list_room_type;
                                $list_room_type1 = array();
                                foreach ($list_room_type as $room) {
                                    $list_room_type1[] = "$room->total_room $room->room_type";
                                }
                                $list_room_type1 = implode(',', $list_room_type1);
                                $check_in = JFactory::getDate();
                                $check_in = JHtml::_('date', $check_in);
                                $check_out = JFactory::getDate();
                                $check_out = JHtml::_('date', $check_out);
                                ?>
                                <h4><?php echo $type == 'pre' ? JText::sprintf('pre transfer %s', $i + 1) : JText::sprintf('post transfer %s', $i + 1) ?></h4>
                                <div class="block">
                                    <?php echo JText::sprintf('%s <br/> check in date: %s,Check out date: %s', $list_room_type1, $check_in, $check_out) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function passenger_hotel_night($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $tour)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_edit_passenger_in_order' . $name;
        $booking_date = $departure->departure_date;
        $total_day = $departure->total_day;
        $total_day--;
        $end_date = JFactory::getDate($booking_date)->modify("+$total_day day");
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_edit_passenger_in_order({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    id_selected:<?php echo $default ? $default : 0 ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>,
                    tour: '<?php echo json_encode($tour) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <h4
        ><?php echo JText::_('Passenger list') ?></h4>
        <div class="html_edit_passenger_in_order row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row-fluid">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                        <div class="buttons pull-right">
                            <button class="btn btn-primary"><?php echo JText::_('Save change') ?></button>
                            <button class="btn btn-primary"><?php echo JText::_('Cancel change') ?></button>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <div class="row-fluid">
                        <div class="span1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="span2 column"><?php echo JText::_('Name') ?></div>
                        <div class="span2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="span1 column"><?php echo JText::_('Title') ?></div>
                        <div class="span2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="span1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="span1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="span1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="span1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row-fluid row-item">
                            <div class="span1 column id"><?php echo $i + 1 ?></div>
                            <div class="span2 column"><?php echo $passenger->first_name ?></div>
                            <div class="span2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="span1 column"><?php echo $passenger->title ?></div>
                            <div class="span2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="span1 column"><?php echo $passenger->nationality ?></div>
                            <div class="span1 column"></div>
                            <div class="span1 column"></div>
                            <div class="span1 column">
                                <div class="buttons pull-right">
                                    <button type="button" class="btn-link pull-left"><span class="icon-edit"></span>
                                    </button>
                                    <button type="button" class="btn-link pull-right"><span class="icon-cancel"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_payment_cardit_card($name, $full_payment, $deposit)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_payment_cardit_card/html_build_payment_cardit_card.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_payment_cardit_card/html_build_payment_cardit_card.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $id_element = 'html_build_payment_cardit_card_' . $name;
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_build_payment_cardit_card({
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_payment_cardit_card row-fluid" id="<?php echo $id_element ?>">
            <fieldset>
                <legend><?php echo JText::_('Payment detail') ?></legend>
                <div class="payment-detail">
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Payment amout: CW') ?>
                        </div>
                        <div class="span4">
                            <label><input type="radio"
                                          name="payment_type"> <?php echo JText::sprintf('$US %s (full payment)', $full_payment) ?>
                            </label>
                        </div>
                        <div class="span4">
                            <label><input type="radio"
                                          name="payment_type"> <?php echo JText::sprintf('$US %s (Deposit)', $deposit) ?>
                            </label>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Card type') ?>
                        </div>
                        <div class="span3">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Visa card') ?></label>
                        </div>
                        <div class="span2">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Master card') ?></label>
                        </div>
                        <div class="span3">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Amex card') ?></label>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Card number') ?>:
                        </div>
                        <div class="span8">
                            <input type="text" class="card_number" name="card_number">
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Expri date') ?>:
                        </div>
                        <div class="span3">
                            <select name="month" class="month">
                                <option value=""><?php echo JText::_('select month') ?></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="span2">
                            <select name="year" class="year">
                                <option value=""><?php echo JText::_('select year') ?></option>
                                <?php for ($i = 2016; $i <= 2020; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="span3">
                            <label class="icon-question"><span></span><input type="text" class="last_three_number"
                                                                             name="last_three_number"></label>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo JText::_('Card holder name') ?>:
                        </div>
                        <div class="span8">
                            <input type="text" class="card_holder_name" name="card_holder_name">
                        </div>
                    </div>
                </div>
            </fieldset>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_form_contact($name, $full_payment, $deposit)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_build_form_contact/html_build_form_contact.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/html_build_form_contact/html_build_form_contact.less');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $id_element = 'html_build_form_contact_' . $name;
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_build_form_contact({
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_form_contact row-fluid" id="<?php echo $id_element ?>">
            <div class="form-contact form-horizontal">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 class="title contact-detail"><span class="icon-location "
                                                               title=""></span><?php echo JText::_('Contact detail') ?>
                        </h4>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('input', JText::_('Contact name'), 'contact_name', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Phone No'), 'phone_number', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Email Address'), 'email_address', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Confirm Email'), 'confirm_email', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Street address'), 'street_address', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Suburb/Town'), 'suburb_town', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('State/province'), 'state_province', '', 'class="required"'); ?>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('input', JText::_('Postcode/Zip'), 'post_code_zip', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Res. Country'), 'res_country', '', 'class="required"'); ?>
                        <h3 class=" emergency-contact"><?php echo JText::_('Emergency contact') ?></h3>
                        <?php echo VmHTML::row_control('input', JText::_('Contact Name'), 'emergency_contact_name', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Street address'), 'emergency_email_address', '', 'class="required"'); ?>
                        <?php echo VmHTML::row_control('input', JText::_('Phone No'), 'emergency_phone_number', '', 'class="required"'); ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function build_pickup_transfer($name, $list_passenger = array(), $default = '0', $departure, $passenger_config, $transfer_item_config)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_pickup_transfer/html_build_pickup_transfer.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_pickup_transfer/html_build_pickup_transfer.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_pickup_transfer_' . $name;
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_pickup_transfer({
                    list_passenger: cookie_list_passenger,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    transfer_item_config:<?php echo json_encode($transfer_item_config) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_pickup_transfer row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_transfer">
                <div class="item-transfer">
                    <div class="move-transfer handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h4><?php echo JText::_('transfer ') ?><span class="transfer-order">1</span>
                            </h4></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div
                                class="air-price"><?php echo JText::sprintf('price<br/> from <span class="price">%d</span> /pers', $transfer_item->sale_price) ?></div>
                        </div>
                        <div class="span8">
                            <h5><?php echo JText::_('Air port pickup & transfer') ?></h5>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_transfer"></span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="transfer-fluid note">
                        <div class="span12">
                            <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                    <button type="button" class="btn btn-primary random-text">Random text
                                    </button><?php } ?></h4>
                            <textarea data-name="transfer_note" style="width: 96%;height: 50px"></textarea>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-transfer pull-right"><?php echo JText::_('Add more transfer') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-transfer pull-right"><?php echo JText::_('Remove transfer') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="transfering-list">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 style="text-align: center"><?php echo JText::_('transfering list') ?></h4>
                        <div class="table table-hover table-bordered table-transfering-list">
                            <div class="thead">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer type') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row-fluid div-item-transfer">
                                    <div class="span2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_type"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="private-transfer"></div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_note"></div>
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
    public static function build_excursion_addon($name, $list_passenger = array(), $default = '0', $departure, $passenger_config, $list_excursion_addon)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $json_list_passenger = json_decode($json_list_passenger);
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_excursion_addon/html_build_excursion_addon.js');
        $doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_excursion_addon/html_build_excursion_addon.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_pickup_transfer_' . $name;
        $debug = true;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_excursion_addon({
                    list_passenger: cookie_list_passenger,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    list_excursion_addon:<?php echo json_encode($list_excursion_addon) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_pickup_transfer row-fluid" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_transfer">
                <div class="item-transfer">
                    <div class="move-transfer handle"><span title="" class="icon-move "></span></div>
                    <div class="row-fluid">
                        <div class="span12"><h4><?php echo JText::_('transfer ') ?><span class="transfer-order">1</span>
                            </h4></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div
                                class="air-price"><?php echo JText::sprintf('price<br/> from <span class="price">%d</span> /pers', $list_excursion_addon->sale_price) ?></div>
                        </div>
                        <div class="span8">
                            <h5><?php echo JText::_('Air port pickup & transfer') ?></h5>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_transfer"></span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="transfer-fluid note">
                        <div class="span12">
                            <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                    <button type="button" class="btn btn-primary random-text">Random text
                                    </button><?php } ?></h4>
                            <textarea data-name="transfer_note" style="width: 96%;height: 50px"></textarea>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <button type="button"
                                    class="btn btn-primary add-more-transfer pull-right"><?php echo JText::_('Add more transfer') ?></button>
                            <button type="button"
                                    class="btn btn-primary remove-transfer pull-right"><?php echo JText::_('Remove transfer') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="transfering-list">
                <div class="row-fluid">
                    <div class="span12">
                        <h4 style="text-align: center"><?php echo JText::_('transfering list') ?></h4>
                        <div class="table table-hover table-bordered table-transfering-list">
                            <div class="thead">
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer type') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="span3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="span2">
                                        <div class="column-header-item"><?php echo JText::_('transfer note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row-fluid div-item-transfer">
                                    <div class="span2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_type"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="row-item-column">
                                            <div class="private-transfer"></div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-item-column">
                                            <div class="transfer_note"></div>
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
>>>>>>> local
