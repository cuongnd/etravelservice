<?php
/**
*
* Calc table ( for calculations)
*
* @package	tsmart
* @subpackage Calculation tool
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2011- 2014 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: calcs.php 3151 2011-05-03 16:28:43Z Milbo $
*/
defined('_JEXEC') or die();

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');
/**
 * Calculator table class
 * The class is is used to manage the calculation in the shop.
 *
 * @author Max Milbers
 * @package		tsmart
 */
class TableCalcs extends tsmTable
{
	/** @var int Primary key */
	var $tsmart_calc_id					= 0;
	/** @var string VendorID of the rule creator */
	var $tsmart_vendor_id				= 0;
	var $calc_jplugin_id            = 0;
	//var $calc_element            = '';
	/** @var string Calculation name */
	var $calc_name           		= '';
	/** @var string Calculation description */
	var $calc_descr           		= '';
	/** @var string Calculation kind */
	var $calc_kind           		= '';
   	/** @var string Calculation mathematical Operation */
	var $calc_value_mathop       	= '';
	/** @var string Calculation value of the mathop */
	var $calc_value       		 	= '';
	var $calc_params       		 	= '';
	/** @var string Currency used in the calculation */
	var $calc_currency				= '';

	/** @var string Visible for shoppers */
	var $calc_shopper_published		= 0;
	/** @var string Visible for Vendors */
	var $calc_vendor_published		= 0;
	/** @var string start date */
	var $publish_up;
	/** @var string end date */
	var $publish_down;

	/** @var Affects the rule all products of all Vendors? */
	var $shared				= 0;//this must be forbidden to set for normal vendors, that means only setable Administrator permissions or vendorId=1
    /** @var int published or unpublished */
	var $ordering	= 0;
    var $published 		        = 0;


	function __construct(&$db){
		parent::__construct('#__tsmart_calcs', 'tsmart_calc_id', $db);

		$this->setObligatoryKeys('calc_name');
		$this->setObligatoryKeys('calc_kind');
		$this->setLoggable();

	}

}
// pure php no closing tag
