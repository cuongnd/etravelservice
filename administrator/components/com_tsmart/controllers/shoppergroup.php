<?php
/**
*
* Shopper Group controller
*
* @package	tsmart
* @subpackage ShopperGroup
* @author Markus �hler
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: shoppergroup.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Shopper Group Controller
 *
 * @package    tsmart
 * @subpackage ShopperGroup
 * @author Markus �hler
 */
class TsmartControllerShopperGroup extends TsmController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		parent::__construct('tsmart_shoppergroup_id');
	}

}
// pure php no closing tag
