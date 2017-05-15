<?php
/**
 *
 * Currency table
 *
 * @package    tsmart
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
if (!class_exists('tsmTableData')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmtabledata.php');
/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package        tsmart
 * @author RickG, Max Milbers
 */
class TablePayment extends tsmTableData
{
    /** @var int Primary key */
    var $tsmart_payment_id = 0;
    var $payment_name = "";
    var $vat = "";
    var $tsmart_currency_id = 0;
    var $credit_card_fee = "";
    var $cancel_fee = "";
    var $amount = "";
    var $dep_term = "";
    var $hold_seat_orderstate_id = null;
    var $deposit_of_day = "";
    var $deposit_amount_type = "";
    var $balance_of_day_1 = "";
    var $balance_of_day_2 = "";
    var $balance_of_day_3 = "";
    var $percent_balance_of_day_1 = "";
    var $percent_balance_of_day_2 = "";
    var $percent_balance_of_day_3 = "";
    var $cancellation_of_day = "";
    var $bal_term = "";
    var $hold_seat = "";
    var $hold_seat_hours = "";
    var $mode = "";
    var $shared = 0;
    var $published = 0;
    /**
     * @author Max Milbers
     * @param JDataBase $db
     */
    function __construct(&$db)
    {
        parent::__construct('#__tsmart_payment', 'tsmart_payment_id', $db);
        $this->setLoggable();
        $this->setOrderable();
    }
    function check()
    {
        //$this->checkCurrencySymbol();
        return parent::check();
    }
    /**
     * ATM Unused !
     * Checks a departure symbol wether it is a HTML entity.
     * When not and $convertToEntity is true, it converts the symbol
     * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
     *
     */
}
// pure php no closing tag
