<?php
/**
*
* Currency controller
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
* @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllercoupon extends TsmController {

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
		$tsmart_hotel_id=$input->getInt('tsmart_hotel_id',0);
		$db=JFactory::getDbo();
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmcoupon.php';
		$hotel=tsmcoupon::get_detail_hotel_by_hotel_id($tsmart_hotel_id);
		echo json_encode($hotel);
		die;
	}
	public function get_tour_avail_by_hotel_id_first_itinerary()
	{
		$input=JFactory::getApplication()->input;
		$tsmart_hotel_id=$input->getInt('tsmart_hotel_id',0);
		$hotel_addon_type=$input->getString('hotel_addon_type','');
		$db=JFactory::getDbo();

		$query=$db->getQuery(true);
		$query->select('itinerary.*')
			->from('#__tsmart_itinerary AS itinerary')
			;
		$list_itinerary=$db->setQuery($query)->loadObjectList();
		$list_itinerary_id=array();
		foreach($list_itinerary as $itinerary)
		{
			$key=$itinerary->tsmart_product_id;
			if($key)
			{
				$item = new stdClass();
				$item->tsmart_product_id = $itinerary->tsmart_product_id;
				$item->tsmart_itinerary_id = $itinerary->tsmart_itinerary_id;
				$item->ordering = $itinerary->ordering;
				if (!isset($list_itinerary_id[$key])) {
					$list_itinerary_id[$key] = $item;
				} elseif ($list_itinerary_id[$key]->tsmart_product_id = $itinerary->tsmart_product_id ) {
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
			$a_list_itinerary_id[]=$itinerary->tsmart_itinerary_id;
		}
		array_unshift($a_list_itinerary_id,0);
		$a_list_itinerary_id=implode(',',$a_list_itinerary_id);
		$query->clear();
		$query->select('product.tsmart_product_id')
			->from('#__tsmart_products AS product')
			->leftJoin('#__tsmart_itinerary AS itinerary USING(tsmart_product_id)')
			->leftJoin('#__tsmart_accommodation AS accommodation ON accommodation.tsmart_itinerary_id=itinerary.tsmart_itinerary_id')
			->leftJoin('#__tsmart_hotel_id_service_class_id_accommodation_id AS hotel_id_service_class_id_accommodation_id ON hotel_id_service_class_id_accommodation_id.tsmart_accommodation_id=accommodation.tsmart_accommodation_id')
			->where('hotel_id_service_class_id_accommodation_id.tsmart_hotel_id='.(int)$tsmart_hotel_id)
			->where('itinerary.tsmart_itinerary_id IN('.$a_list_itinerary_id.')')
			->group('product.tsmart_product_id')
		;
		//echo $query->dump();
		$list_tsmart_product_id=$db->setQuery($query)->loadColumn();
		echo json_encode($list_tsmart_product_id);
		die;

	}
	public function check_tour()
	{
		$app=JFactory::getApplication();
		$tsmart_product_id=$app->input->get('tsmart_product_id',0);
		$vail_from=$app->input->get('vail_from',0);
        $vail_from=JFactory::getDate($vail_from);
        $vail_to=$app->input->get('vail_to',0);
        $vail_to=JFactory::getDate($vail_to);
        $tsmart_hotel_addon_id=$app->input->get('tsmart_hotel_addon_id',0);
        $hotel_addon_type=$app->input->getString('hotel_addon_type','');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('count(*) AS total_record')
            ->from('#__tsmart_hotel_addon_date_price AS hotel_addon_date_price')
            ->where('hotel_addon_date_price.tsmart_hotel_addon_id!='.(int)$tsmart_hotel_addon_id)
            ->where('hotel_addon_date_price.tsmart_product_id='.(int)$tsmart_product_id)
            ->where('hotel_addon_date_price.hotel_addon_type='.$query->q($hotel_addon_type))
            ->where('(hotel_addon_date_price.date>='.$query->q($vail_from->toSql()).' AND hotel_addon_date_price.date<='.$query->q($vail_to->toSql()).')')
            ;
        $db->setQuery($query);
        $total_record=$db->loadResult();
        $return=new stdClass();

        if($total_record>0)
        {
            $return->error=1;
            $return->msg="you cannot select this tour";
        }else{
            $return->error=0;
            $return->msg="you can select this tour";
        }
        echo json_encode($return);
        die;


	}
}
// pure php no closing tag
