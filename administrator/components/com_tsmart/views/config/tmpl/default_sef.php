<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Config
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_sef.php 7004 2013-06-20 08:34:18Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>
<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_SEO_SETTINGS'); ?></legend>
	<table class="admintable">
		<?php
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_SEO_DISABLE','seo_disabled', tsmConfig::get('seo_disabled', 0));
		echo VmHTML::row('input','com_tsmart_ADMIN_CFG_SEO_SUFIX','seo_sufix', tsmConfig::get('seo_sufix', '-detail'));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_SEO_TRANSLATE','seo_translate', tsmConfig::get('seo_translate', 1));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_SEO_USE_ID','seo_use_id', tsmConfig::get('seo_use_id'));
		?>
	</table>
</fieldset>