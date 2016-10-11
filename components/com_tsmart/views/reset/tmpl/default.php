<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_virtuemart/assets/js/view_reset_password.js');
$doc->addStyleSheet(JUri::root().'/components/com_virtuemart/assets/less/view_reset_password.less');
$input=JFactory::getApplication()->input;
$token=$input->getString('token','');
$go_to=$input->getString('go_to','');
?>
<div class="view-reset-default">
    <form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=reset') ?>"
          method="post"
          id="reset_password" name="reset" class="form-horizontal">
        <div class="page_container" style="margin: 0 auto;width: 60%">
            <div class="row-fluid">
                <div class="span12">
                    <div class="control-group">
                        <label class="control-label" for="inputEmail"><?php echo JText::_('Rassword')?></label>
                        <div class="controls">
                            <?php echo VmHTML::password('password', '', ' placeholder="'.JText::_('Rassword').'" '); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputEmail"><?php echo JText::_('Retype password')?></label>
                        <div class="controls">
                            <?php echo VmHTML::password('password1', '', ' placeholder="'.JText::_('Retype password').'" '); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <button type="submit" class="btn btn-primary pull-right"><?php echo JText::_('Submit')?></button>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div><?php echo JText::_('You need to keep in mind this password for your future sign in.') ?></div>
                </div>
            </div>

            <input type="hidden" value="com_virtuemart" name="option">
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