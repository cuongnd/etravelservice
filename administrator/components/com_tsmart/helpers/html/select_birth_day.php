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
abstract class TSMHtmlSelect_birth_day
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
    public static function birth_day_select($name, $value_selected = '', $format = 'mm/dd/yy', $view_format = 'mm/dd/yy', $min_date = '', $max_date = '', $class = '', $attrib = '')
    {
        JHtml::_('jquery.ui');
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
        $doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
        $doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/dist/jquery-dateFormat.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.maskedinput-master/dist/jquery.maskedinput.js');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/birth_day_select/html_birth_day_select.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/birth_day_select/html_birth_day_select.less');
        $input = JFactory::getApplication()->input;
        $select_date = 'select_date_' . $name;
        $value_selected=JFactory::getDate($value_selected)->format('m/d/y');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $select_date ?>').html_birth_day_select({
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
        <div id="<?php echo $select_date ?>" class="html_birth_day_select">
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


}
