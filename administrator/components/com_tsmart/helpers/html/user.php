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
abstract class TSMHtmlUser
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
    public static function select_user_name($list_user = array(), $name, $default = '0', $attrib = "onchange='submit();'", $zero = true, $chosenDropDowns = true, $tranlsate = true)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root() . '/media/system/js/select2-master/dist/js/select2.full.js');
        $doc->addStyleSheet(JUri::root() . '/media/system/js/select2-master/dist/css/select2.css');
        $doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/user/user_html_select_user.js');
        $doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/js/controller/user/user_html_select_user.less');
        $input = JFactory::getApplication()->input;
        if (empty($list_user)) {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmuser.php';
            $list_user = tsmuser::get_list_user();
            foreach($list_user as &$user){
                $user=(object)array_intersect_key((array)$user, array(id=>0,name=>""));
            }

        }
        $id_element = 'user_html_select_user_' . $name;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php  echo $id_element ?>').user_html_select_user({
                    list_user:<?php echo json_encode($list_user) ?>,
                    name: "<?php echo $name ?>",
                    user_id:<?php echo $default ? $default : 0 ?>
                });
            });
        </script>
        <?php
        $script_content = ob_get_clean();
        $script_content = TSMUtility::remove_string_javascript($script_content);
        $doc->addScriptDeclaration($script_content);
        $function_check_user_selected=function($user_id,$default){
            if(is_numeric($default)){
                return  $user_id == $default;
            }else if(is_array($default)){
                return in_array($user_id,$default);
            }else{
                return false;
            }
        };
        ob_start();
        ?>
        <div id="<?php echo $id_element ?>">
            <select  disable_chosen="true"  <?php echo $attrib ?>   <?php echo is_array($default)?' multiple ':'' ?>   id="<?php echo $name ?>" name="<?php echo $name ?>" class="user">
                <?php foreach ($list_user as $user) { ?>
                    <option <?php echo $function_check_user_selected($user->id,$default)? ' selected ' : '' ?>
                        value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }


}
