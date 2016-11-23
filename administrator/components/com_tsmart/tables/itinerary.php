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
class Tableitinerary extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_itinerary_id				= 0;
	var $title				= "";
	var $overnight				= 0;
	var $short_description				= "";
	var $full_description				= "";
	var $tsmart_cityarea_id				= 0;
	var $tsmart_product_id				= 0;
	var $trip_note				= "";
	var $photo1				= "";
	var $photo2				= "";
	var $shared					= 0;
	var $published				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_itinerary', 'tsmart_itinerary_id', $db);


		$this->setLoggable();

		$this->setOrderable();
	}

    public function bindChecknStore(&$data, $preload = false)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('itinerary.tsmart_itinerary_id')
            ->from('#__tsmart_itinerary AS itinerary')
            ->where('itinerary.tsmart_itinerary_id!='.(int)$data['tsmart_itinerary_id'])
            ->where('itinerary.tsmart_product_id='.(int)$data['tsmart_product_id'])
            ->where('itinerary.title = '.$query->q("{$data['title']}"))
        ;
        $db->setQuery($query);
        $list_itinerary=$db->loadObjectList();
        if(count($list_itinerary)>0)
        {
            vmError('itinerary title exists, please select other itinerary title');
            return false;
        }
        return parent::bindChecknStore($data, $preload); // TODO: Change the autogenerated stub
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
