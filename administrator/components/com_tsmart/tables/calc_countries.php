<?php
/**
*
* tsmart_calc_countries table ( to map calc rules to countries)
*
* @package	tsmart
* @subpackage Calculation tool
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2011 - 2014 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: tsmart_calc_countries.php 3002 2011-04-08 12:35:45Z alatak $
*/

defined('_JEXEC') or die();

if(!class_exists('tsmTableXarray'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtablexarray.php');

class TableCalc_countries extends tsmTableXarray {

	function __construct(&$db){
		parent::__construct('#__tsmart_calc_countries', 'id', $db);

		$this->setPrimaryKey('tsmart_calc_id');
		$this->setSecondaryKey('tsmart_country_id');
	}

}
