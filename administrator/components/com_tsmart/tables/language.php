<?php
/**
*
* Currency table
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currencies.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');

/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		tsmart
 * @author RickG, Max Milbers
 */
class TableLanguage extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_language_id				= 0;
	/** @var int vendor id */
	var $tsmart_vendor_id					= 1;
	/** @var string Currency name*/
	var $language_name 			= '';
	var $currency_numeric_code	= 0;
	var $currency_exchange_rate = 0.0;
	var $currency_symbol		= '';
	var $currency_decimal_place		= 0;
	var $currency_decimal_symbol		= '';
	var $currency_thousands		= '';
	var $currency_positive_style	= '';
	var $currency_negative_style	= '';
	var $sign	= '';
	var $tsmart_country_id	= 0;
	var $shared					= 0;
	var $published				= 1;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_language', 'tsmart_language_id', $db);

		$this->setUniqueName('language_name');

		$this->setLoggable();

		$this->setOrderable();
	}

	function check(){

		//$this->checkCurrencySymbol();
		return parent::check();
	}

	/**
	 * ATM Unused !
	 * Checks a currency symbol wether it is a HTML entity.
	 * When not and $convertToEntity is true, it converts the symbol
	 * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
	 *
	 */
	function checkCurrencySymbol($convertToEntity=true ) {

		$symbol = str_replace('&amp;', '&', $this->currency_symbol );

		if( substr( $symbol, 0, 1) == '&' && substr( $symbol, strlen($symbol)-1, 1 ) == ';') {
			return $symbol;
		}
		else {
			if( $convertToEntity ) {
				$symbol = htmlentities( $symbol, ENT_QUOTES, 'utf-8' );

				if( substr( $symbol, 0, 1) == '&' && substr( $symbol, strlen($symbol)-1, 1 ) == ';') {
					return $symbol;
				}
				// Sometimes htmlentities() doesn't return a valid HTML Entity
				switch( ord( $symbol ) ) {
					case 128:
					case 63:
						$symbol = '&euro;';
						break;
				}

			}
		}

		 $this->currency_symbol = $symbol;
	}
}
// pure php no closing tag
