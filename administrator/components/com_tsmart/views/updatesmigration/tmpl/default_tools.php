<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage UpdatesMigration
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default_tools.php 8802 2015-03-18 17:12:44Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!tsmConfig::get('dangeroustools', false)){
	$uri = JFactory::getURI();
	$link = $uri->root() . 'administrator/index.php?option=com_tsmart&view=config';
	?>

	<div class="vmquote" style="text-align:left;margin-left:20px;">
	<span style="font-weight:bold;color:green;"> <?php echo tsmText::sprintf('com_tsmart_SYSTEM_DANGEROUS_TOOL_ENABLED_JS',tsmText::_('com_tsmart_ADMIN_CFG_DANGEROUS_TOOLS'),$link) ?></span>
	</div>

	<?php
}

?>
<div id="cpanel">
<table  >
    <tr>


	<td align="left" colspan="2" >
             <h3> <?php echo tsmText::_('com_tsmart_TOOLS_SYNC_MEDIA_FILES'); ?> </h3>
	</td>


    </tr>
    <tr>
<?php /*	<td align="center">
		<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=installSampleData&'.JSession::getFormToken().'=1'); ?>
	    <div class="icon"><a onclick="javascript:confirmation('<?php echo vmText::_('com_tsmart_UPDATE_INSTALLSAMPLE_CONFIRM'); ?>', '<?php echo $link; ?>');">
		<span class="vmicon48 vm_install_48"></span>
	    <br /><?php echo vmText::_('com_tsmart_SAMPLE_DATA'); ?>
		</a></div>
	</td>
	<td align="center">
	    <a href="<?php echo JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=userSync&'.JSession::getFormToken().'=1'); ?>">
		<span class="vmicon48 vm_shoppers_48"></span>
	    </a>
	    <br /><?php echo vmText::_('com_tsmart_SYNC_JOOMLA_USERS'); ?>
		</a></div>
	</td>*/ ?>

 	<td align="center" width="25%">
		<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=portMedia&'.JSession::getFormToken().'=1' ); ?>
	    <div class="icon"><a onclick="javascript:confirmation('<?php echo tsmText::sprintf('com_tsmart_UPDATE_MIGRATION_STRING_CONFIRM', tsmText::_('com_tsmart_MEDIA_S')); ?>', '<?php echo $link; ?>');">
			<span class="vmicon48"></span>
			<br /><?php echo tsmText::_('com_tsmart_TOOLS_SYNC_MEDIA_FILES'); ?>

		</a></div>
	</td>

    <td align="left" width="25%" >
		<?php echo tsmText::sprintf('com_tsmart_TOOLS_SYNC_MEDIAS_EXPLAIN',tsmConfig::get('media_product_path') ,tsmConfig::get('media_category_path') , tsmConfig::get('media_manufacturer_path')); ?>
    </td>

    </tr>
  <tr>
	  <td align="center" width="25%">
		  <?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=resetThumbs&'.JSession::getFormToken().'=1' ); ?>
		  <div class="icon"><a onclick="javascript:confirmation('<?php echo tsmText::_('com_tsmart_TOOLS_RESTHUMB_CONF'); ?>', '<?php echo $link; ?>');">
				  <span class="vmicon48 vm_cpanel_48"></span>
				  <br /><?php echo tsmText::_('com_tsmart_TOOLS_RESTHUMB'); ?>

			  </a></div>
	  </td>

	  <td align="left" width="25%" >

		  <?php echo tsmText::_('com_tsmart_TOOLS_RESTHUMB_TIP'); ?>
	  </td>
    </tr>

    <tr><td align="left" colspan="4"><?php echo tsmText::_('com_tsmart_UPDATE_MIGRATION_TOOLS_WARNING'); ?></td></tr>
