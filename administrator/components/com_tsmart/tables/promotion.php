<?php
/**
*
* Currency table
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currencies.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmtabledata.php');

/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		VirtueMart
 * @author RickG, Max Milbers
 */
class Tablepromotion extends VmTableData {

	/** @var int Primary key */
	var $virtuemart_promotion_price_id				= 0;
	var $price_note				= 0;
	var $sale_period_from				= null;
	var $virtuemart_product_id				= null;
	var $sale_period_to				= null;
	var $virtuemart_price_id				= 0;
	var $title				= '';
	var $tax				= 0;
	var $virtuemart_service_class_id				= 0;
	var $shared					= 0;
	var $published				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__virtuemart_tour_promotion_price', 'virtuemart_promotion_price_id', $db);



		$this->setLoggable();

		$this->setOrderable();
	}
    public function bindChecknStore(&$data, $preload = false)
    {
        $virtuemart_product_id=$data['virtuemart_product_id'];
        $virtuemart_service_class_id=$data['virtuemart_service_class_id'];
        $virtuemart_promotion_price_id=$data['virtuemart_promotion_price_id'];
        $sale_period_from=JFactory::getDate($data['sale_period_from']);
        $sale_period_to=JFactory::getDate($data['sale_period_to']);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('COUNT(*)')
            ->from('#__virtuemart_tour_promotion_price AS tour_promotion_price')
            ->where('virtuemart_service_class_id='.(int)$virtuemart_service_class_id)
            ->where('virtuemart_product_id='.(int)$virtuemart_product_id)
            ->where('virtuemart_promotion_price_id<>'.(int)$virtuemart_promotion_price_id)
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