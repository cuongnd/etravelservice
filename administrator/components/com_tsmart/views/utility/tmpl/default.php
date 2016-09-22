<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_utility_default.js');
AdminUIHelper::startAdminArea($this);

?>
<div class="view-utility-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div class="row">
            <div class="span12">
                <input type="button" class="check_database" value="Check database">
            </div>
        </div>
        <input type="hidden" name="option" value="com_tsmart"/>
        <input type="hidden" name="controller" value="utility"/>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php
    $js_content = '';
    $app = JFactory::getApplication();

    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-utility-default').view_utility_default({});
        });
    </script>
    <?php
    $js_content = ob_get_clean();
    $js_content = JUtility::remove_string_javascript($js_content);
    $doc->addScriptDeclaration($js_content);

    ?>
    <?php AdminUIHelper::endAdminArea(); ?>
</div>
