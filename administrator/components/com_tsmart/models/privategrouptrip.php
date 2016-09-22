<?php
/**
 *
 * Data module for shop product
 *
 * @package	VirtueMart
 * @subpackage product
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop product
 *
 * @package	VirtueMart
 * @subpackage product
 */
class VirtueMartModelPrivategrouptrip extends VmModel {

    protected $context = 'privategrouptrip';

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('price');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		$item= $this->getData($id);
        return $item;
	}
    public function getItems()
    {
        $items= parent::getItems(); // TODO: Change the autogenerated stub
        foreach($items as &$item)
        {
            $type=$item->mark_up_type;
            $tax=$item->tax/100;
            if($type=='amount')
            {
                $price_senior=$item->price_senior+$item->mark_up_price_senior;
                $item->price_senior=round($price_senior+$price_senior.$tax);


                $price_adult=$item->price_adult+$item->mark_up_price_adult;
                $item->price_adult=round($price_adult+$price_adult.$tax);


                $price_teen=$item->price_teen+$item->mark_up_price_teen;
                $item->price_teen=round($price_teen+$price_teen.$tax);


                $price_infant=$item->price_infant+$item->mark_up_price_infant;
                $item->price_infant=round($price_infant+$price_infant.$tax);


                $price_children1=$item->price_children1+$item->mark_up_price_children1;
                $item->price_children1=round($price_children1+$price_children1.$tax);


                $price_children2=$item->price_children2+$item->mark_up_price_children2;
                $item->price_children2=round($price_children2+$price_children2.$tax);


                $price_private_room=$item->price_private_room+$item->mark_up_price_private_room;
                $item->price_private_room=round($price_private_room+$price_private_room.$tax);


                $extra_bed=$item->extra_bed+$item->mark_up_extra_bed;
                $item->extra_bed=round($extra_bed+$extra_bed.$tax);


            }else{
                $price_senior=$item->price_senior+$item->price_senior.$item->mark_up_senior/100;
                $item->price_senior=round($price_senior+$price_senior.$tax);


                $price_adult=$item->price_adult+$item->price_adult*$item->mark_up_adult/100;
                $item->price_adult=round($price_adult+$price_adult.$tax);


                $price_teen=$item->price_teen+$item->price_teen*$item->mark_up_teen/100;
                $item->price_teen=round($price_teen+$price_teen.$tax);


                $price_infant=$item->price_infant+$item->price_infant*$item->mark_up_infant/100;
                $item->price_infant=round($price_infant+$price_infant.$tax);


                $price_children1=$item->price_children1+$item->price_children1*$item->mark_up_children1/100;
                $item->price_children1=round($price_children1+$price_children1.$tax);


                $price_children2=$item->price_children2+$item->price_children2*$item->mark_up_children2/100;
                $item->price_children2=round($price_children2+$price_children2.$tax);


                $price_private_room=$item->price_private_room+$item->price_private_room*$item->mark_up_private_room/100;
                $item->price_private_room=round($price_private_room+$price_private_room.$tax);


                $extra_bed=$item->extra_bed+$item->extra_bed*$item->mark_up_extra_bed/100;
                $item->extra_bed=round($extra_bed+$extra_bed.$tax);

            }
        }
        return $items;
    }

    public function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('site');

        // Load the filter state.
        $total_passenger_from_12_years_old = $this->getUserStateFromRequest($this->context . '.filter.total_passenger_from_12_years_old', 'filter_total_passenger_from_12_years_old', 0, 'int');
        $this->setState('filter.total_passenger_from_12_years_old', $total_passenger_from_12_years_old);

        $total_passenger_under_12_years_old = $this->getUserStateFromRequest($this->context . '.filter.total_passenger_under_12_years_old', 'filter_total_passenger_under_12_years_old', 0, 'int');
        $this->setState('filter.total_passenger_under_12_years_old', $total_passenger_under_12_years_old);


        $start_date = $this->getUserStateFromRequest($this->context . '.filter.start_date', 'filter_start_date', '', 'string');
        $this->setState('filter.start_date', $start_date);

        $month = $this->getUserStateFromRequest($this->context . '.filter.month', 'filter_month', '', 'string');
        $this->setState('filter.month', $month);

    }

    function getListQuery()
	{
        $app=JFactory::getApplication();
        $input=$app->input;
        $virtuemart_product_id=$input->getInt('virtuemart_product_id',0);
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
        $query->select('service_class.service_class_name,tour_price.virtuemart_product_id')
            ->from('#__virtuemart_service_class AS service_class')
            ->innerJoin('#__virtuemart_tour_price AS tour_price ON tour_price.virtuemart_service_class_id=service_class.virtuemart_service_class_id')
            ->where('tour_price.virtuemart_product_id='.(int)$virtuemart_product_id)
            ->leftJoin('#__virtuemart_itinerary AS itinerary ON itinerary.virtuemart_product_id=tour_price.virtuemart_product_id')
            ->select('count(distinct itinerary.virtuemart_itinerary_id) AS total_day ')
            ->leftJoin('#__virtuemart_cityarea AS cityarea ON cityarea.virtuemart_cityarea_id=itinerary.virtuemart_cityarea_id')
            ->leftJoin('#__virtuemart_states AS states ON states.virtuemart_state_id=cityarea.virtuemart_state_id')
            ->leftJoin('#__virtuemart_countries AS countries ON countries.virtuemart_country_id=states.virtuemart_country_id')
            ->select('GROUP_CONCAT(
                    CONCAT(states.state_name,",",countries.country_name) SEPARATOR ";"
            ) AS list_destination')
            ->select('group_size_id_tour_price_id.*')
            ->select('tour_price.tax')
            ->group('service_class.virtuemart_service_class_id')
            ->order('service_class.ordering')

            //->where('')

		;
        if ($start_date = $this->getState('filter.start_date'))
        {
            $start_date=JFactory::getDate($start_date);
            $query->leftJoin('#__virtuemart_date_availability AS date_availability ON date_availability.virtuemart_service_class_id=tour_price.virtuemart_service_class_id AND date_availability.virtuemart_product_id=tour_price.virtuemart_product_id');
            $query->select('case when date_availability.date= '.$query->quote($start_date->toSql()).' OR CURDATE()='.$query->quote($start_date->toSql()).' then 0 else 1 END AS tour_state');



        }
        $query2=$db->getQuery(true);
        $query2->select('MIN(group_size_id_tour_price_id2.price_adult)')
            ->from('#__virtuemart_group_size_id_tour_price_id AS group_size_id_tour_price_id2')
            ->leftJoin('#__virtuemart_tour_price AS tour_price2 ON tour_price2.virtuemart_price_id=group_size_id_tour_price_id2.virtuemart_price_id')
            ->where('(tour_price2.virtuemart_product_id ='.(int)$virtuemart_product_id.' AND tour_price2.virtuemart_service_class_id=service_class.virtuemart_service_class_id)')
        ;
        if ($start_date = $this->getState('filter.start_date'))
        {
            $start_date=JFactory::getDate($start_date);

            $query->where('(tour_price.sale_period_from<='.$query->quote($start_date->toSql()) .' AND tour_price.sale_period_to>='.$query->quote($start_date->toSql()) .')');
            $query2->where('(tour_price2.sale_period_from<='.$query->quote($start_date->toSql()) .' AND tour_price2.sale_period_to>='.$query->quote($start_date->toSql()) .')');


        }
        if ($total_passenger_from_12_years_old = $this->getState('filter.total_passenger_from_12_years_old'))
        {
            $query2->leftJoin('#__virtuemart_group_size AS group_size ON group_size.virtuemart_group_size_id=group_size_id_tour_price_id2.virtuemart_group_size_id')
            ->where('(group_size.from<='.(int)$total_passenger_from_12_years_old.' AND group_size.to>='.(int)$total_passenger_from_12_years_old.')')
            ;

        }

        $query->innerJoin('#__virtuemart_group_size_id_tour_price_id AS group_size_id_tour_price_id ON group_size_id_tour_price_id.price_adult=('.$query2.')')
        ;
        $query->innerJoin('#__virtuemart_mark_up_tour_price_id AS mark_up_tour_price_id ON mark_up_tour_price_id.virtuemart_price_id=tour_price.virtuemart_price_id')
            ->select('
            mark_up_tour_price_id.price_senior AS mark_up_price_senior,
            mark_up_tour_price_id.price_adult AS mark_up_price_adult,
            mark_up_tour_price_id.price_teen AS mark_up_price_teen,
            mark_up_tour_price_id.price_infant AS mark_up_price_infant,
            mark_up_tour_price_id.price_children1 AS mark_up_price_children1,
            mark_up_tour_price_id.price_children2 AS mark_up_price_children2,
            mark_up_tour_price_id.price_private_room AS mark_up_price_private_room,
            mark_up_tour_price_id.price_extra_bed AS mark_up_price_extra_bed,
            mark_up_tour_price_id.senior AS mark_up_senior,
            mark_up_tour_price_id.adult AS mark_up_adult,
            mark_up_tour_price_id.teen AS mark_up_teen,
            mark_up_tour_price_id.children2 AS mark_up_children2,
            mark_up_tour_price_id.price_teen AS mark_up_teen,
            mark_up_tour_price_id.infant AS mark_up_infant,
            mark_up_tour_price_id.private_room AS mark_up_private_room,
            mark_up_tour_price_id.extra_bed AS mark_up_extra_bed,
            mark_up_tour_price_id.type AS mark_up_type
            ')
        ;
        //echo $query->dump();
		return $query;
	}




}
// pure php no closing tag