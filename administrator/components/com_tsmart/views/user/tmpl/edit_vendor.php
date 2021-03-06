<?php
/**
*
* Modify user form view, User info
*
* @package	tsmart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit_vendor.php 9012 2015-10-09 11:49:32Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>

<div class="col50">

				<fieldset>
					<legend>
						<?php echo tsmText::_('com_tsmart_VENDOR_FORM_INFO_LBL') ?>
					</legend>
					<table class="admintable">
						<tr>
							<td class="key">
								<?php echo tsmText::_('com_tsmart_STORE_FORM_STORE_NAME'); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="vendor_store_name" id="vendor_store_name" size="50" value="<?php echo $this->vendor->vendor_store_name; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo tsmText::_('com_tsmart_STORE_FORM_COMPANY_NAME'); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="vendor_name" id="vendor_name" size="50" value="<?php echo $this->vendor->vendor_name; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo tsmText::_('com_tsmart_PRODUCT_FORM_URL'); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="vendor_url" id="vendor_url" size="50" value="<?php echo $this->vendor->vendor_url; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo tsmText::_('com_tsmart_STORE_FORM_MPOV'); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="vendor_min_pov" id="vendor_min_pov" size="10" value="<?php echo $this->vendor->vendor_min_pov; ?>" />
							</td>
						</tr>

					</table>
				</fieldset>

				<fieldset>
					<legend>
						<?php echo tsmText::_('com_tsmart_STORE_CURRENCY_DISPLAY') ?>
					</legend>
					<table class="admintable">
						<tr>
							<td class="key">
								<?php echo tsmText::_('com_tsmart_CURRENCY'); ?>:
							</td>
							<td>
								<?php echo JHtml::_('Select.genericlist', $this->currencies, 'vendor_currency', '', 'tsmart_currency_id', 'currency_name', $this->vendor->vendor_currency); ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo tsmText::_('com_tsmart_STORE_FORM_ACCEPTED_CURRENCIES'); ?>:
							</td>
							<td>
								<?php echo JHtml::_('Select.genericlist', $this->currencies, 'vendor_accepted_currencies[]', 'size=10 multiple="multiple" data-placeholder="'.tsmText::_('com_tsmart_DRDOWN_SELECT_SOME_OPTIONS').'"', 'tsmart_currency_id', 'currency_name', $this->vendor->vendor_accepted_currencies); ?>
							</td>
						</tr>
					</table>
				</fieldset>

		<fieldset>
			<legend>
				<?php echo tsmText::_('com_tsmart_VENDOR_FORM_INFO_LBL') ?>
			</legend>
			<?php
			vmdebug('$this->vendor->tsmart_vendor_id',$this->vendor->tsmart_vendor_id);
				echo $this->vendor->images[0]->displayFilesHandler($this->vendor->tsmart_media_id,'vendor',$this->vendor->tsmart_vendor_id);
			?>


		</fieldset>


				<fieldset>
					<legend>
						<?php echo tsmText::_('com_tsmart_STORE_FORM_DESCRIPTION');?>
					</legend>
					<?php echo $this->editor->display('vendor_store_desc', $this->vendor->vendor_store_desc, '100%', 350, 70, 15)?>
				</fieldset>

				<fieldset>
					<legend>
						<?php echo tsmText::_('com_tsmart_STORE_FORM_TOS');?>
					</legend>
					<?php echo $this->editor->display('vendor_terms_of_service', $this->vendor->vendor_terms_of_service, '100%', 350, 70, 15)?>
				</fieldset>

				<fieldset>
					<legend>
						<?php echo tsmText::_('com_tsmart_STORE_FORM_LEGAL');?>
					</legend>
					<?php echo $this->editor->display('vendor_legal_info', $this->vendor->vendor_legal_info, '100%', 100, 70, 15)?>
				</fieldset>

			<fieldset>
				<legend><?php echo tsmText::_('com_tsmart_METAINFO'); ?></legend>
				<?php echo shopFunctions::renderMetaEdit($this->vendor); ?>
			</fieldset>

</div>
<input type="hidden" name="user_is_vendor" value="1" />
<input type="hidden" name="tsmart_vendor_id" value="<?php echo $this->vendor->tsmart_vendor_id; ?>" />
<input type="hidden" name="last_task" value="<?php echo vRequest::getCmd('task'); ?>" />
