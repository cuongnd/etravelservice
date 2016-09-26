<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Config
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_shop.php 9035 2015-11-03 10:37:57Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_SHOP_SETTINGS'); ?></legend>
	<table class="admintable">
		<?php
			echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_SHOP_OFFLINE','shop_is_offline',tsmConfig::get('shop_is_offline',0));
		?>
		<tr>
			<td class="key">
				<?php echo tsmText::_('com_tsmart_ADMIN_CFG_SHOP_OFFLINE_MSG'); ?>
			</td>
			<td>
				<textarea rows="6" cols="50" name="offline_message"
				          style="text-align: left;"><?php echo tsmConfig::get('offline_message', 'Our Shop is currently down for maintenance. Please check back again soon.'); ?></textarea>
			</td>
		</tr>
		<?php
			echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_USE_ONLY_AS_CATALOGUE','use_as_catalog',tsmConfig::get('use_as_catalog',0));
			echo VmHTML::row('genericlist','com_tsmart_CFG_CURRENCY_MODULE',$this->currConverterList, 'currency_converter_module', 'size=1', 'value', 'text', tsmConfig::get('currency_converter_module', 'convertECB.php'));
			echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_ENABLE_CONTENT_PLUGIN','enable_content_plugin',tsmConfig::get('enable_content_plugin',0));

			echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_SSL','useSSL',tsmConfig::get('useSSL',0));
			echo VmHTML::row('checkbox','com_tsmart_REGISTRATION_CAPTCHA','reg_captcha',tsmConfig::get('reg_captcha',0));
			echo VmHTML::row('checkbox','com_tsmart_VM_ERROR_HANDLING_ENABLE','handle_404',tsmConfig::get('handle_404',1));
		?>
	</table>
</fieldset>

<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_SHOP_LANGUAGES'); ?></legend>
	<table class="admintable">
		<tr>
			<td class="key">
					<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_ADMIN_CFG_MULTILANGUE_TIP'); ?>">
						<?php echo tsmText::_('com_tsmart_ADMIN_CFG_MULTILANGUE'); ?>
					</span>
			</td>
			<td>
				<?php echo $this->activeLanguages; ?>
			 <span>
				<?php echo tsmText::sprintf('com_tsmart_MORE_LANGUAGES','<a href="http://tsmart.net/community/translations" target="_blank" >Translations</a>'); ?>
				</span></td>
		</tr>
		<?php
			echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_ENABLE_ENGLISH','enableEnglish',tsmConfig::get('enableEnglish',1));
		?>

	</table>
</fieldset>



<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_SHOP_ADVANCED'); ?></legend>
	<table class="admintable">
		<?php
			$optDebug = array(
				'none' => tsmText::_('com_tsmart_ADMIN_CFG_ENABLE_DEBUG_NONE'),
				'admin' => tsmText::_('com_tsmart_ADMIN_CFG_ENABLE_DEBUG_ADMIN'),
				'all' => tsmText::_('com_tsmart_ADMIN_CFG_ENABLE_DEBUG_ALL')
			);
			echo VmHTML::row('radiolist','com_tsmart_ADMIN_CFG_ENABLE_DEBUG','debug_enable',tsmConfig::get('debug_enable','none'), $optDebug);
			echo VmHTML::row('radiolist','com_tsmart_CFG_DEV','vmdev',tsmConfig::get('vmdev',0), $optDebug);
			echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_DANGEROUS_TOOLS','dangeroustools',tsmConfig::get('dangeroustools',0));
			echo VmHTML::row('input','com_tsmart_REV_PROXY_VAR','revproxvar',tsmConfig::get('revproxvar',''));
			$optMultiX = array(
				'none' => tsmText::_('com_tsmart_ADMIN_CFG_ENABLE_MULTIX_NONE'),
				'admin' => tsmText::_('com_tsmart_ADMIN_CFG_ENABLE_MULTIX_ADMIN')

				// 				'all'	=> vmText::_('com_tsmart_ADMIN_CFG_ENABLE_DEBUG_ALL')
			);
			echo VmHTML::row('radiolist','com_tsmart_ADMIN_CFG_ENABLE_MULTIX','multix',tsmConfig::get('multix','none'), $optMultiX);
		$optMultiX = array(
			'0' => tsmText::_('com_tsmart_CFG_MULTIX_CART_NONE'),
			'byproduct' => tsmText::_('com_tsmart_CFG_MULTIX_CART_BYPRODUCT'),
			'byvendor' => tsmText::_('com_tsmart_CFG_MULTIX_CART_BYVENDOR'),
			'byselection' => tsmText::_('com_tsmart_CFG_MULTIX_CART_BYSELECTION')
			// 				'all'	=> vmText::_('com_tsmart_ADMIN_CFG_ENABLE_DEBUG_ALL')
		);
		echo VmHTML::row('radiolist','com_tsmart_CFG_MULTIX_CART','multixcart',tsmConfig::get('multixcart',0), $optMultiX);

		?>

	</table>
</fieldset>
