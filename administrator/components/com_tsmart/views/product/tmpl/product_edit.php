<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit.php 8578 2014-11-18 18:24:06Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');

AdminUIHelper::startAdminArea($this);


$document = JFactory::getDocument();
$document=JFactory::getDocument();
$document->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_product_edit.less');
$document->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_product_edit.js');
$this->editor = JFactory::getEditor();
$js_content = '';
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-product-edit').view_product_edit({});
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$document->addScriptDeclaration($js_content);

?>


<div class="view-product-edit">

    <form method="post" name="adminForm" class="form-horizontal" action="index.php" enctype="multipart/form-data" id="adminForm">

        <?php // Loading Templates in Tabs
        $tabarray = array();
        $tabarray['information'] = 'com_tsmart_PRODUCT_FORM_PRODUCT_INFO_LBL';
        $tabarray['advance'] = 'advance';
        //$tabarray['description'] = 'com_tsmart_PRODUCT_FORM_DESCRIPTION';
        //$tabarray['images'] = 'com_tsmart_PRODUCT_FORM_PRODUCT_IMAGES_LBL';

        //$tabarray['custom'] = 'com_tsmart_PRODUCT_FORM_PRODUCT_CUSTOM_TAB';
        //$tabarray['emails'] = 'com_tsmart_PRODUCT_FORM_EMAILS_TAB';
        // $tabarray['customer'] = 'com_tsmart_PRODUCT_FORM_CUSTOMER_TAB';
        echo $this->loadTemplate('information');

        // Loading Templates in Tabs END ?>


        <!-- Hidden Fields -->

        <?php echo $this->addStandardHiddenToForm1(); ?>

        <input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>"/>
        <input type="hidden" name="product_parent_id"
               value="<?php echo vRequest::getInt('product_parent_id', $this->product->product_parent_id); ?>"/>
    </form>
    <?php AdminUIHelper::endAdminArea(); ?>
    <?php //$document->addScriptDeclaration( 'jQuery(window).load(function(){ jQuery.ajaxSetup({ cache: false }); })'); ?>
</div>