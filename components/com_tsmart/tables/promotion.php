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
class Tablepromotion extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_promotion_price_id				= 0;
	var $price_note				= 0;
	var $sale_period_from				= null;
	var $tsmart_product_id				= null;
	var $sale_period_to				= null;
	var $tsmart_price_id				= 0;
	var $title				= '';
	var $tax				= 0;
	var $tsmart_service_class_id				= 0;
	var $shared					= 0;
	var $published				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_tour_promotion_price', 'tsmart_promotion_price_id', $db);



		$this->setLoggable();

		$this->setOrderable();
	}
    public function bindChecknStore(&$data, $preload = false)
    {
        $tsmart_product_id=$data['tsmart_product_id'];
        $tsmart_service_class_id=$data['tsmart_service_class_id'];
        $tsmart_promotion_price_id=$data['tsmart_promotion_price_id'];
        $sale_period_from=JFactory::getDate($data['sale_period_from']);
        $sale_period_to=JFactory::getDate($data['sale_period_to']);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('COUNT(*)')
            ->from('#__tsmart_tour_promotion_price AS tour_promotion_price')
            ->where('tsmart_service_class_id='.(int)$tsmart_service_class_id)
            ->where('tsmart_product_id='.(int)$tsmart_product_id)
            ->where('tsmart_promotion_price_id<>'.(int)$tsmart_promotion_price_id)
            ->where(
                '((sale_period_from<='.$query->q($sale_period_from->toSql()).' AND sale_period_to>= '.$query->q($sale_period_from->toSql()).') OR (sale_period_from<='.$query->q($sale_period_to->toSql()).' AND sale_period_to>= '.$query->q($sale_period_to->toSql()).'))'


            )
            ;
        $db->setQuery($query);
        $total=$db->loadResult();
        if($total>0)
        {
            $this->setError('exists promotion');
            return false;
        }
        return parent::bindChecknStore($data, $preload); // TODO: Change the autogenerated stub
    }

    function check(){

		//$this->checkCurrencySymbol();
		return parent::check();
	}

	/**
	 * ATM Unused !
	 * Checks a promotion symbol wether it is a HTML entity.
	 * When not and $convertToEntity is true, it converts the symbol
	 * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
	 *
	 */
}
// pure php no closing tag
