<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage Shipment
 * @author RickG
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
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">

<?php // Loading Templates in Tabs
$tabarray = array();
$tabarray['edit'] = 'com_tsmart_ADMIN_SHIPMENT_FORM';
$tabarray['config'] = 'com_tsmart_ADMIN_SHIPMENT_CONFIGURATION';

AdminUIHelper::buildTabs ( $this, $tabarray , $this->shipment->tsmart_shipmentmethod_id );
// Loading Templates in Tabs END ?>

    <!-- Hidden Fields -->
	<?php echo $this->addStandardHiddenToForm(); ?>
<input type="hidden" name="tsmart_shipmentmethod_id" value="<?php echo $this->shipment->tsmart_shipmentmethod_id; ?>" />
<input type="hidden" name="xxcontroller" value="shipmentmethod" />

</form>
    <?php AdminUIHelper::endAdminArea(); ?>
