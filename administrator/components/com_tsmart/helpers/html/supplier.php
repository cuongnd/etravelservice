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
abstract class TSMHtmlSupplier
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
    public static function select_supplier($list_supplier = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/supplier/supplier_html_select_supplier.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/supplier/supplier_html_select_supplier.less');
        $input = JFactory::getApplication()->input;
        $supplier_helper = TSMHelper::getHepler('supplier');
        if (empty($list_supplier)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmuser.php';
            $list_supplier = $supplier_helper->get_list_supplier();
            foreach($list_supplier as &$supplier){
                $supplier=(object)array_intersect_key((array)$supplier, array(tsmart_supplier_id=>0,supplier_name=>""));
            }

        }
        $id_element = 'supplier_html_select_supplier_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').supplier_html_select_supplier({
                    list_supplier:<?php echo json_encode($list_supplier) ?>,
                    name: "<?php echo $name ?>",
                    supplier_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $function_check_supplier_selected=function($supplier_id, $default){
            if(is_numeric($default)){
                return  $supplier_id == $default;
            }else if(is_array($default)){
                return in_array($supplier_id,$default);
            }else{
                return false;
            }
        };
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select  disable_chosen="true"  <?php echo $attrib ?>   <?php echo is_array($default)?' multiple ':'' ?>   id="<?php echo $name ?>" name="<?php echo $name ?>" class="supplier">
                <?php foreach ($list_supplier as $supplier) { ?>
                    <option <?php echo $function_check_supplier_selected($supplier->tsmart_supplier_id,$default)? ' selected ' : '' ?>
                        value="<?php echo $supplier->id ?>"><?php echo $supplier->supplier_name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }


}