<tr>
    <td align="center">
		<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=refreshCompleteInstall&'.JSession::getFormToken().'=1' ); ?>
	    <div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_DELETES_ALL_VM_TABLES_AND_FRESH_CONFIRM_JS') ); ?>', '<?php echo $link; ?>');">
		<span class="vmicon48"></span>
	    <br />
            <?php echo tsmText::_('com_tsmart_DELETES_ALL_VM_TABLES_AND_FRESH'); ?>
		</a></div>
	</td>
	   <td align="center">
		<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=refreshCompleteInstallAndSample&'.JSession::getFormToken().'=1' ); ?>
	    <div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_DELETES_ALL_VM_TABLES_AND_SAMPLE_CONFIRM_JS') ); ?>', '<?php echo $link; ?>');">
		<span class="vmicon48"></span>
	    <br />
            <?php echo tsmText::_('com_tsmart_DELETES_ALL_VM_TABLES_AND_SAMPLE'); ?>
		</a></div>
	</td>

	   <td align="center">
		<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=updateDatabase&'.JSession::getFormToken().'=1' ); ?>
	    <div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_UPDATEDATABASE_CONFIRM_JS') ); ?>', '<?php echo $link; ?>');">
		<span class="vmicon48"></span>
	    <br />
            <?php echo tsmText::_('com_tsmart_UPDATEDATABASE'); ?>
		</a></div>
	</td>
	<td align="center">

	</td>
    </tr>
    <tr>
		<td align="center">
			<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=restoreSystemDefaults&'.JSession::getFormToken().'=1'); ?>
			<div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_UPDATE_RESTOREDEFAULTS_CONFIRM_JS') ); ?>', '<?php echo $link; ?>');">
			<span class="vmicon48 vm_cpanel_48"></span>
			<br /><?php echo tsmText::_('com_tsmart_UPDATE_RESTOREDEFAULTS'); ?>
			</a></div>
		</td>
		<td align="center">
			<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=deleteVmData&'.JSession::getFormToken().'=1' ); ?>
			<div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_UPDATE_REMOVEDATA_CONFIRM_JS') ); ?>', '<?php echo $link; ?>');">
			<span class="vmicon48"></span>
			<br /> <?php echo tsmText::_('com_tsmart_UPDATE_REMOVEDATA'); ?>
			</a></div>
		</td>
		<td align="center">
			<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=deleteVmTables&'.JSession::getFormToken().'=1' ); ?>
			<div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_UPDATE_REMOVETABLES_CONFIRM_JS') ); ?>', '<?php echo $link; ?>');">
			<span class="vmicon48"></span>
			<br />
				<?php echo tsmText::_('com_tsmart_UPDATE_REMOVETABLES'); ?>
			</a></div>
		</td>

    </tr>
	<tr>
		<td align="center">
			<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=deleteInheritedCustoms&'.JSession::getFormToken().'=1' ); ?>
			<div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_UPDATE_DELETE_INHERITEDC') ); ?>', '<?php echo $link; ?>');">
					<span class="vmicon48"></span>
					<br />
					<?php echo tsmText::_('com_tsmart_UPDATE_DELETE_INHERITEDC'); ?>
				</a></div>
		</td>
		<td align="center">
			<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=fixCustomsParams&'.JSession::getFormToken().'=1' ); ?>
			<div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('com_tsmart_UPDATE_OLD_CUSTOMFORMAT') ); ?>', '<?php echo $link; ?>');">
					<span class="vmicon48"></span>
					<br />
					<?php echo tsmText::_('com_tsmart_UPDATE_OLD_CUSTOMFORMAT'); ?>
				</a></div>
		</td>
		<td align="center">
			<?php $link=JROUTE::_('index.php?option=com_tsmart&view=updatesmigration&task=updateDatabaseJoomla&'.JSession::getFormToken().'=1' ); ?>
			<div class="icon"><a onclick="javascript:confirmation('<?php echo addslashes( tsmText::_('Update Joomla Database') ); ?>', '<?php echo $link; ?>');">
					<span class="vmicon48"></span>
					<br />
					<?php echo tsmText::_('Update Joomla Database for pros, use only if you know what you do'); ?>
				</a></div>
		</td>
	</tr>
</table>
</div>
<div>

</div>
<script type="text/javascript">
<!--
function confirmation(message, destnUrl) {
	var answer = confirm(message);
	if (answer) {
		window.location = destnUrl;
	}
}
//-->
</script>