<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage UpdatesMigration
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_update.php 3274 2011-05-17 20:43:48Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_tsmart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig();

VmConfig::loadJLang('com_tsmart.sys');
VmConfig::loadJLang('com_tsmart');

$update = vRequest::getInt('update',0);
$option = vRequest::getString('option');

if($option=='com_tsmart'){

	if (!class_exists('AdminUIHelper')) require(VMPATH_ADMIN.DS.'helpers'.DS.'adminui.php');
	if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');
	if (!class_exists ('TsmartViewUpdatesMigration'))
		require(VMPATH_ADMIN . DS . 'views' . DS . 'updatesmigration' .DS. 'view.html.php');

	$view = new TsmartViewUpdatesMigration();
	AdminUIHelper::startAdminArea($view);
}
?>

	<table
		width="100%"
		border="0">
		<tr>
			<td>
				<strong>
					<?php
					if($update){
						echo  tsmText::_('com_tsmart_UPGRADE_SUCCESSFUL');
					} else {
						echo tsmText::_('com_tsmart_INSTALLATION_SUCCESSFUL');
					}
					?>
				</strong>

			</td>
		</tr>
		<?php  if (vRequest::get('view','')=='install') {
			if (JVM_VERSION < 3) {
			$tag="strong";$style='style="color: #C00"';
			} else {
				$tag="span";
				$style = 'class="label label-warning"';
			} ?>
			<tr>
				<td>
					<<?php echo $tag.' '.$style ?>>
						<?php
						if ($update) {
							echo tsmText::_('com_tsmart_UPDATE_AIO');
						} else {
							echo tsmText::_('com_tsmart_INSTALL_AIO');
						}
						?>
					</<?php echo $tag ?>>
					<?php echo tsmText::_('com_tsmart_INSTALL_AIO_TIP'); ?>
				</td>
			</tr>
		<?php
		}
		$class="";
		if (vRequest::get('view','')=='install') {
			if (JVM_VERSION < 3) {
				$class = "button";
			} else {
				$class = "btn";
			}
		}
		?>
		<tr>
			<td><span class="<?php echo $class ?>">
				<?php echo tsmText::sprintf('com_tsmart_MORE_LANGUAGES','http://tsmart.net/community/translations'); ?>
				</span>
			</td>
		</tr>
		<tr>
			<td><span class="<?php echo $class ?>">
				<a href="http://docs.virtuemart.net"><?php echo tsmText::_('com_tsmart_DOCUMENTATION'); ?></a>
				</span>
			</td>
		</tr>
		<tr>
			<td><span class="<?php echo $class ?>">
				<a href="http://extensions.virtuemart.net"><?php echo  tsmText::_('com_tsmart_EXTENSIONS_MORE'); ?></a>
				</span>
			</td>
		</tr>
	</table>

<?php
if($option=='com_tsmart'){
	AdminUIHelper::endAdminArea();
}

?>
