<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_tsmart/assets/js/view_reset_password.js');
$doc->addStyleSheet(JUri::root().'/components/com_tsmart/assets/less/view_reset_password.less');
$input=JFactory::getApplication()->input;
$token=$input->getString('token','');
$go_to=$input->getString('go_to','');
?>
<div class="view-reset-default">
    <form action="<?php echo JRoute::_('index.php?option=com_tsmart&view=reset') ?>"
          method="post"
          id="reset_password" name="reset" class="form-horizontal">
        <div class="page_container" style="margin: 0 auto;width: 60%">
            <div class="row-fluid">
                <div class="span12">
                   my page
                </div>
            </div>

            <input type="hidden" value="com_tsmart" name="option">
            <input type="hidden" value="reset" name="controller">
            <input type="hidden" value="reset" name="view">
            <input type="hidden" value="<?php echo $token ?>" name="token">
            <input type="hidden" value="reset_password" name="task">
            <input type="hidden" value="<?php echo $go_to ?>" name="go_to">
        </div>
    </form>
</div>
<?php
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-reset-default').view_reset_password({

            });


        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>