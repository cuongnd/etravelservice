<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage Category
* @author RickG, jseros
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 8508 2014-10-22 18:57:14Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
$editor = JFactory::getEditor();

?>

<form action="index.php" method="post" id="adminForm" name="adminForm" enctype="multipart/form-data">

<?php // Loading Templates in Tabs
AdminUIHelper::buildTabs ( $this, array (	'categoryform' 	=> 	'com_tsmart_CATEGORY_FORM_LBL',
									'images' 	=> 	'com_tsmart_IMAGES'
									 ),$this->category->tsmart_category_id );
?>
	<input type="hidden" name="tsmart_category_id" value="<?php echo $this->category->tsmart_category_id; ?>" />

	<?php echo $this->addStandardHiddenToForm(); ?>

</form>

<?php AdminUIHelper::endAdminArea(); ?>