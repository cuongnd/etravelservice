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
    public static function product_display($name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $options = array();
        for ($i = 1; $i <= 10; $i++) {
            $options[] = array($key => "$i", $text => $i);
        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function select_room($name = "tsmart_room_id", $list_room, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $hotel_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_room/html_select_room.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_room/html_select_room.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_room)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmroom.php';
            $list_room = tsmroom::get_list_room();
        }
        ob_start();
        ?>
        <script type="text/javascript" xmlns="http://www.w3.org/1999/html">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_room({
                    element_name: "<?php echo $name ?>",
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
        $name = reset($args);
        $html = ' <div id="control-group-' . $name . '" class="control-group ">' . $label;
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
    static function row_control_horizontal($func, $label, $option)
    {
        $VmHTML = "VmHtml";
        if (!is_array($func)) {
            $func = array($VmHTML, $func);
        }
        $passedArgs = func_get_args();
        array_shift($passedArgs);//remove function
        array_shift($passedArgs);//remove label
        array_shift($passedArgs);//remove option
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
        $label = '<label class="control-label ' . $option['class_label'] . '">' . $label . '</label>';
        $name = reset($args);
        $html = ' <div id="control-group-' . $name . '" class="control-group row ">' . $label;
        if ($func[1] == 'radioList') {
            $html .= '<fieldset class="checkboxes">';
        }
        $html .= '
		 <div class="controls ' . $option['class_control_group'] . '">
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
            JHtml::_('formbehavior.chosen');
            $attrib .= ' class="vm-chzn-select"';
        }
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function select_state_province($name, $list_state, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $country_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_state_province/html_select_state_province.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_state_province/html_select_state_province.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_state)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmstates.php';
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
    public static function location_city($name, $default = '0')
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_location_city/html_select_location_city.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_location_city/html_select_location_city.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmcities.php';
        $cities = tsmcities::get_cities();
        $option = array('vituemart_cityarea_id' => '', 'full_city' => 'Please select location');
        array_unshift($cities, $option);
        $id_element = 'html_select_location_city_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_location_city({
                    list_location_city:<?php echo json_encode($cities) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_cityarea_id:<?php echo $default ? $default : 0 ?>
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
            <select multiple disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>">
                <option value=""><?php echo JText::_('please select location') ?></option>
                <?php foreach ($cities as $city) { ?>
                    <option <?php echo $city->tsmart_cityarea_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $city->tsmart_cityarea_id ?>"
                    ><?php echo $city->full_city ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_tour_type($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_tour_type/html_select_tour_type.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_tour_type/html_select_tour_type.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmtourtype.php';
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
                        data-price_type="<?php echo $tour_type->price_type ?>"><?php echo $tour_type->tour_type_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_tour_style($name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_tour_style/html_select_tour_style.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_tour_style/html_select_tour_style.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmtourstyle.php';
        $list_tour_style = tsmtourstyle::get_list_tour_style();
        $id_element = 'html_select_tour_style_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_tour_style({
                    list_tour_style:<?php echo json_encode($list_tour_style) ?>,
                    select_name: "<?php echo $name ?>",
                    tsmart_tour_style_id:<?php echo $default ? $default : 0 ?>
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
                <?php foreach ($list_tour_style as $tour_style) { ?>
                    <option <?php echo $tour_style->tsmart_tour_style_id == $default ? ' selected ' : '' ?>
                        value="<?php echo $tour_style->tsmart_tour_style_id ?>"
                    ><?php echo $tour_style->tour_style_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_number_passenger($name, $text_header = '', $min = 0, $max = 100, $default = '0', $attrib = "onchange='submit();'", $template_result = "%s", $template_selection = "%s", $disable_select)
    {
        if (!$text_header) {
            $text_header = "Passenger from 12 years old";
        }
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('components/com_tsmart/assets/js/plugin/jquery-sprintf/jquery.sprintf.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_number_passenger/html_select_number_passenger.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_number_passenger/html_select_number_passenger.less');
        $input = JFactory::getApplication()->input;
        $list_number = range($min, $max, 1);
        $element_id = "select_number_passenger_$name";
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id ?>').html_select_number_passenger({
                    element_name: "<?php echo $name ?>",
                    list_number:<?php echo json_encode($list_number) ?>,
                    number_selected:<?php echo $default ? $default : 0 ?>,
                    placeholder: "<?php echo $text_header ?>",
                    template_result: "<?php echo $template_result ?>",
                    template_selection: "<?php echo $template_selection ?>",
                    disable_select:<?php echo json_encode($disable_select) ?>
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
            <select disable_chosen="true" <?php echo $disable_select ? ' disabled ' : '' ?> id="<?php echo $name ?>" name="<?php echo $name ?>" <?php echo $attrib ?> >
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/html_select_tour/html_select_tour.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/html_select_tour/html_select_tour.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmproduct.php';
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_trip_join_and_private/select_trip_join_and_private.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_trip_join_and_private/select_trip_join_and_private.less');
        $input = JFactory::getApplication()->input;
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmserviceclass.php';
        $list_service_class = tsmserviceclass::get_list_tour_service_class();
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmproduct.php';
        $list_products = tsmproduct::get_list_product();
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmdeparture.php';
        $list_departure = tsmDeparture::get_list_departure();
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmtourtype.php';
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
            <div class="row">
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
            <div class="row">
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
            <div class="row air-service-class">
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
            <div class="row air-departure">
                <div class="<?php echo $span_left ?>">
                    <?php echo JText::_('Select departure item') ?>
                </div>
                <div class="<?php echo $span_right ?>">
                    <div class="list_departure">
                        <?php foreach ($list_departure as $departure) {
                            ?>
                            <div class="row departure-item"
                                 data-tsmart_departure_id="<?php echo $departure->tsmart_departure_id ?>">
                                <div class="col-lg-6">
                                    <label>Departure:<?php echo JHtml::_('date', $departure->departure_date, tsmConfig::$date_format); ?>
                                        <input type="checkbox" value="<?php echo $departure->tsmart_departure_id ?>"
                                               name="departure[]"></label></div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4">Code:<?php echo $departure->departure_code ?></div>
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
    public static function select_city($name = "tsmart_cityarea_id", $list_city, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $state_element = '', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_city/html_select_city.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_city/html_select_city.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_city)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmcities.php';
            $list_city = tsmcities::get_cities();
        }
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').html_select_city({
                    element_name: "<?php echo $name ?>",
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
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_percent_amount/html_select_percent_amount.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_percent_amount/html_select_percent_amount.less');
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
            <div class="row">
                <div class="col-lg-6">
                    <input type="text" class="auto percent_input" <?php echo $type == 'amount' ? 'disabled' : '' ?>
                           value="<?php echo $type == 'percent' ? $amount : '' ?>" data-a-sign="%" data-v-min="0"
                           data-v-max="100" placeholder="write percent">
                </div>
                <div class="col-lg-6">
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
        $doc->addStyleSheet("/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css");
        $doc->addStyleSheet("/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css");
        $doc->addScript('/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_from_to/html_select_from_to.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_from_to/html_select_from_to.less');
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
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_range_of_date/html_select_range_of_date.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_range_of_date/html_select_range_of_date.less');
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
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet('/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript('/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript('/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript('/media/system/js/jquery.maskedinput-master/dist/jquery.maskedinput.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_date/html_select_date.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_date/html_select_date.less');
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
            <div class="input-group">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_date_picker_<?php echo $name ?>" class="form-control select_date <?php echo $class ?>"/>
                <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
                <span class="input-group-addon"><span class="icon-calendar add-on"></span></span>
            </div>
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function select_date_time($name, $value_selected = '', $format = 'mm/dd/yy', $view_format = 'mm/dd/yy', $min_date = '', $max_date = '', $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');
        $doc = JFactory::getDocument();
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet('/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addLessStyleSheet('/components/com_tsmart/assets/js/plugin/Slick-Datetime-Picker/slick_dtp.less');
        $doc->addScript('/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/dialog.js');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/button.js');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/Slick-Datetime-Picker/slick_dtp.js');
        $doc->addScript('/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript('/media/system/js/jquery.maskedinput-master/dist/jquery.maskedinput.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_date_time/html_select_date_time.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_date_time/html_select_date_time.less');
        $input = JFactory::getApplication()->input;
        $select_date = 'select_date_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_date ?>').html_select_date_time({
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
        <div id="<?php echo $select_date ?>" class="select_date_time">
            <div class="input-group">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_date_time_picker_<?php echo $name ?>" class="form-control select_date_time <?php echo $class ?>"/>
                <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
                <span class="input-group-addon"><span class="icon-calendar add-on"></span></span>
            </div>
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function select_month($name, $value_selected = '', $format = 'MM/YYYY', $view_format = 'MM/YYYY', $min_month = 1, $max_month = 12, $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');
        $doc = JFactory::getDocument();
        $doc->addScript('/media/jquery-ui-1.11.1/ui/button.js');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/monthpicker.js');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet('/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript('/media/system/js/jquery-ui-month-picker-master/src/MonthPicker.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_month/html_select_month.js');
        $doc->addLessStyleSheet('/media/system/js/jquery-ui-month-picker-master/src/MonthPicker.css');
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
            <div class="input-group">
                <input type="text" value="<?php echo $value_selected ?>" <?php echo $attrib ?>
                       id="select_month_picker_<?php echo $name ?>" class="input-group-addon select_month <?php echo $class ?>"/>
                <span class="input-group-addon icon-calendar add-on"></span>
            </div>
            <input type="hidden" value="<?php echo $value_selected ?>" class="" name="<?php echo $name ?>">
        </div>
        <?php
        $htm = ob_get_clean();
        return $htm;
    }
    public static function photo_store($name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $options = array();
        $options[] = array($key => "header", $text => tsmText::_('header'));
        $options[] = array($key => "gallery", $text => tsmText::_('gallery'));
        $options[] = array($key => "reviews", $text => tsmText::_('reviews'));
        return VmHtml::genericlist($options, $name, $attrib, $key, $text, $default, false, $tranlsate);
    }
    public static function file_browser($name, $value, $attri = "", $readonly = '', $size = '30', $maxlength = '255', $more = '')
    {
        ob_start();
        ?>
        <input type="file" name="<?php echo $name ?>"/>
        <?php echo $value ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function edit_price_add_on($name, $data = '')
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/base64.js');
        $doc->addScript("/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript('components/com_tsmart/assets/js/controller/edit_price_add_on/html_edit_price_add_on.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/edit_price_add_on/html_edit_price_add_on.less');
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
            <table class="table edit_price">
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
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/base64.js');
        $doc->addScript("/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript('components/com_tsmart/assets/js/controller/edit_price_hotel_add_on/html_edit_price_hotel_add_on.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/edit_price_hotel_add_on/html_edit_price_hotel_add_on.less');
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
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_amount_percent/html_select_amount_percent.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_amount_percent/html_select_amount_percent.less');
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
            <div class="row">
                <div class="col-lg-6">
                    <input type="text" value="<?php echo $amount ?>" class="auto amount_input" data-v-min="0"
                           data-v-max="9999" placeholder="write of No day">
                </div>
                <div class="col-lg-6">
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
        $doc->addScript('components/com_tsmart/assets/js/controller/html_select_add_on.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/html_select_add_on.less');
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
    public static function list_checkbox_tour($name, $list_selected = array(), $column = 3)
    {
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmproduct.php';
        $list_products = tsmproduct::get_list_product();
        $html = '';
        $list_options = array_chunk($list_products, $column);
        ob_start();
        ?>
        <?php foreach ($list_options as $options) { ?>
        <div class="row">
            <?php foreach ($options as $option) { ?>
                <div class="col-lg-<?php echo round(12 / $column) ?>">
                    <label class="checkbox">
                        <input
                            name="<?php echo $name ?>[]" <?php echo in_array($option->tsmart_product_id, $list_selected) ? 'checked' : '' ?>
                            value="<?php echo $option->tsmart_product_id ?>" type="checkbox"> <?php echo $option->product_name ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_table_tour($name, $list_products = array(), $list_selected = array(), $multiple = true)
    {
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/select_table_tour/style.less');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/controller/select_table_tour/select_table_tour.js');
        if (empty($list_products)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmproduct.php';
            $list_products = tsmproduct::get_list_product();
        }
        $html = '';
        $id_element = "select_table_tour_" . $name;
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="check-table-row">
            <div class="head">
                <div class="tr-row">
                    <div class="column no"><span class="item"><?php echo JText::_('no') ?></span></div>
                    <div class="column id"><span class="item"><?php echo JText::_('id') ?></span></div>
                    <div class="column trip_name"><span class="item"><?php echo JText::_('trip name') ?></span></div>
                    <div class="column application"><span class="item"><?php echo JText::_('application') ?></span>
                    </div>
                </div>
            </div>
            <div class="body">
                <?php foreach ($list_products as $product) { ?>
                    <div class="tr-row" data-tsmart_product_id="<?php echo $product->tsmart_product_id ?>">
                        <div class="column no"><span class="item"><?php echo $product->tsmart_product_id ?></span></div>
                        <div class="column id"><span class="item"><?php echo $product->product_code ?></span></div>
                        <div class="column trip_name"><span class="item"><?php echo $product->product_name ?></span>
                        </div>
                        <div class="column application">
                            <input class="input-application"
                                   name="<?php echo $name ?><?php echo $multiple ? '[]' : '' ?>" <?php echo in_array($product->tsmart_product_id, $list_selected) ? 'checked' : '' ?>
                                   value="<?php echo $product->tsmart_product_id ?>" type="<?php echo $multiple ? 'checkbox' : 'radio' ?>">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').select_table_tour({
                    list_products:<?php echo json_encode($list_products) ?>

                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $html = ob_get_clean();
        return $html;
    }
    public static function select_table_service_class($name, $list_service_class = array(), $list_selected = array())
    {
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/select_table_service_class/style.less');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/controller/select_table_service_class/select_table_service_class.js');
        if (empty($list_service_class)) {
            require_once JPATH_ROOT . DS . 'components/com_tsmart/helpers/tsmserviceclass.php';
            $list_service_class = tsmserviceclass::get_list_tour_service_class();
        }
        $html = '';
        $id_element = "select_table_service_class_" . $name;
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="select-table-service-class">
            <div class="head">
                <div class="tr-row">
                    <div class="column no"><span class="item"><?php echo JText::_('no') ?></span></div>
                    <div class="column id"><span class="item"><?php echo JText::_('id') ?></span></div>
                    <div class="column trip_name"><span class="item"><?php echo JText::_('service class name') ?></span>
                    </div>
                    <div class="column application"><span class="item"><?php echo JText::_('application') ?></span>
                    </div>
                </div>
            </div>
            <div class="body">
                <?php foreach ($list_service_class as $service_class) { ?>
                    <div class="tr-row" data-tsmart_service_class_id="<?php echo $service_class->tsmart_service_class_id ?>">
                        <div class="column no">
                            <span class="item"><?php echo $service_class->tsmart_service_class_id ?></span></div>
                        <div class="column id"><span class="item"><?php echo $service_class->product_name ?></span>
                        </div>
                        <div class="column trip_name">
                            <span class="item"><?php echo $service_class->service_class_name ?></span></div>
                        <div class="column application">
                            <input
                                name="<?php echo $name ?>[]" class="input-application" <?php echo in_array($service_class->tsmart_service_class_id, $list_selected) ? 'checked' : '' ?>
                                value="<?php echo $service_class->tsmart_service_class_id ?>" type="checkbox">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').select_table_service_class({
                    list_service_class:<?php echo json_encode($list_service_class) ?>

                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        return $html;
    }
    public static function select_table_departure_date($name, $list_departure_date = array(), $list_selected = array())
    {
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/select_table_departure_date/style.less');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/controller/select_table_departure_date/select_table_departure_date.js');
        if (empty($list_departure_date)) {
            require_once JPATH_ROOT . DS . 'components/com_tsmart/helpers/tsmdeparture.php';
            $list_departure_date = tsmDeparture::get_list_departure_exclude_parent_departure();
        }
        $html = '';
        $id_element = "select_table_departure_date_" . $name;
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="select-table-departure-date">
            <div class="head">
                <div class="tr-row">
                    <div class="column no"><span class="item"><?php echo JText::_('no') ?></span></div>
                    <div class="column id"><span class="item"><?php echo JText::_('id') ?></span></div>
                    <div class="column trip_name"><span class="item"><?php echo JText::_('departure name') ?></span>
                    </div>
                    <div class="column service_class_name">
                        <span class="item"><?php echo JText::_('Service class name') ?></span></div>
                    <div class="column application"><span class="item"><?php echo JText::_('application') ?></span>
                    </div>
                </div>
            </div>
            <div class="body">
                <?php foreach ($list_departure_date as $departure) { ?>
                    <div class="tr-row" data-tsmart_departure_id="<?php echo $departure->tsmart_departure_id ?>">
                        <div class="column no"><span class="item"><?php echo $departure->tsmart_departure_id ?></span>
                        </div>
                        <div class="column id"><span class="item"><?php echo $departure->departure_code ?></span></div>
                        <div class="column departure_name">
                            <span class="item"><?php echo $departure->departure_name ?></span></div>
                        <div class="column service_class_name">
                            <span class="item"><?php echo $departure->service_class_name ?></span></div>
                        <div class="column application">
                            <input
                                name="<?php echo $name ?>[]" class="input-application" <?php echo in_array($departure->tsmart_departure_id, $list_selected) ? 'checked' : '' ?>
                                value="<?php echo $departure->tsmart_departure_id ?>" type="checkbox">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').select_table_departure_date({
                    list_departure_date:<?php echo json_encode($list_departure_date) ?>

                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        return $html;
    }
    public static function list_checkbox($name, $options, $list_selected = array(), $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $column = 3)
    {
        $doc = JFactory::getDocument();
        $list_options = array_chunk($options, $column);
        ob_start();
        $id_element = "list_checkbox" . TSMUtility::clean($name);
        ?>
        <div id="<?php echo $id_element ?>" class="list_checkbox">
            <?php foreach ($list_options as $options) { ?>
                <div class="row">
                    <?php foreach ($options as $option) { ?>
                        <div class="col-lg-<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>[]" <?php echo in_array($option->$key, $list_selected) ? 'checked' : '' ?>
                                    value="<?php echo $option->$key ?>" type="checkbox"> <?php echo $option->$text ?>
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
    public static function allow_passenger($name, $list_selected = array(), $attrib = "onchange='submit();'", $column = 3)
    {
        $list_passenger = array(
            senior => JText::_('senior'),
            adult => JText::_('adult'),
            teen => JText::_('teen'),
            child_1 => JText::_('child_1'),
            child_2 => JText::_('child_2'),
            infant => JText::_('infant')
        );
        $list_options = array_chunk($list_passenger, $column);
        ob_start();
        ?>
        <?php foreach ($list_options as $list_passenger) { ?>
        <div class="row">
            <?php foreach ($list_passenger as $key => $person) { ?>
                <div class="col-lg-<?php echo round(12 / $column) ?>">
                    <label class="checkbox">
                        <input
                            name="<?php echo $name ?>[]" <?php echo in_array($key, $list_selected) ? 'checked' : '' ?>
                            value="<?php echo $key ?>" type="checkbox"> <?php echo $person ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function number_state($name, $min = 0, $max = 100, $current = 50, $current_color = '#990100', $full_color = '#ff9900')
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/controller/number_state/jquery.number_state.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/number_state/style.number_state.less');
        $id_element = "number_state_$name";
        $left = (($current - $min) / ($max - $min)) * 100;
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="number-state">
            <div class="slider-number-state">
                <div class="top">
                    <div class="min"><?php echo $min ?></div>
                    <div class="current pull-left" style="width: <?php echo $left ?>%; ?>"><?php echo $current ?></div>
                    <div class="max"><?php echo $max ?></div>
                </div>
                <div class="bottom" style="background:<?php echo $full_color ?> ">
                    <div class="current pull-left" style="width: <?php echo $left ?>%;background: <?php echo $current_color ?>">
                        &nbsp;</div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').number_state({
                    min:<?php echo (int)$min ?>,
                    max:<?php echo (int)$max ?>,
                    current:<?php echo (int)$current ?>,
                    left_color: "<?php echo $current_color ?>",
                    right_color: "<?php echo $full_color ?>"
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        return $html;
    }
    public static function list_checkbox_group_size($name, $list_selected = array(), $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true, $column = 3)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/controller/list_checkbox_group_size/html_list_checkbox_group_size.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/list_checkbox_group_size/html_list_checkbox_group_size.less');
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmgroupsize.php';
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
                <div class="row">
                    <?php foreach ($list_group_size as $group_size) { ?>
                        <div class="col-lg-<?php echo round(12 / $column) ?>">
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
        $doc->addScript('components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/list_radio/style.less');
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
                <div class="row">
                    <?php foreach ($options as $option) { ?>
                        <div class="col-lg-<?php echo round(12 / $column) ?>">
                            <div class="item-check">
                                <div class="title"><label for="<?php echo $name ?>"><?php echo $option->$text ?></label>
                                </div>
                                <label class="checkbox">
                                    <input
                                        name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                        value="<?php echo $option->$key ?>" type="radio">
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_radio_rooming($name, $options, $selected = 0, $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $column = 3, $debug = false)
    {
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/list_radio_rooming/style.less');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/controller/list_radio_rooming/jquery.list_radio_rooming.js');
        $id_element = "list-radio-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').list_radio_rooming({
                    element_name: "<?php echo $name ?>",
                    debug: false
                });
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
        <div id="<?php echo $id_element ?>" class="list-radio-box list_radio_rooming">
            <?php foreach ($list_options as $options) { ?>
                <div class="row">
                    <?php foreach ($options as $option) { ?>
                        <div class="col-lg-<?php echo round(12 / $column) ?> col-xxxs-<?php echo round(12 / $column) ?>">
                            <div class="item-check">
                                <div class="title pull-left">
                                    <label for="<?php echo $name ?>"><?php echo $option->$text ?></label></div>
                                <label class="checkbox pull-left">
                                    <input
                                        name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                        value="<?php echo $option->$key ?>" type="radio">
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function list_check_rooming($name, $options, $selected = 0, $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $column = 3, $debug = false)
    {
        TSMHtmlJquery::ui();
        $doc = JFactory::getDocument();
        $doc->addScript('/components/com_tsmart/assets/js/plugin/jquery-ui-1.11.1/ui/effect.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet('/components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
        $doc->addLessStyleSheet('/components/com_tsmart/assets/js/controller/list_check_rooming/style.list_check_rooming.less');
        $doc->addScript('/components/com_tsmart/assets/js/controller/list_check_rooming/jquery.list_check_rooming.js');
        $id_element = "list-check-box-$name";
        $total_option = count($options);
        $column = $total_option < $column ? $total_option : $column;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').list_check_rooming({
                    element_name: "<?php echo $name ?>",
                    debug: false
                });
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
        <div id="<?php echo $id_element ?>" class="list-check-box list_check_rooming">
            <?php foreach ($list_options as $options) { ?>
                <div class="row">
                    <?php foreach ($options as $option) { ?>
                        <div class="col-lg-<?php echo round(12 / $column) ?> col-xxxs-<?php echo round(12 / $column) ?>">
                            <div class="item-check">
                                <div class="title pull-left">
                                    <label for="<?php echo $name ?>"><?php echo $option->$text ?></label></div>
                                <label class="checkbox pull-left">
                                    <input
                                        name="<?php echo $name ?>[]" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                        value="<?php echo $option->$key ?>" type="checkbox">
                                </label>
                            </div>
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
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmprice.php';
        $options = vmprice::get_list_price_type();
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/plugin/checkator-master/fm.checkator.jquery.less');
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
        <div id="<?php echo $id ?>" class="list-radio-box price_type">
            <?php foreach ($list_options as $options) { ?>
                <div class="row">
                    <?php foreach ($options as $option) { ?>
                        <div class="col-lg-<?php echo round(12 / $column) ?>">
                            <label class="checkbox">
                                <input
                                    name="<?php echo $name ?>" <?php echo $option->$key == $selected ? 'checked' : '' ?>
                                    value="<?php echo $option->$key ?>" type="radio">
                                <br/>
                            </label>
                            <div class="label"><?php echo $option->$text ?></div>
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
        $doc->addLessStyleSheet('components/com_tsmart/assets/plugins/tag/style.less');
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
                <div class="col-lg-<?php echo round(12 / $column) ?>">
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
        $doc->addScript('/media/system/js/jQuery-Plugin-For-Bootstrap-Button-Group-Toggles/select-toggleizer.js');
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
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/chosen_v1.6.2/chosen.jquery.js');
        $doc->addStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/plugin/chosen_v1.6.2/chosen.css');
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
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select[name="<?php echo $name ?>"]').chosen({});
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
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
        return JHtml::_('select.booleanlist', $name, $class, $value, JText::_('Active'), JText::_('Unactive'));
    }
    public static function bootstrap_activelist($name, $value, $class = 'class="inputbox"')
    {
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/bootstrap_activelist/bootstrap_activelist.less');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/controller/bootstrap_activelist/bootstrap_activelist.js');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/icheck-1.x/icheck.js');
        $doc->addStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/plugin/icheck-1.x/skins/all.css');
        ob_start();
        $id_element = 'bootstrap_activelist_' . $name;
        require_once JPATH_ROOT . 'components/com_tsmart/helpers/utility.php';
        $id_element = TSMUtility::clean($id_element);
        $clean_name = TSMUtility::clean($name);
        ?>
        <div id="<?php echo $id_element ?>" class="bootstrap_activelist">
            <ul class="list">
                <li>
                    <input type="radio" id="<?php echo $clean_name ?>-minimal-radio-1" name="<?php echo $name ?>" value="<?php echo $value ?>" <?php echo $value == false ? 'checked' : '' ?>>
                    <label for="<?php echo $clean_name ?>-minimal-radio-1"><?php echo JText::_('Active') ?></label>
                </li>
                <li>
                    <input type="radio" id="<?php echo $clean_name ?>-minimal-radio-2" name="<?php echo $name ?>" <?php echo $value == true ? 'checked' : '' ?> >
                    <label for="<?php echo $clean_name ?>-minimal-radio-2"><?php echo JText::_('Unactive') ?></label>
                </li>
            </ul>
        </div>
        <?php
        $html = ob_get_clean();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').bootstrap_activelist({});
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
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
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
    public static function balance_term($name_balance_of_day, $name_percent_balance_of_day, $value_balance_of_day = 0, $value_percent_balance_of_day, $number_min = 0, $number_max = 1000, $percent_min = 0, $percent_max = 100, $readonly = false)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/balance_term/jquery.balance_term.js');
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/balance_term/style.balance_term.less');
        $id_element = "balance_term_" . $name_balance_of_day . '_' . $name_percent_balance_of_day;
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').balance_term({
                    name_balance_of_day: "<?php echo $name_balance_of_day ?>",
                    name_percent_balance_of_day: "<?php echo $name_percent_balance_of_day ?>"
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="balance-term">
            <input type="text" value="<?php echo $value_balance_of_day ?>" <?php echo $readonly ? 'readonly' : '' ?>
                   class="inputbox inputbox_number    input_number_<?php echo $name_balance_of_day ?>  pull-left" data-v-min="<?php echo $number_min ?>"
                   data-v-max="<?php echo $number_max ?>"
            >
            <input type="text" value="<?php echo $value_percent_balance_of_day ?>" <?php echo $readonly ? 'readonly' : '' ?>
                   class="inputbox inputbox_percent    input_number_<?php echo $name_percent_balance_of_day ?>    pull-left" data-v-min="<?php echo $percent_min ?>"
                   data-v-max="<?php echo $percent_max ?>" data-a-sign="%"
            >
            <input type="hidden" class="" value="<?php echo $value_balance_of_day ?>" name="<?php echo $name_balance_of_day ?>" id="<?php echo $name_balance_of_day ?>">
            <input type="hidden" class="" value="<?php echo $value_percent_balance_of_day ?>" name="<?php echo $name_percent_balance_of_day ?>" id="<?php echo $name_percent_balance_of_day ?>">
        </div>
        <?php
        return ob_get_clean();
    }
    public static function range_of_integer($name_input_from, $name_input_to, $value_input_from = 0, $value_input_to, $number_min_input_from = 0, $number_max_input_from = 1000, $number_min_input_to = 0, $number_max_input_to = 1000, $readonly = false)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/range_of_integer/jquery.range_of_integer.js');
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/range_of_integer/style.range_of_integer.less');
        $id_element = "range_of_integer_" . $name_input_from . '_' . $name_input_to;
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').range_of_integer({
                    name_input_from: "<?php echo $name_input_from ?>",
                    name_input_to: "<?php echo $name_input_to ?>"
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="range_of_interger">
            <input type="text" value="<?php echo $value_input_from ?>" <?php echo $readonly ? 'readonly' : '' ?>
                   class="inputbox inputbox_number    input_number_<?php echo $name_input_from ?>  pull-left" data-v-min="<?php echo $number_min_input_from ?>"
                   data-v-max="<?php echo $number_max_input_from ?>"
            >
            <div class="to"><?php echo JText::_('To') ?></div>
            <input type="text" value="<?php echo $value_input_to ?>" <?php echo $readonly ? 'readonly' : '' ?>
                   class="inputbox inputbox_percent    input_number_<?php echo $name_input_to ?>    pull-left" data-v-min="<?php echo $number_min_input_to ?>"
                   data-v-max="<?php echo $number_max_input_to ?>"
            >
            <input type="hidden" class="" value="<?php echo $value_input_from ?>" name="<?php echo $name_input_from ?>" id="<?php echo $name_input_from ?>">
            <input type="hidden" class="" value="<?php echo $value_input_to ?>" name="<?php echo $name_input_to ?>" id="<?php echo $name_input_to ?>">
        </div>
        <?php
        return ob_get_clean();
    }
    public static function hold_seat_option($name_hold_seat, $name_hold_seat_hours, $value_hold_seat = 0, $value_hold_seat_hours)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/controller/hold_seat_option/jquery.hold_seat_option.js');
        $doc->addLessStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/controller/hold_seat_option/style.hold_seat_option.less');
        $id_element = "hold_seat_option_" . $name_hold_seat . '_' . $name_hold_seat_hours;
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $id_element ?>').hold_seat_option({
                    name_hold_seat: "<?php echo $name_hold_seat ?>",
                    name_hold_seat_hours: "<?php echo $name_hold_seat_hours ?>"
                });
            });
        </script>
        <?php
        $js_content = ob_get_clean();
        $js_content = TSMUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        $list_hold_seat_hours = array(6, 12, 18, 24, 30, 36, 42, 48);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="hold_seat_option">
            <select class="pull-left" id="<?php echo $name_hold_seat ?>" name="<?php echo $name_hold_seat ?>">
                <option value=""><?php echo JText::_('please select') ?></option>
                <option <?php echo $value_hold_seat == 1 ? 'selected' : '' ?> value="1"><?php echo JText::_('Yes') ?></option>
                <option <?php echo $value_hold_seat == 0 ? 'selected' : '' ?> value="0"><?php echo JText::_('no') ?></option>
            </select>
            <select <?php echo $value_hold_seat == 1 ? '' : 'disabled' ?> class="pull-left" id="<?php echo $name_hold_seat_hours ?>" name="<?php echo $name_hold_seat_hours ?>">
                <option value=""><?php echo JText::_('please select hours') ?></option>
                <?php foreach ($list_hold_seat_hours as $hours) { ?>
                    <option <?php echo $value_hold_seat_hours == $hours ? 'selected' : '' ?> value="<?php echo $hours ?>"><?php echo $hours ?>
                        h
                    </option>
                <?php } ?>
            </select>
        </div>
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
        $doc->addScript('media/system/js/jquery-locationpicker-plugin-master/src/locationpicker.jquery.js');
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('components/com_tsmart/assets/js/plugin/chosen_v1.6.2/chosen.jquery.js');
        $doc->addStyleSheet('components/com_tsmart/assets/js/plugin/chosen_v1.6.2/chosen.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_service_class/html_select_service_class.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_service_class/html_select_service_class.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_select_service_class_' . $name;
        if (empty($list_service_class)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmserviceclass.php';
            $list_service_class = tsmserviceclass::get_list_service_class();
        }
        $option = array('tsmart_service_class_id' => '', 'service_class_name' => 'Please select service class');
        array_unshift($list_service_class, $option);
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
        $html = VmHtml::genericlist($list_service_class, $name, $attrib, 'tsmart_service_class_id', 'service_class_name', $default, false);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="html_select_service_class">
            <?php echo $html ?>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_currency($list_currency = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_currency/html_select_currency.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_currency/html_select_currency.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_currency)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmcurrency.php';
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
    public static function select_group_product($name, $default = '0', $list_group_product = array(), $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/html_select_group_product/html_select_group_product.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/html_select_group_product/html_select_group_product.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_group_product)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmart.php';
            $list_group_product = tsmart::get_list_group_product();
        }
        $id_element = 'html_select_group_product_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_group_product({
                    list_group_product:<?php echo json_encode($list_group_product) ?>,
                    select_name: "<?php echo $name ?>",
                    group_product:<?php echo $default ? $default : 0 ?>
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
                <option value=""><?php echo JText::_('please select group product') ?></option>
                <?php foreach ($list_group_product as $key => $group_product) { ?>
                    <option <?php echo $key == $default ? ' selected ' : '' ?>
                        value="<?php echo $key ?>"><?php echo $group_product ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_trip_type($name, $default = '0', $list_trip_type = array(), $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/html_select_trip_type/html_select_trip_type.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/html_select_trip_type/html_select_trip_type.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_trip_type)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmart.php';
            $list_trip_type = tsmart::get_list_trip_type();
        }
        $id_element = 'html_select_group_product_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_trip_type({
                    list_trip_type:<?php echo json_encode($list_trip_type) ?>,
                    select_name: "<?php echo $name ?>",
                    trip_type: "<?php echo $default  ?>"
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
                <option value=""><?php echo JText::_('please select group product') ?></option>
                <?php foreach ($list_trip_type as $key => $trip_type) { ?>
                    <option <?php echo $key == $default ? ' selected ' : '' ?>
                        value="<?php echo $key ?>"><?php echo $trip_type ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_model_price($name, $default = '0', $list_model_price = array(), $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/html_select_trip_type/html_select_trip_type.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/html_select_trip_type/html_select_trip_type.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_model_price)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmprice.php';
            $list_model_price = vmprice::get_list_price_type();
        }
        $id_element = 'html_select_group_product_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_trip_type({
                    list_trip_type:<?php echo json_encode($list_model_price) ?>,
                    select_name: "<?php echo $name ?>",
                    trip_type: "<?php echo $default  ?>"
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
                <option value=""><?php echo JText::_('please select model price') ?></option>
                <?php foreach ($list_model_price as $key => $price_type) { ?>
                    <option <?php echo $price_type->value == $default ? ' selected ' : '' ?>
                        value="<?php echo $price_type->value ?>"><?php echo $price_type->text ?></option>
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_language/html_select_language.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_language/html_select_language.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_language)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmlanguage.php';
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
    public static function select_user_name($list_user = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_user/html_select_user.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_user/html_select_user.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_user)) {
            require_once JPATH_ROOT . 'components/com_tsmart/helpers/tsmuser.php';
            $list_user = tsmuser::get_list_user();
        }
        $id_element = 'html_select_user_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_select_user({
                    list_user:<?php echo json_encode($list_user) ?>,
                    select_name: "<?php echo $name ?>",
                    user_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" <?php echo $attrib ?> >
            <select disable_chosen="true" id="<?php echo $name ?>" name="<?php echo $name ?>" class="user">
                <option value=""><?php echo JText::_('please select user') ?></option>
                <?php foreach ($list_user as $user) { ?>
                    <option <?php echo $user->id == $default ? ' selected ' : '' ?>
                        value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_type_percent_or_amount/html_select_type_percent_or_amount.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_type_percent_or_amount/html_select_type_percent_or_amount.less');
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
    public static function input_percent_or_amount($name_of_value_percent_or_amount, $name_of_type_percent_or_amount, $value_of_percent_or_amount, $value_of_type, $min_amount = 0, $max_amount = 1000, $min_percent = 0, $max_percent = 100, $readonly = false)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/input_percent_or_amount/input_percent_or_amount.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/input_percent_or_amount/input_percent_or_amount.less');
        $input = JFactory::getApplication()->input;
        if (!in_array($value_of_type, array('percent', 'amount'))) {
            $value_of_type = "percent";
        }
        $id_element = 'input_percent_or_amount_' . $name_of_value_percent_or_amount . '_' . $name_of_type_percent_or_amount;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').input_percent_or_amount({
                    name_of_value_percent_or_amount: "<?php echo $name_of_value_percent_or_amount ?>",
                    name_of_type_percent_or_amount: "<?php echo $name_of_type_percent_or_amount ?>",
                    value_of_percent_or_amount:<?php echo (float)$value_of_percent_or_amount ?>,
                    value_of_type: "<?php echo $value_of_type ?>",
                    min_amount:<?php echo (float)$min_amount ?>,
                    max_amount:<?php echo (float)$max_amount ?>,
                    min_percent:<?php echo (float)$min_percent ?>,
                    max_percent:<?php echo (float)$max_percent ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>" class="input_percent_or_amount">
            <div class="pull-left">
                <input type="text" value="<?php echo $value_of_percent_or_amount ?>" <?php echo $readonly ? 'readonly' : '' ?>
                       class="inputbox input_number_or_percent   input_number_or_percent_<?php echo $name_of_value_percent_or_amount ?>" id="input_number_<?php echo $name_of_value_percent_or_amount ?>" data-v-min="<?php echo $value_of_type == 'percent' ? $min_percent : $min_amount ?>"
                       data-v-max="<?php echo $value_of_type == 'percent' ? $max_percent : $max_amount ?>" data-a-sign="<?php echo $value_of_type == 'percent' ? '%' : '' ?>">
                <input type="hidden" value="<?php echo $value_of_percent_or_amount ?>" name="<?php echo $name_of_value_percent_or_amount ?>" id="<?php echo $name_of_value_percent_or_amount ?>">
            </div>
            <div class="pull-left">
                <select disable_chosen="true" class="type_percent_or_amount" id="<?php echo $name_of_type_percent_or_amount ?>" name="<?php echo $name_of_type_percent_or_amount ?>">
                    <option value="percent" <?php echo $value_of_type == 'percent' ? 'selected' : '' ?> ><?php echo JText::_('percent') ?></option>
                    <option value="amount" <?php echo $value_of_type == 'amount' ? 'selected' : '' ?>><?php echo JText::_('amount') ?></option>
                </select>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function select_range_of_date($list_rang_of_date = array(), $name, $default = '0', $attrib = "onchange='submit();'", $key = 'value', $text = 'text', $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('components/com_tsmart/assets/js/controller/select_list_range_of_date/html_select_list_range_of_date.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/select_list_range_of_date/html_select_list_range_of_date.less');
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
    public static function generate_code($name, $default = '0', $controller = 'tsmart', $task = 'get_code', $read_only = false)
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_tsmart/assets/js/controller/generate_code/html_generate_code.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/generate_code/html_generate_code.less');
        $input = JFactory::getApplication()->input;
        $id_element = 'html_generate_code_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').html_generate_code({
                    controller: "<?php echo $controller ?>",
                    task: "<?php echo $task ?>",
                    read_only:<?php echo json_encode($read_only)  ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_generate_code" id="<?php echo $id_element ?>">
            <div class="row">
                <div class="pull-left">
                    <input name="<?php echo $name ?>" value="<?php echo $default ?>" <?php echo $read_only ? ' readonly ' : '' ?> type="text" class="code">
                </div>
                <div class="pull-left">
                    <button type="button" class="btn generate_code"><?php echo JText::_('Go') ?></button>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function input_passenger($list_passenger = array(), $name = '', $default = '0', $min_age = 0, $max_age = 99, $departure, $passenger_config, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript('/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet('/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript('/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript('/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript('/media/system/js/Create-A-Tooltip/js/jquery.tooltip.js');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/input_passenger/html_input_passenger.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/input_passenger/html_input_passenger.less');
        $doc->addLessStyleSheet('/media/system/js/Create-A-Tooltip/css/tooltip.less');
        $doc->addStyleSheet('/media/system/js/Create-A-Tooltip/css/tooltip.css');
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
                    min_age: <?php echo (int)$min_age ?>,
                    max_age: <?php echo (int)$max_age ?>,
                    debug: <?php echo json_encode($debug) ?>,
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="row person-type">
                        <div class="col-lg-12">
                            <h4 class="">
                                <span title=""
                                      class="travel-icon">n</span> <?php echo JText::_('SENIOR/ADULT/TEEN(12-99 years)') ?>
                                <?php if ($debug) { ?>
                                    <button type="button" class="btn btn-primary auto-fill-date">auto fill data
                                    </button><?php } ?>
                            </h4>
                        </div>
                    </div>
                    <div class="row herder">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-1"><?php echo JText::_('Gender') ?></div>
                        <div class="col-lg-2"><?php echo JText::_('First name') ?></div>
                        <div class="col-lg-2"><?php echo JText::_('Middle name') ?></div>
                        <div class="col-lg-2"><?php echo JText::_('Last name') ?></div>
                        <div class="col-lg-2"><?php echo JText::_('Nationality') ?></div>
                        <div class="col-lg-2"><?php echo JText::_('Date of birth') ?></div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-1"></div>
                    </div>
                    <div class="input-passenger-list-passenger senior-adult-teen">
                        <div class="row item-passenger">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="header-row text-uppercase">
                                            <?php echo JText::_('Person ') ?><span class="passenger-index">1</span>
                                            <button type="button" class=" btn btn-link remove">
                                                <span class="icon-remove " title=""></span></button>
                                            <button type="button" class=" btn btn-link add ">
                                                <span class="icon-plus " title=""></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <input data-name="first_name" maxlength="100"
                                               placeholder="<?php echo JText::_('First name') ?>"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <input data-name="middle_name" maxlength="100"
                                               placeholder="<?php echo JText::_('Middle name') ?>"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <input data-name="last_name" maxlength="100"
                                               placeholder="<?php echo JText::_('Last name') ?>"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <input class="date readonly" data-name="date_of_birth" readonly
                                               placeholder="<?php echo JText::_('Date of birth') ?>"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <select data-name="nationality"
                                                placeholder="<?php echo JText::_('Nationality') ?>"
                                        >
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <select class="gender" data-name="gender">
                                            <option value="mr">Mr</option>
                                            <option value="ms">Ms</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row person-type">
                        <div class="col-lg-10">
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
    public static function build_room($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $disable = false, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_room/html_build_room.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_room/html_build_room.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_room';
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
                    <div class="row">
                        <div class="col-lg-12"><h3><?php echo JText::_('Room ') ?><span class="room-order">1</span></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <h3 style="text-align: center;color: #820004"><?php echo JText::_('Select room type') ?></h3>
                            <div class="list-room">
                                <div class="row">
                                    <div class="col-lg-2 col-xxxs-2" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Single') ?></br>
                                            <input type="radio" checked
                                                   data-name="room_type"
                                                   name="room_type" data-note="<?php echo JText::_('ROOM_TYPE_SINGLE_NOTE') ?>"
                                                   value="single"></label>
                                    </div>
                                    <div class="col-lg-3 col-xxxs-3" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Double') ?></br>
                                            <input type="radio" data-name="room_type" data-note="<?php echo JText::_('ROOM_TYPE_DOUBLE_NOTE') ?>"
                                                   name="room_type"
                                                   value="double"></label>
                                    </div>
                                    <div class="col-lg-3 col-xxxs-3"></div>
                                    <div class="col-lg-2 col-xxxs-2" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Twin') ?></br>
                                            <input type="radio" data-name="room_type" data-note="<?php echo JText::_('ROOM_TYPE_TWIN_NOTE') ?>"
                                                   name="room_type"
                                                   value="twin"></label>
                                    </div>
                                    <div class="col-lg-2 col-xxxs-2" style="text-align: center;color: #820004">
                                        <label><?php echo JText::_('Triple') ?></br>
                                            <input type="radio" data-name="room_type" data-note="<?php echo JText::_('ROOM_TYPE_TRIPLE_NOTE') ?>"
                                                   name="room_type"
                                                   value="triple"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row note ">
                                <div class="col-log-12">
                                    <div class="mobile-note"><?php echo JText::_('ROOM_TYPE_SINGLE_NOTE') ?></div>
                                </div>
                            </div>
                            <div class="row note">
                                <div class="col-lg-12">
                                    <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                            <button type="button" class="btn btn-primary random-text">Random text
                                            </button><?php } ?></h4>
                                    <textarea data-name="room_note" style="width: 100%;height: 50px"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <h3 class="" style="text-align: center;color: #820004"><?php echo JText::_('select person for room on your own') ?></h3>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span style="<?php echo !$debug ? 'display: none;' : '' ?>" class="in_room"></span></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
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
            <div class="rooming-list">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 style="text-align: center"><?php echo JText::_('Rooming list') ?></h4>
                        <div class="table table-hover table-bordered table-rooming-list">
                            <div class="thead">
                                <div class="row">
                                    <div class="col-lg-2 col-xxxs-2">
                                        <div class="column-header-item"><?php echo JText::_('Room') ?></div>
                                    </div>
                                    <div class="col-lg-2 col-xxxs-2">
                                        <div class="column-header-item"><?php echo JText::_('Room type') ?></div>
                                    </div>
                                    <div class="col-lg-3 col-xxxs-5">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="col-lg-3 col-xxxs-3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="col-lg-2 hidden-xxxs">
                                        <div class="column-header-item"><?php echo JText::_('Room note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row div-item-room">
                                    <div class="col-lg-2 col-xxxs-2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="col-lg-2 col-xxxs-2">
                                        <div class="row-item-column">
                                            <div class="room_type"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xxxs-5">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xxxs-3">
                                        <div class="row-item-column">
                                            <div class="private-room"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 hidden-xxxs">
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
    public static function build_extra_night_hotel($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $type, $extra_night_config, $debug = false)
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
        $doc->addScript('components/com_tsmart/assets/js/controller/build_extra_night_hotel/html_build_extra_night_hotel.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_extra_night_hotel/html_build_extra_night_hotel.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $session = JFactory::getSession();
        $list_passenger = $session->get('list_passenger');
        $list_passenger = json_decode($list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
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
        $json_list_passenger = $session->get('json_list_passenger', '');
        $json_list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($json_list_passenger->senior_adult_teen, $json_list_passenger->children_infant);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_extra_night_hotel({
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
        <div class="html_night_hotel " id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_room">
                <div class="item-night-hotel">
                    <div style="display: none" class="move-room handle"><span title="" class="icon-move "></span></div>
                    <div class="row">
                        <div class="col-lg-offset-6 col-lg-6">
                            <div class="room-price text-center color-red">
                                <b><span data-a-sign="US$" class="price single-price"><?php echo $group_min_price->single_min_price ?></span>/<?php echo JText::_('SGL') ?>
                                    or
                                    <span data-a-sign="US$" class="price dbl-price"><?php echo $group_min_price->dbl_twin_min_price ?></span>/<?php echo JText::_('DBL/TWIN') ?>
                                    or
                                    <span data-a-sign="US$" class="price tpl-price"><?php echo $group_min_price->tpl_min_price ?></span>/<?php echo JText::_('TPL') ?>
                                </b></div>
                            <div class="text-uppercase text-center color-red"><b><?php echo JText::_('Book now') ?></b>
                            </div>
                            <div class="text-center"><span class="glyphicon glyphicon-chevron-down"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="reservation text-uppercase"><?php echo JText::_('Reservation') ?>
                                <span class="room-order">1</span>
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <h5><?php echo JText::_('Select date from to') ?></h5>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input style="width: 100%" type="text" class="date check-in-date">
                                </div>
                                <div class="col-lg-6">
                                    <input style="width: 100%" type="text" class="date check-out-date">
                                </div>
                            </div>
                            <div class="row note">
                                <div class="col-lg-12">
                                    <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                            <button type="button" class="btn btn-primary random-text">Random text
                                            </button><?php } ?></h4>
                                    <textarea data-name="room_note" style="width: 100%;height: 50px"></textarea>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-12">
                                    <div class="notify">
                                        <div class="notify1">
                                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo JText::sprintf('NOTE_NIGHT_HOTEL_1', 0, $passenger_config->hotel_arrange_year_old_from) ?>
                                        </div>
                                        <div class="notify2">
                                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo JText::sprintf('NOTE_NIGHT_HOTEL_2', 0, $passenger_config->hotel_arrange_year_old_from) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="list_room_type row">
                                <div class="col-lg-3">
                                </div>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="title-select-room"><?php echo JText::_('Select room') ?></div>
                                            <div class="text-center">
                                                <span class="glyphicon glyphicon-arrow-down"></span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                                        $room_limit = 16;
                                        ?>
                                        <?php foreach ($list_room_type as $room_type => $allow_passenger) { ?>
                                            <div class="col-lg-3">
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
                                    <div class="room-item <?php echo $room_type ?>">
                                        <div class="passenger-item row <?php echo $room_type ?>"
                                             data-room_type="<?php echo $room_type ?>">
                                            <div class="col-lg-2">
                                                <div class="title_room text-uppercase"><?php echo JText::_($room_type) ?></div>
                                            </div>
                                            <div class="col-lg-1">
                                                <button type="button" class="btn btn-primary btn-xs remove_room">X
                                                </button>
                                            </div>
                                            <div class="col-lg-9">
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="group_button pull-right">
                                <button type="button"
                                        class="btn btn-primary btn-xs remove-extra-night pull-left"><?php echo JText::_('Remove') ?></button>
                                <button type="button"
                                        class="btn btn-primary btn-xs add-extra-night pull-left"><?php echo JText::_('Book more') ?></button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" data-name="tsmart_hotel_addon_id" class="tsmart_hotel_addon_id">
                </div>
            </div>
            <div class="rooming-list">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 style="text-align: center"><?php echo JText::_('Rooming list') ?></h4>
                        <div class="table table-hover table-bordered table-rooming-list">
                            <div class="thead">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="column-header-item"><?php echo JText::_('Room') ?></div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="column-header-item"><?php echo JText::_('Room type') ?></div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="column-header-item"><?php echo JText::_('Room note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row div-item-room">
                                    <div class="col-lg-2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="row-item-column">
                                            <div class="room_type"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="row-item-column">
                                            <div class="private-room"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
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
    public static function build_passenger_summary($list_passenger = array(), $name = '', $default = '0', $departure, $passenger_config, $product)
    {
        return;
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_passenger_summary/html_build_passenger_summary.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_passenger_summary/html_build_passenger_summary.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
                    tour: '<?php echo json_encode($product) ?>'
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_aobject row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <h4
                    style="text-align: center"><?php echo JText::_('Service total') ?></h4>
                <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                    <?php
                    $passenger = $list_passenger[$i];
                    $room_type = $passenger->room_type;
                    ?>
                    <div class="item-aobject">
                        <div class="row">
                            <div class="col-lg-12"><h3 class="passenger-title"><?php echo JText::_('passenger ') ?><span
                                        class="aobject-order"><?php echo JText::sprintf("%s :$passenger->first_name $passenger->middle_name $passenger->last_name", $i + 1) ?></span>
                                </h3></div>
                        </div>
                        <div class="passenger-service-list">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table table-hover table-service-list">
                                        <div class="thead">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div
                                                        class="column-header-item"><?php echo JText::_('Service detail') ?></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="column-header-item"></div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div
                                                        class="column-header-item"><?php echo JText::_('Service price') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tbody">
                                            <div class="row div-item-aobject">
                                                <div class="col-lg-4">
                                                    <div
                                                        class="row-item-column"><?php echo JText::_('Package tour') ?></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row-item-column">
                                                        <div
                                                            class="tour-detail"><?php echo JText::sprintf("%s Trip from %s to %s. %s include", $product->product_name, JHtml::_('date', $booking_date, tsmConfig::$date_format), $end_date->format(tsmConfig::$date_format), $room_type) ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="row-item-column">
                                                        <div
                                                            class="tour-price"><?php echo $passenger->tour_service_price ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row div-item-aobject">
                                                <div class="col-lg-4">
                                                    <div
                                                        class="row-item-column"><?php echo JText::_('Extra service') ?></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row-item-column">
                                                        <div class="extra-service"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="row-item-column">
                                                        <div class="extra-price"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row div-item-aobject">
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_passenger_summary_confirm/html_build_passenger_summary_confirm.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_passenger_summary_confirm/html_build_passenger_summary_confirm.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
        <div class="html_build_passenger_summary_confirm row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="header">
                    <div class="row">
                        <div class="col-lg-1"><?php echo JText::_('Gender') ?></div>
                        <div class="col-lg-3"><?php echo JText::_('Name & surname') ?></div>
                        <div class="col-lg-1"><?php echo JText::_('Date of birth') ?></div>
                        <div class="col-lg-1"><?php echo JText::_('Nationality') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row row-item">
                            <div class="col-lg-1"><?php echo $passenger->title ?></div>
                            <div class="col-lg-3"><?php echo $passenger->first_name ?></div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-1"></div>
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
        $order->order_data = json_decode($order->order_data);
        $list_passenger = $order->order_data->list_passenger;
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
        <div class="html_edit_passenger_in_order row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                    </div>
                </div>
                <div class="header">
                    <div class="row">
                        <div class="col-lg-1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('Name') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Title') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row row-item" data-passenger_id="<?php echo $i ?>">
                            <div class="col-lg-1 column id"><?php echo $i + 1 ?></div>
                            <div class="col-lg-2 column"><?php echo $passenger->first_name ?></div>
                            <div class="col-lg-2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="col-lg-1 column"><?php echo $passenger->title ?></div>
                            <div class="col-lg-2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="col-lg-1 column"><?php echo $passenger->nationality ?></div>
                            <div class="col-lg-1 column"></div>
                            <div class="col-lg-1 column"></div>
                            <div class="col-lg-1 column">
                                <div class="buttons pull-right">
                                    <button type="button" class="btn-link edit"><span class="icon-edit"></span></button>
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
                    <div class="row">
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
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
                    <div class="row">
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
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
                                <label class="control-label" for="emergency_contact_phone_no"><?php echo JText::_('Phone no') ?></label>
                                <div class="controls">
                                    <input type="text" name="emergency_contact_phone_no" id="emergency_contact_phone_no">
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4><?php echo JText::_('addtion information') ?></h4>
                    <div class="row">
                        <div class="col-lg-12">
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/passenger_information_in_order/html_passenger_information_in_order.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/passenger_information_in_order/html_passenger_information_in_order.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
        <div class="html_passenger_information_in_order row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-right">
                            <?php echo JText::_('Tour fee') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-left">
                            <?php echo JText::_('Base price') ?>
                        </div>
                        <div class="pull-right">
                            <span>1232</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-left">
                            <?php echo JText::_('Tax & fees') ?>
                        </div>
                        <div class="pull-right">
                            <span>1232</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-left">
                            <?php echo JText::_('Discout') ?>
                        </div>
                        <div class="pull-right">
                            <span>1232</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-right">
                            <?php echo JText::_('<span class="total">Total price</span><span class="price">1500</span>') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
        <div class="html_edit_passenger_in_order row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                        <div class="buttons pull-right">
                            <button class="btn btn-primary"><?php echo JText::_('Save change') ?></button>
                            <button class="btn btn-primary"><?php echo JText::_('Cancel change') ?></button>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <div class="row">
                        <div class="col-lg-1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('Name') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Title') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row row-item">
                            <div class="col-lg-1 column id"><?php echo $i + 1 ?></div>
                            <div class="col-lg-2 column"><?php echo $passenger->first_name ?></div>
                            <div class="col-lg-2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="col-lg-1 column"><?php echo $passenger->title ?></div>
                            <div class="col-lg-2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="col-lg-1 column"><?php echo $passenger->nationality ?></div>
                            <div class="col-lg-1 column"></div>
                            <div class="col-lg-1 column"></div>
                            <div class="col-lg-1 column">
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
        <div class="passenger_transfer row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <?php
                ?>
                <?php foreach ($list_list_transfer as $list_transfer) { ?>
                    <div class="row">
                        <?php for ($i = 0; $i < count($list_transfer); $i++) { ?>
                            <div class="col-lg-6">
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/edit_passenger_in_order/html_edit_passenger_in_order.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
        <div class="html_edit_passenger_in_order row" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_aobject">
                <div class="toolbar">
                    <div class="row">
                        <div class="pull-left title"><?php echo JText::_('Passenger list') ?></div>
                        <div class="buttons pull-right">
                            <button class="btn btn-primary"><?php echo JText::_('Save change') ?></button>
                            <button class="btn btn-primary"><?php echo JText::_('Cancel change') ?></button>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <div class="row">
                        <div class="col-lg-1 column id"><?php echo JText::_('Id') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('Name') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('Surname') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Title') ?></div>
                        <div class="col-lg-2 column"><?php echo JText::_('DOB') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Nationality') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Passport no') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Expiry date') ?></div>
                        <div class="col-lg-1 column"><?php echo JText::_('Action') ?></div>
                    </div>
                </div>
                <div class="body">
                    <?php for ($i = 0; $i < count($list_passenger); $i++) { ?>
                        <?php
                        $passenger = $list_passenger[$i];
                        $room_type = $passenger->room_type;
                        ?>
                        <div class="row row-item">
                            <div class="col-lg-1 column id"><?php echo $i + 1 ?></div>
                            <div class="col-lg-2 column"><?php echo $passenger->first_name ?></div>
                            <div class="col-lg-2 column"><?php echo tsmConfig::get_full_name($passenger) ?></div>
                            <div class="col-lg-1 column"><?php echo $passenger->title ?></div>
                            <div class="col-lg-2 column"><?php echo $passenger->date_of_birth ?></div>
                            <div class="col-lg-1 column"><?php echo $passenger->nationality ?></div>
                            <div class="col-lg-1 column"></div>
                            <div class="col-lg-1 column"></div>
                            <div class="col-lg-1 column">
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
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_payment_cardit_card/html_build_payment_cardit_card.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_payment_cardit_card/html_build_payment_cardit_card.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
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
        <div class="html_build_payment_cardit_card row" id="<?php echo $id_element ?>">
            <fieldset>
                <legend><?php echo JText::_('Payment detail') ?></legend>
                <div class="payment-detail">
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo JText::_('Payment amout: CW') ?>
                        </div>
                        <div class="col-lg-4">
                            <label><input type="radio"
                                          name="payment_type" value="full_payment" > <?php echo JText::sprintf('<span class="cost">%s</span> (full payment)', $full_payment) ?>
                            </label>
                        </div>
                        <div class="col-lg-4">
                            <label><input type="radio"
                                          name="payment_type" value="deposit_payment"> <?php echo JText::sprintf('<span class="cost">%s</span> (Deposit)', $deposit) ?>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo JText::_('Card type') ?>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Visa card') ?></label>
                        </div>
                        <div class="col-lg-2">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Master card') ?></label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="radio" name="card_type"> <?php echo JText::_('Amex card') ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo JText::_('Card number') ?>:
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="card_number" name="card_number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo JText::_('Expri date') ?>:
                        </div>
                        <div class="col-lg-3">
                            <select name="month" class="month">
                                <option value=""><?php echo JText::_('select month') ?></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select name="year" class="year">
                                <option value=""><?php echo JText::_('select year') ?></option>
                                <?php for ($i = 2016; $i <= 2020; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="icon-question"><span></span><input type="text" class="last_three_number"
                                                                             name="last_three_number"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo JText::_('Card holder name') ?>:
                        </div>
                        <div class="col-lg-8">
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
    public static function build_form_contact($name, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($list_passenger->senior_adult_teen, $list_passenger->children_infant);
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/html_build_form_contact/html_build_form_contact.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/html_build_form_contact/html_build_form_contact.less');
        $doc->addScript('/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
        $doc->addStyleSheet('/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $lipsum = new joshtronic\LoremIpsum();
        $id_element = 'html_build_form_contact_' . $name;
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
        <div class="html_build_form_contact row" id="<?php echo $id_element ?>">
            <div class="form-contact form-horizontal">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title contact-detail"><span class="icon-envelope-alt"
                                                               title=""></span><?php echo JText::_('Contact detail') ?> <?php if ($debug) { ?>
                                <button type="button" class="btn btn-primary auto-fill-date">auto fill data
                                </button><?php } ?>
                        </h4>
                    </div>
                </div>
                <?php
                $option = array(
                    class_label => 'col-lg-4',
                    class_control_group => 'col-lg-8',
                );
                ?>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="left">
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Contact name'), $option, 'contact_name', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Phone No'), $option, 'phone_number', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Email Address'), $option, 'email_address', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Confirm Email'), $option, 'confirm_email', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Street address'), $option, 'street_address', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Suburb/Town'), $option, 'suburb_town', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('State/province'), $option, 'state_province', '', 'class="required"'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right">
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Postcode/Zip'), $option, 'post_code_zip', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Res. Country'), $option, 'res_country', '', 'class="required"'); ?>
                            <h3 class=" emergency-contact"><?php echo JText::_('Emergency contact') ?></h3>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Contact Name'), $option, 'emergency_contact_name', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Street address'), $option, 'emergency_email_address', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control_horizontal('input', JText::_('Phone No'), $option, 'emergency_phone_number', '', 'class="required"'); ?>
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
    public static function build_pickup_transfer($name, $list_passenger = array(), $default = '0', $departure, $transfer_config, $transfer_item_config, $pickup_transfer_type = 'pre_transfer', $min_price = 0, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript('/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet('/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_pickup_transfer/html_build_pickup_transfer.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/plugin/moment-develop/moment.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_pickup_transfer/html_build_pickup_transfer.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_pickup_transfer_' . $name;
        $config = tsmConfig::get_config();
        $params = $config->params;
        if ($pickup_transfer_type == 'pre_transfer') {
            $transfer_booking_days_allow = $params->get('pre_transfer_booking_days_allow', 1);
        } else {
            $transfer_booking_days_allow = $params->get('post_transfer_booking_days_allow', 1);
        }
        $session=JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger', '');
        $json_list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($json_list_passenger->senior_adult_teen, $json_list_passenger->children_infant);

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_pickup_transfer({
                    list_passenger: <?php echo json_encode($list_passenger) ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    transfer_item_config:<?php echo json_encode($transfer_item_config) ?>,
                    transfer_config:<?php echo json_encode($transfer_config) ?>,
                    pickup_transfer_type: "<?php echo $pickup_transfer_type ?>",
                    transfer_booking_days_allow: <?php echo (int)$transfer_booking_days_allow ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        ob_start();
        ?>
        <div class="html_build_pickup_transfer" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_transfer">
                <div class="item-transfer">
                    <div class="row">
                        <div class="col-lg-12"><h4><?php echo JText::_('transfer ') ?>
                                <span class="transfer-order">1</span>
                            </h4></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label><?php echo JText::_('Check in date') ?>:
                                <div class="input-group">
                                    <input type="text" readonly class="form-control date check-in-date " placeholder="Checkin date">
                                          <span class="input-group-btn">
                                            <button class="btn btn-primary remove-checkin-date" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                                          </span>
                                </div>

                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div
                                class="air-price"><?php echo JText::sprintf('price<br/> from <span class="price" data-a-sign="US$ ">%d</span> /pers', $min_price) ?></div>
                        </div>
                        <div class="col-lg-8">
                            <h5><?php echo JText::_('Air port pickup & transfer') ?></h5>
                            <ul class="list-passenger">
                                <li><label class="checkbox-inline"> <input class="passenger-item" type="checkbox"> <span
                                            class="full-name"></span><span class="in_transfer <?php echo !$debug ? ' hide ' : '' ?>"></span></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="transfer-note">
                                <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                        <button type="button" class="btn btn-primary random-text">Random text
                                        </button><?php } ?></h4>
                                <textarea data-name="transfer_note" class="transfer_note" style=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="group_button pull-right">
                                <button type="button"
                                        class="btn btn-primary btn-xs remove-transfer pull-left"><?php echo JText::_('Remove transfer') ?></button>
                                <button type="button"
                                        class="btn btn-primary btn-xs add-more-transfer pull-left"><?php echo JText::_('Add more transfer') ?></button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" data-name="tsmart_transfer_addon_id" class="tsmart_transfer_addon_id">
                </div>
            </div>
            <div class="transfering-list">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 style="text-align: center"><?php echo JText::_('transfering list') ?></h4>
                        <div class="table table-hover table-bordered table-transfering-list">
                            <div class="thead">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="column-header-item"><?php echo JText::_('transfer') ?></div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="column-header-item"><?php echo JText::_('transfer type') ?></div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="column-header-item"><?php echo JText::_('Passenger') ?></div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="column-header-item"><?php echo JText::_('Bed note') ?></div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="column-header-item"><?php echo JText::_('transfer note') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <div class="row div-item-transfer">
                                    <div class="col-lg-2">
                                        <div class="row-item-column"><span class="order">1</span></div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="row-item-column">
                                            <div class="transfer_type"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="row-item-column">
                                            <div class="table_list_passenger"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="row-item-column">
                                            <div class="private-transfer"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
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
    /**
     * @param $name
     * @param array $list_passenger
     * @param string $default
     * @param $departure
     * @param $passenger_config
     * @param $list_excursion_addon
     * @return string
     * @throws Exception
     */
    public static function build_excursion_addon($name, $default = '0', $departure, $passenger_config, $list_excursion_addon, $debug = false)
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $session = JFactory::getSession();
        $json_list_passenger = $session->get('json_list_passenger');
        $json_list_passenger = json_decode($json_list_passenger);
        $json_list_passenger = array_merge($json_list_passenger->senior_adult_teen, $json_list_passenger->children_infant);
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addScript('/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addScript('/media/system/js/jquery.serializeObject.js');
        $doc->addScript('/media/system/js/jquery.base64.js');
        $doc->addStyleSheet('/media/system/js/select2-master/dist/css/select2.css');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
        $doc->addScript('components/com_tsmart/assets/js/controller/build_excursion_addon/html_build_excursion_addon.js');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        $doc->addScript('/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
        $doc->addScript('/media/system/js/jquery.scrollTo-master/jquery.scrollTo.js');
        $doc->addLessStyleSheet('components/com_tsmart/assets/js/controller/build_excursion_addon/html_build_excursion_addon.less');
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $doc->addScript('/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $lipsum = new joshtronic\LoremIpsum();
        $input = JFactory::getApplication()->input;
        $id_element = 'html_build_excursion_addonn_' . $name;
        $json_list_passenger = TSMUtility::add_year_old($json_list_passenger);
        $list_excursion_addon = JArrayHelper::pivot($list_excursion_addon, 'tsmart_excursion_addon_id');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var cookie_list_passenger = $.cookie('cookie_list_passenger');
                cookie_list_passenger = $.parseJSON(cookie_list_passenger);
                $('#<?php  echo $id_element ?>').html_build_excursion_addon({
                    list_passenger: <?php echo json_encode($json_list_passenger) ?>,
                    input_name: "<?php echo $name ?>",
                    element_key: "<?php echo $id_element ?>",
                    debug:<?php echo json_encode($debug) ?>,
                    departure:<?php echo json_encode($departure) ?>,
                    list_data_excursion_addon:<?php echo json_encode($list_excursion_addon) ?>,
                    passenger_config:<?php echo json_encode($passenger_config) ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $excusion_helper = tsmhelper::getHepler('excursionaddon');
        ob_start();
        ?>
        <div class="html_build_excursion_addon" id="<?php echo $id_element ?>">
            <div class="<?php echo $id_element ?>_list_excursion">
                <?php foreach ($list_excursion_addon AS $excursion_addon) { ?>
                    <?php
                    $excursion_addon->itinerary = strip_tags($excursion_addon->itinerary);
                    $min_price = $excusion_helper->get_min_price($excursion_addon);
                    ?>
                    <div class="item-excursion">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="color-red"><b><?php echo $excursion_addon->excursion_addon_name ?></b></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <img class="img-responsive" src="/<?php echo $excursion_addon->photo ?>">
                                <div class="description  text-justify"><?php echo JString::substr($excursion_addon->itinerary, 0, 100) ?></div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="air-price"><?php echo JText::sprintf('price<br/> from <span class="price">%d</span> /pers', $min_price) ?></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul class="list-passenger">
                                            <?php foreach ($json_list_passenger AS $passenger) { ?>
                                                <li><label class="checkbox-inline">
                                                        <input class="passenger-item" type="checkbox">
                                                        <span class="full-name"><?php echo TSMUtility::get_full_name($passenger, $debug); ?></span><span class="in_excursion"></span></label>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="excursion-fluid note">
                                    <div class="col-lg-12">
                                        <h4><?php echo JText::_('Your note') ?><?php if ($debug) { ?>
                                                <button type="button" class="btn btn-primary random-text">Random text
                                                </button><?php } ?></h4>
                                        <textarea data-name="excursion_note" style="width: 96%;height: 50px"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row hide">
                            <div class="col-lg-12">
                                <div class="group_button pull-right">
                                    <button type="button"
                                            class="btn btn-primary add-more-excursion pull-leff"><?php echo JText::_('Add more excursion') ?></button>
                                    <button type="button"
                                            class="btn btn-primary remove-excursion pull-left"><?php echo JText::_('Remove excursion') ?></button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="tsmart_excursion_addon_id" value="<?php echo $excursion_addon->tsmart_excursion_addon_id ?>">
                    </div>
                <?php } ?>
            </div>
            <input type="hidden" name="<?php echo $name ?>">
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    public static function show_tip($name = "", $value, $option_tip = array(
        speed => 400,
        background => "#55b555"
    ))
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.ui');
        JHtml::_('jquery.ui', array('sortable'));
        $doc->addScript('/media/system/js/jquery.utility.js');
        $doc->addStyleSheet('/media/system/js/tipso-master/src/tipso.css');
        $doc->addStyleSheet('/media/system/js/animate.css-master/animate.css');
        $doc->addScript('/media/system/js/tipso-master/src/tipso.js');
        ob_start();
        ?>
        <a href="javascript:void(0)" class="show-content <?php echo $name ?>"><span class="icon-eye"></span></a>
        <?php
        $html = ob_get_clean();
        $option_tip = (array)$option_tip;
        $option_tip1 = array(
            speed => 400,
            background => "#55b555",
            content => $value
        );
        $option_tip = array_merge($option_tip, $option_tip1);
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
}