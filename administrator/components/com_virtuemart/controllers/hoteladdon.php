<?php
/**
*
* Currency controller
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
* @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Currency Controller
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class VirtuemartControllerHoteladdon extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct();


	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = vRequest::getRequest();
		parent::save($data);
	}
	public function get_detail_hotel()
	{
		$input=JFactory::getApplication()->input;
		$virtuemart_hotel_id=$input->getInt('virtuemart_hotel_id',0);
		$db=JFactory::getDbo();
		require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmhoteladdon.php';
		$hotel=vmHotelAddon::get_detail_hotel_by_hotel_id($virtuemart_hotel_id);
		echo json_encode($hotel);
		die;
	}
	public function get_tour_avail_by_hotel_id_first_itinerary()
	{
		$input=JFactory::getApplication()->input;
		$virtuemart_hotel_id=$input->getInt('virtuemart_hotel_id',0);
		$hotel_addon_type=$input->getString('hotel_addon_type','');
		$db=JFactory::getDbo();

		$query=$db->getQuery(true);
		$query->select('itinerary.*')
			->from('#__virtuemart_itinerary AS itinerary')
			;
		$list_itinerary=$db->setQuery($query)->loadObjectList();
		$list_itinerary_id=array();
		foreach($list_itinerary as $itinerary)
		{
			$key=$itinerary->virtuemart_product_id;
			if($key)
			{
				$item = new stdClass();
				$item->virtuemart_product_id = $itinerary->virtuemart_product_id;
				$item->virtuemart_itinerary_id = $itinerary->virtuemart_itinerary_id;
				$item->ordering = $itinerary->ordering;
				if (!isset($list_itinerary_id[$key])) {
					$list_itinerary_id[$key] = $item;
				} elseif ($list_itinerary_id[$key]->virtuemart_product_id = $itinerary->virtuemart_product_id ) {
					if($hotel_addon_type=='pre_transfer'&&$list_itinerary_id[$key]->ordering > $itinerary->ordering)
					{
						$list_itinerary_id[$key] = $item;
					}elseif($hotel_addon_type=='post_transfer'&&$list_itinerary_id[$key]->ordering < $itinerary->ordering)
					{
						$list_itinerary_id[$key] = $item;
					}

				}
			}
		}
		$a_list_itinerary_id=array();
		foreach($list_itinerary_id as $itinerary)
		{
			$a_list_itinerary_id[]=$itinerary->virtuemart_itinerary_id;
		}
		array_unshift($a_list_itinerary_id,0);
		$a_list_itinerary_id=implode(',',$a_list_itinerary_id);
		$query->clear();
		$query->select('product.virtuemart_product_id')
			->from('#__virtuemart_products AS product')
			->leftJoin('#__virtuemart_itinerary AS itinerary USING(virtuemart_product_id)')
			->leftJoin('#__virtuemart_accommodation AS accommodation ON accommodation.virtuemart_itinerary_id=itinerary.virtuemart_itinerary_id')
			->leftJoin('#__virtuemart_hotel_id_service_class_id_accommodation_id AS hotel_id_service_class_id_accommodation_id ON hotel_id_service_class_id_accommodation_id.virtuemart_accommodation_id=accommodation.virtuemart_accommodation_id')
			->where('hotel_id_service_class_id_accommodation_id.virtuemart_hotel_id='.(int)$virtuemart_hotel_id)
			->where('itinerary.virtuemart_itinerary_id IN('.$a_list_itinerary_id.')')
			->group('product.virtuemart_product_id')
		;
		//echo $query->dump();
		$list_virtuemart_product_id=$db->setQuery($query)->loadColumn();
		echo json_encode($list_virtuemart_product_id);
		die;

	}
}
// pure php no closing tag
