<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists('VmModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package    VirtueMart
 * @subpackage Currency
 */
class VirtueMartModelDeparture extends VmModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('departure');
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getdeparture($departure_id = 0)
    {
        return $this->getData($departure_id);
    }


    /**
     * Retireve a list of currencies from the database.
     * This function is used in the backend for the currency listing, therefore no asking if enabled or not
     * @author Max Milbers
     * @return object List of currency objects
     */

    function getItemList($search='') {
        echo $this->getListQuery()->dump();
        $data=parent::getItems();
        return $data;
    }

    function getListQuery()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $virtuemart_product_id=$input->getInt('virtuemart_product_id',0);
        $db = JFactory::getDbo();
        $query=$db->getQuery(true);

        $query->select('departure.*,products_en_gb.product_name,service_class.service_class_name')
            ->from('#__virtuemart_departure AS departure')
            ->leftJoin('#__virtuemart_products AS products USING(virtuemart_product_id)')
            ->innerJoin('#__virtuemart_products_en_gb AS products_en_gb ON products_en_gb.virtuemart_product_id=products.virtuemart_product_id')
            ->leftJoin('#__virtuemart_service_class AS service_class USING(virtuemart_service_class_id)')
            ->where('departure.virtuemart_departure_parent_id IS NOT NULL')
        ;
        if($virtuemart_product_id)
        {
            $query->where('departure.virtuemart_product_id='.(int)$virtuemart_product_id);
        }
        $user = JFactory::getUser();
        $shared = '';
        if (vmAccess::manager()) {
            //$query->where('transferaddon.shared=1','OR');
        }
        $search=vRequest::getCmd('search', false);
        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $query->where('departure.departure_name LIKE '.$search);
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'departure.virtuemart_departure_id');
        $orderDirn = $this->state->get('list.direction', 'asc');

        if ($orderCol == 'airport.ordering')
        {
            $orderCol = $db->quoteName('departure.virtuemart_departure_id') . ' ' . $orderDirn . ', ' . $db->quoteName('itinerary.ordering');
        }

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }



    public function save_departure_item($data)
    {
        $virtuemart_product_id=$data['tour_id'];
        $departure_name=$data['departure_name'];
        $tour_service_class_id=$data['tour_service_class_id'];
        if($departure_name=='')
        {
            vmError('please set departure name');
            return false;
        }
        $list_basic_available2=$this->get_departure_item($data);
        if(count($list_basic_available2)==0)
        {
            vmError('there are no departure available');
            return false;
        }
        $list_date_available=array();
        if(count($list_basic_available2))
        {
            foreach($list_basic_available2 as $item)
            {
                $list_date_available[]=$item->date_select;
            }
        }
        //lấy ra group_size_id từ bản ghi đầu tiên
        $virtuemart_group_size_id=reset($list_basic_available2)->virtuemart_group_size_id;
        $list_date_available='"'.implode('","',$list_date_available).'"';
        //check exists data
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_departure_id')
            ->from('#__virtuemart_departure')
            ->where('departure_date IN('.$list_date_available.')')
            ->where('virtuemart_product_id='.(int)$virtuemart_product_id)
            ->where('virtuemart_service_class_id='.(int)$tour_service_class_id)
            ->where('virtuemart_group_size_id='.(int)$virtuemart_group_size_id)
            ;
        $list_virtuemart_departure_id=$db->setQuery($query)->loadObjectList();
        if(count($list_virtuemart_departure_id)>1)
        {
            vmError('there are some departure exists');
            return false;
        }
        $virtuemart_departure_id=parent::store($data);
        $db=JFactory::getDbo();
        if(!$virtuemart_departure_id)
        {
            vmError('can not save departure '.$db->getErrorMsg());
            return false;
        }
        
        $query=$db->getQuery(true);
        $table_departure=VmTable::getInstance('Departure','Table');
        $table_departure->bind($data);

        $table_departure->store();
        $virtuemart_departure_id=$table_departure->virtuemart_departure_id;
        //inser to group size
        foreach($list_basic_available2 as $item)
        {
            $table_departure->virtuemart_departure_id=0;
            $table_departure->published=1;
            $table_departure->virtuemart_departure_id=$virtuemart_departure_id;
            $table_departure->virtuemart_product_id=$virtuemart_product_id;
            $table_departure->departure_date=JFactory::getDate($item->date_select)->toSql();
            $table_departure->virtuemart_service_class_id=$item->service_class_id;
            $table_departure->virtuemart_group_size_id=$item->virtuemart_group_size_id;
            $table_departure->senior_price=$item->senior_price;
            $table_departure->adult_price=$item->price_adult;
            $table_departure->teen_price=$item->price_teen;
            $table_departure->infant_price=$item->price_infant;
            $table_departure->children1_price=$item->price_children1;
            $table_departure->children2_price=$item->price_children2;
            $table_departure->private_room_price=$item->price_private_room;
            $table_departure->senior_departure_price=$item->departure_price->price_senior;
            $table_departure->adult_departure_price=$item->departure_price->price_adult;
            $table_departure->teen_departure_price=$item->departure_price->price_teen;
            $table_departure->infant_departure_price=$item->departure_price->price_infant;
            $table_departure->children1_departure_price=$item->departure_price->price_children1;
            $table_departure->children2_departure_price=$item->departure_price->price_children2;
            $table_departure->private_room_departure_price=$item->departure_price->price_private_room;
            $table_departure->store();
            $err = $db->getErrorMsg();
            if(!empty($err)){
                vmError('can not insert group size in this tour',$err);
            }
        }
        $table_departure->delete($virtuemart_departure_id);
        $err = $db->getErrorMsg();
        if(!empty($err)){
            vmError('can not insert group size in this tour',$err);
        }
        return $list_basic_available2;
    }
    public function get_departure_item($data=array())
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $weeklies=$data['weekly'];
        $a_list_date=$data['list_date'];
        $min_space=$data['min_space'];
        if($min_space==0)
        {
            vmError('please set min space ');
            return false;
        }
        $tour_id=$data['tour_id'];
        if($tour_id==0)
        {
            vmError('please set tour ');
            return false;
        }
        $virtuemart_service_class_id=$data['tour_service_class_id'];
        if($virtuemart_service_class_id==0)
        {
            vmError('please set tour class ');
            return false;
        }
        $vail_period_from=JFactory::getDate($data['vail_period_from']);
        $vail_period_to=JFactory::getDate( $data['vail_period_to']);
        $date_type=$data['date_type'];
        if($date_type=='weekly'&&count($weeklies)==0)
        {
            vmError('please select dates ');
            return false;
        }
        if($date_type=='day_select'&&count($a_list_date)==0)
        {
            vmError('please select dates ');
            return false;
        }

        // Start date
        $date = JFactory::getDate($vail_period_from);
        // End date
        $end_date = JFactory::getDate($vail_period_to);
        $list_date=array();
        while ($date->getTimestamp() <= $end_date->getTimestamp()) {
            $day_of_week = date( "w", $date->getTimestamp());

            if($date_type=='weekly' && count($weeklies))
            {
                if(in_array($day_of_week,$weeklies))
                {
                    $list_date[]= $date->format('Y-m-d');
                }
                $date->modify('+1 day');
                continue;
            }
            if($date_type=='day_select' && count($a_list_date))
            {
                if(in_array($date->format('Y-m-d'),$a_list_date))
                {
                    $list_date[]= $date->format('Y-m-d');
                }
                $date->modify('+1 day');
                continue;
            }
            if(!count($weeklies)&&!count($a_list_date))
            {
                $list_date[]= $date->format('Y-m-d');
                $date->modify('+1 day');
                continue;
            }

        }
        //lấy ra các departure theo điều kiện tour_id, tour class và group size
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);

        //get list group size by tour id
        $query->select('group_size.*')
            ->from('#__virtuemart_tour_id_group_size_id AS tour_id_group_size_id')
            ->where('tour_id_group_size_id.virtuemart_product_id='.(int)$tour_id)
            ->leftJoin('#__virtuemart_group_size AS group_size ON group_size.virtuemart_group_size_id=tour_id_group_size_id.virtuemart_group_size_id')
            ->order('group_size.from')
            ->where('group_size.from>='.(int)$min_space.' OR (group_size.from<='.(int)$min_space.' AND group_size.to>='.(int)$min_space.')')
            ;
        $group_size=$db->setQuery($query)->loadObject();
        if(!$group_size)
        {
            vmError('there no group size from '.(int)$min_space);
            return false;
        }

        $query->clear()
            ->select('group_size_id_tour_departure_price_id.*')
            ->from('#__virtuemart_group_size_id_tour_departure_price_id AS group_size_id_tour_departure_price_id')
            ->leftJoin('#__virtuemart_tour_departure_price AS departure_price
			ON departure_price.virtuemart_departure_price_id=group_size_id_tour_departure_price_id.virtuemart_tour_departure_price_id')
            ->select('departure_price.*')
            ->where('departure_price.virtuemart_product_id='.(int)$tour_id)
            ->where('departure_price.virtuemart_service_class_id='.(int)$virtuemart_service_class_id)
            ->where('group_size_id_tour_departure_price_id.virtuemart_group_size_id='.(int)$group_size->virtuemart_group_size_id)
        ;

        $list_departure_price=$db->setQuery($query)->loadObjectList();
        if(count($list_departure_price)==0)
        {
            vmWarn('there are no departure price ');
        }
        //lấy giá có departure lọc theo điều kiện thỏa mãn danh sách này
        $list_departure_available=array();
        foreach($list_date as $item_date)
        {
            $time_stamp_item_date=JFactory::getDate($item_date)->getTimestamp();
            foreach($list_departure_price as $item_departure_price_date)
            {
                $start_date=$item_departure_price_date->sale_period_from;
                $end_date=$item_departure_price_date->sale_period_to;
                $time_stamp_start_date=JFactory::getDate($start_date)->getTimestamp();
                $time_stamp_end_date=JFactory::getDate($end_date)->getTimestamp();
                if($time_stamp_item_date>=$time_stamp_start_date && $time_stamp_item_date<=$time_stamp_end_date)
                {
                    $item_departure_price_date1=clone $item_departure_price_date;
                    $item_departure_price_date1->date_select=$item_date;
                    $list_departure_available[]=$item_departure_price_date1;
                }
            }
        }
        //lấy ra giá có markup (giá trị departure) theo tour có net departure price
        $list_mark_up_tour_departure_net_price=array();
        $list_virtuemart_departure_price_id=array();
        foreach($list_departure_price as $item_departure_price)
        {
            if(!in_array($item_departure_price->virtuemart_departure_price_id,$list_virtuemart_departure_price_id))
            {
                $list_virtuemart_departure_price_id[]=$item_departure_price->virtuemart_departure_price_id;
            }
        }
        if(count($list_virtuemart_departure_price_id)>=1) {
            $query->clear()
                ->from('#__virtuemart_mark_up_tour_departure_net_price_id AS mark_up_tour_departure_net_price_id')
                ->select('mark_up_tour_departure_net_price_id.*')
                ->where('mark_up_tour_departure_net_price_id.virtuemart_tour_departure_price_id IN ('.implode(',', $list_virtuemart_departure_price_id).')')
            ;

            $list_mark_up_tour_departure_net_price=$db->setQuery($query)->loadObjectList();
        }
        $list_mark_up_tour_departure_net_price2=array();
        foreach($list_mark_up_tour_departure_net_price as $mark_up_tour_departure_net_price)
        {
            $virtuemart_tour_departure_price_id=$mark_up_tour_departure_net_price->virtuemart_tour_departure_price_id;
            $list_mark_up_tour_departure_net_price2[$virtuemart_tour_departure_price_id][$mark_up_tour_departure_net_price->type]=$mark_up_tour_departure_net_price;
        }
        if(count($list_departure_available)==0)
        {
            vmWarn('there are no departure price ');
        }
        //tính giá lãi có departure
        //1.tính giá thực sau khi departure
        foreach($list_departure_available as $key=> $departure_available)
        {
            $virtuemart_departure_price_id=$departure_available->virtuemart_departure_price_id;

            $percent_price=$list_mark_up_tour_departure_net_price2[$virtuemart_departure_price_id]['percent'];
            if(
                $percent_price->senior!=0
                ||$percent_price->adult!=0
                || $percent_price->teen!=0
                || $percent_price->infant!=0
                || $percent_price->children1!=0
                || $percent_price->children2!=0
                || $percent_price->private_room!=0)
            {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior=$list_departure_available[$key]->price_senior;
                $price_senior=$price_senior-($price_senior*$percent_price->senior)/100;
                $list_departure_available[$key]->price_senior=$price_senior;

                $price_adult=$list_departure_available[$key]->price_adult;
                $price_adult=$price_adult-($price_adult*$percent_price->adult)/100;
                $list_departure_available[$key]->price_adult=$price_adult;

                $price_teen=$list_departure_available[$key]->price_teen;
                $price_teen=$price_teen-($price_teen*$percent_price->teen)/100;
                $list_departure_available[$key]->price_teen=$price_teen;

                $price_infant=$list_departure_available[$key]->price_infant;
                $price_infant=$price_infant-($price_infant*$percent_price->infant)/100;
                $list_departure_available[$key]->price_infant=$price_infant;

                $price_children1=$list_departure_available[$key]->price_children1;
                $price_children1=$price_children1-($price_children1*$percent_price->children1)/100;
                $list_departure_available[$key]->price_children1=$price_children1;

                $price_children2=$list_departure_available[$key]->price_children2;
                $price_children2=$price_children2-($price_children2*$percent_price->children2)/100;
                $list_departure_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_departure_available[$key]->price_private_room;
                $price_private_room=$price_private_room-($price_private_room*$percent_price->private_room)/100;
                $list_departure_available[$key]->price_private_room=$price_private_room;


            }else{

                $amount_price=$list_mark_up_tour_departure_net_price2[$virtuemart_departure_price_id]['amount'];

                $price_senior=$list_departure_available[$key]->price_senior;
                $price_senior=$price_senior-$amount_price->senior;
                $list_departure_available[$key]->price_senior=$price_senior;

                $price_adult=$list_departure_available[$key]->price_adult;
                $price_adult=$price_adult-$amount_price->adult;
                $list_departure_available[$key]->price_adult=$price_adult;

                $price_teen=$list_departure_available[$key]->price_teen;
                $price_teen=$price_teen-$amount_price->teen;
                $list_departure_available[$key]->price_teen=$price_teen;

                $price_infant=$list_departure_available[$key]->price_infant;
                $price_infant=$price_infant-$amount_price->infant;
                $list_departure_available[$key]->price_infant=$price_infant;

                $price_children1=$list_departure_available[$key]->price_children1;
                $price_children1=$price_children1-$amount_price->children1;
                $list_departure_available[$key]->price_children1=$price_children1;

                $price_children2=$list_departure_available[$key]->price_children2;
                $price_children2=$price_children2-$amount_price->children2;
                $list_departure_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_departure_available[$key]->price_private_room;
                $price_private_room=$price_private_room-$amount_price->private_room;
                $list_departure_available[$key]->price_private_room=$price_private_room;
            }



        }

        if(count($list_virtuemart_departure_price_id)==0)
        {
            vmWarn('there are no departure price ');
        }

        //lấy ra các giá markup (giá trị có lãi) theo tour có net departure price
        $list_mark_up_tour_departure_price=array();
        if(count($list_virtuemart_departure_price_id)>=1) {
            $query->clear()
                ->from('#__virtuemart_mark_up_tour_departure_price_id AS mark_up_tour_departure_price_id')
                ->select('mark_up_tour_departure_price_id.*')
                ->where('mark_up_tour_departure_price_id.virtuemart_tour_departure_price_id IN ('.implode(',', $list_virtuemart_departure_price_id).')')
            ;
            $list_mark_up_tour_departure_price=$db->setQuery($query)->loadObjectList();
        }

        $list_mark_up_tour_departure_price2=array();
        foreach($list_mark_up_tour_departure_price as $mark_up_tour_departure_price)
        {
            $virtuemart_tour_departure_price_id=$mark_up_tour_departure_price->virtuemart_tour_departure_price_id;
            $list_mark_up_tour_departure_price2[$virtuemart_tour_departure_price_id][$mark_up_tour_departure_price->type]=$mark_up_tour_departure_price;
        }
        //2.tính giá thực sau khi khi có markup (có phần lãi)

        foreach($list_departure_available as $key=> $departure_available)
        {
            $virtuemart_departure_price_id=$departure_available->virtuemart_departure_price_id;

            $percent_price=$list_mark_up_tour_departure_price2[$virtuemart_departure_price_id]['percent'];
            if(
                $percent_price->senior!=0
                ||$percent_price->adult!=0
                || $percent_price->teen!=0
                || $percent_price->infant!=0
                || $percent_price->children1!=0
                || $percent_price->children2!=0
                || $percent_price->private_room!=0)
            {
                //giá  tính theo percent thì không tính theo amount n?a
                $price_senior=$list_departure_available[$key]->price_senior;
                $price_senior=$price_senior+($price_senior*$percent_price->senior)/100;
                $list_departure_available[$key]->price_senior=$price_senior;

                $price_adult=$list_departure_available[$key]->price_adult;
                $price_adult=$price_adult+($price_adult*$percent_price->adult)/100;
                $list_departure_available[$key]->price_adult=$price_adult;

                $price_teen=$list_departure_available[$key]->price_teen;
                $price_teen=$price_teen+($price_teen*$percent_price->teen)/100;
                $list_departure_available[$key]->price_teen=$price_teen;

                $price_infant=$list_departure_available[$key]->price_infant;
                $price_infant=$price_infant+($price_infant*$percent_price->infant)/100;
                $list_departure_available[$key]->price_infant=$price_infant;

                $price_children1=$list_departure_available[$key]->price_children1;
                $price_children1=$price_children1+($price_children1*$percent_price->children1)/100;
                $list_departure_available[$key]->price_children1=$price_children1;

                $price_children2=$list_departure_available[$key]->price_children2;
                $price_children2=$price_children2+($price_children2*$percent_price->children2)/100;
                $list_departure_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_departure_available[$key]->price_private_room;
                $price_private_room=$price_private_room+($price_private_room*$percent_price->private_room)/100;
                $list_departure_available[$key]->price_private_room=$price_private_room;


            }else{

                $amount_price=$list_mark_up_tour_departure_price2[$virtuemart_departure_price_id]['amount'];

                $price_senior=$list_departure_available[$key]->price_senior;
                $price_senior=$price_senior+$amount_price->senior;
                $list_departure_available[$key]->price_senior=$price_senior;

                $price_adult=$list_departure_available[$key]->price_adult;
                $price_adult=$price_adult+$amount_price->adult;
                $list_departure_available[$key]->price_adult=$price_adult;

                $price_teen=$list_departure_available[$key]->price_teen;
                $price_teen=$price_teen+$amount_price->teen;
                $list_departure_available[$key]->price_teen=$price_teen;

                $price_infant=$list_departure_available[$key]->price_infant;
                $price_infant=$price_infant+$amount_price->infant;
                $list_departure_available[$key]->price_infant=$price_infant;

                $price_children1=$list_departure_available[$key]->price_children1;
                $price_children1=$price_children1+$amount_price->children1;
                $list_departure_available[$key]->price_children1=$price_children1;

                $price_children2=$list_departure_available[$key]->price_children2;
                $price_children2=$price_children2+$amount_price->children2;
                $list_departure_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_departure_available[$key]->price_private_room;
                $price_private_room=$price_private_room+$amount_price->private_room;
                $list_departure_available[$key]->price_private_room=$price_private_room;
            }



        }
        $list_departure_available2=array();
        foreach($list_departure_available as $departure_available)
        {
            $list_departure_available2[$departure_available->virtuemart_product_id.'-'.$departure_available->service_class_id.'-'.$departure_available->virtuemart_group_size_id.'-'.$departure_available->date_select]=$departure_available;
        }
        //tính giá bán sau thuế
        foreach($list_departure_available as $key=> $departure_available)
        {
            $price_senior=$list_departure_available[$key]->price_senior;
            $price_senior=$price_senior+($price_senior*$departure_available->tax)/100;
            $list_departure_available[$key]->price_senior=$price_senior;

            $price_adult=$list_departure_available[$key]->price_adult;
            $price_adult=$price_adult+($price_adult*$departure_available->tax)/100;
            $list_departure_available[$key]->price_adult=$price_adult;

            $price_teen=$list_departure_available[$key]->price_teen;
            $price_teen=$price_teen+($price_teen*$departure_available->tax)/100;
            $list_departure_available[$key]->price_teen=$price_teen;

            $price_infant=$list_departure_available[$key]->price_infant;
            $price_infant=$price_infant+($price_infant*$departure_available->tax)/100;
            $list_departure_available[$key]->price_infant=$price_infant;

            $price_children1=$list_departure_available[$key]->price_children1;
            $price_children1=$price_children1+($price_children1*$departure_available->tax)/100;
            $list_departure_available[$key]->price_children1=$price_children1;

            $price_children2=$list_departure_available[$key]->price_children2;
            $price_children2=$price_children2+($price_children2*$departure_available->tax)/100;
            $list_departure_available[$key]->price_children2=$price_children2;

            $price_private_room=$list_departure_available[$key]->price_private_room;
            $price_private_room=$price_private_room+($price_private_room*$departure_available->tax)/100;
            $list_departure_available[$key]->price_private_room=$price_private_room;
        }
        //sử lý với giá basic

        $query->clear()
            ->select('group_size_id_tour_price_id.*')
            ->from('#__virtuemart_group_size_id_tour_price_id AS group_size_id_tour_price_id')
            ->leftJoin('#__virtuemart_tour_price AS tour_price
			ON tour_price.virtuemart_price_id=group_size_id_tour_price_id.virtuemart_price_id')
            ->select('tour_price.*')
            ->where('tour_price.virtuemart_product_id='.(int)$tour_id)
            ->where('tour_price.virtuemart_service_class_id='.(int)$virtuemart_service_class_id)
            ->where('group_size_id_tour_price_id.virtuemart_group_size_id='.(int)$group_size->virtuemart_group_size_id)
        ;



        $list_price=$db->setQuery($query)->loadObjectList();
        if(count($list_departure_available2)==0&&count($list_price)==0)
        {
            vmError('there are no base price  and departure price');
            return false;
        }
        // lấy giá có departure lọc theo điều kiên thỏa mãn danh sách ngày
        $list_price_available=array();
        foreach($list_date as $item_date)
        {
            $time_stamp_item_date=JFactory::getDate($item_date)->getTimestamp();
            foreach($list_price as $item_price_date)
            {
                $start_date=$item_price_date->sale_period_from;
                $end_date=$item_price_date->sale_period_to;
                $time_stamp_start_date=JFactory::getDate($start_date)->getTimestamp();
                $time_stamp_end_date=JFactory::getDate($end_date)->getTimestamp();
                if($time_stamp_item_date>=$time_stamp_start_date && $time_stamp_item_date<=$time_stamp_end_date)
                {
                    $item_price_date1=clone $item_price_date;
                    $item_price_date1->date_select=$item_date;
                    $list_price_available[]=$item_price_date1;
                }
            }
        }


        //lấy ra các giá markup (giá trị có lãi) theo tour có net  price


        $list_virtuemart_price_id=array();
        foreach($list_price as $item_price)
        {
            if(!in_array($item_price->virtuemart_price_id,$list_virtuemart_price_id))
            {
                $list_virtuemart_price_id[]=$item_price->virtuemart_price_id;
            }
        }


        $list_mark_up_tour_price=array();
        if(count($list_virtuemart_price_id)>=1) {
            $query->clear()
                ->from('#__virtuemart_mark_up_tour_price_id AS mark_up_tour_price_id')
                ->select('mark_up_tour_price_id.*')
                ->where('mark_up_tour_price_id.virtuemart_price_id IN ('.implode(',', $list_virtuemart_price_id).')')
            ;
            $list_mark_up_tour_price=$db->setQuery($query)->loadObjectList();
        }

        $list_mark_up_tour_price2=array();
        foreach($list_mark_up_tour_price as $mark_up_tour_price)
        {
            $virtuemart_price_id=$mark_up_tour_price->virtuemart_price_id;
            $list_mark_up_tour_price2[$virtuemart_price_id][$mark_up_tour_price->type]=$mark_up_tour_price;
        }
        //2.tính giá thực sau khi khi có markup basic (có phần lãi)

        foreach($list_price_available as $key=> $price_available)
        {
            $virtuemart_price_id=$price_available->virtuemart_price_id;

            $percent_price=$list_mark_up_tour_price2[$virtuemart_price_id]['percent'];
            if(
                $percent_price->senior!=0
                ||$percent_price->adult!=0
                || $percent_price->teen!=0
                || $percent_price->infant!=0
                || $percent_price->children1!=0
                || $percent_price->children2!=0
                || $percent_price->private_room!=0)
            {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior=$list_price_available[$key]->price_senior;
                $price_senior=$price_senior+($price_senior*$percent_price->senior)/100;
                $list_price_available[$key]->price_senior=$price_senior;

                $price_adult=$list_price_available[$key]->price_adult;
                $price_adult=$price_adult+($price_adult*$percent_price->adult)/100;
                $list_price_available[$key]->price_adult=$price_adult;

                $price_teen=$list_price_available[$key]->price_teen;
                $price_teen=$price_teen+($price_teen*$percent_price->teen)/100;
                $list_price_available[$key]->price_teen=$price_teen;

                $price_infant=$list_price_available[$key]->price_infant;
                $price_infant=$price_infant+($price_infant*$percent_price->infant)/100;
                $list_price_available[$key]->price_infant=$price_infant;

                $price_children1=$list_price_available[$key]->price_children1;
                $price_children1=$price_children1+($price_children1*$percent_price->children1)/100;
                $list_price_available[$key]->price_children1=$price_children1;

                $price_children2=$list_price_available[$key]->price_children2;
                $price_children2=$price_children2+($price_children2*$percent_price->children2)/100;
                $list_price_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_price_available[$key]->price_private_room;
                $price_private_room=$price_private_room+($price_private_room*$percent_price->private_room)/100;
                $list_price_available[$key]->price_private_room=$price_private_room;


            }else{

                $amount_price=$list_mark_up_tour_price2[$virtuemart_price_id]['amount'];

                $price_senior=$list_price_available[$key]->price_senior;
                $price_senior=$price_senior+$amount_price->senior;
                $list_price_available[$key]->price_senior=$price_senior;

                $price_adult=$list_price_available[$key]->price_adult;
                $price_adult=$price_adult+$amount_price->adult;
                $list_price_available[$key]->price_adult=$price_adult;

                $price_teen=$list_price_available[$key]->price_teen;
                $price_teen=$price_teen+$amount_price->teen;
                $list_price_available[$key]->price_teen=$price_teen;

                $price_infant=$list_price_available[$key]->price_infant;
                $price_infant=$price_infant+$amount_price->infant;
                $list_price_available[$key]->price_infant=$price_infant;

                $price_children1=$list_price_available[$key]->price_children1;
                $price_children1=$price_children1+$amount_price->children1;
                $list_price_available[$key]->price_children1=$price_children1;

                $price_children2=$list_price_available[$key]->price_children2;
                $price_children2=$price_children2+$amount_price->children2;
                $list_price_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_price_available[$key]->price_private_room;
                $price_private_room=$price_private_room+$amount_price->private_room;
                $list_price_available[$key]->price_private_room=$price_private_room;
            }



        }




        $list_price_available2=array();
        foreach($list_price_available as $price_available)
        {
            $list_price_available2[$price_available->virtuemart_product_id.'-'.$price_available->service_class_id.'-'.$price_available->virtuemart_group_size_id.'-'.$price_available->date_select]=$price_available;
        }
        //tính giá bán sau thuế
        foreach($list_price_available as $key=> $price_available)
        {
            $price_senior=$list_price_available[$key]->price_senior;
            $price_senior=$price_senior+($price_senior*$price_available->tax)/100;
            $list_price_available[$key]->price_senior=$price_senior;

            $price_adult=$list_price_available[$key]->price_adult;
            $price_adult=$price_adult+($price_adult*$price_available->tax)/100;
            $list_price_available[$key]->price_adult=$price_adult;

            $price_teen=$list_price_available[$key]->price_teen;
            $price_teen=$price_teen+($price_teen*$price_available->tax)/100;
            $list_price_available[$key]->price_teen=$price_teen;

            $price_infant=$list_price_available[$key]->price_infant;
            $price_infant=$price_infant+($price_infant*$price_available->tax)/100;
            $list_price_available[$key]->price_infant=$price_infant;

            $price_children1=$list_price_available[$key]->price_children1;
            $price_children1=$price_children1+($price_children1*$price_available->tax)/100;
            $list_price_available[$key]->price_children1=$price_children1;

            $price_children2=$list_price_available[$key]->price_children2;
            $price_children2=$price_children2+($price_children2*$price_available->tax)/100;
            $list_price_available[$key]->price_children2=$price_children2;

            $price_private_room=$list_price_available[$key]->price_private_room;
            $price_private_room=$price_private_room+($price_private_room*$price_available->tax)/100;
            $list_price_available[$key]->price_private_room=$price_private_room;
        }

        if(count($list_price_available2)==0)
        {
            vmWarn('there are no base price');
        }

        foreach($list_departure_available2 as $key=>$value)
        {
            if(!is_object($list_price_available2[$key]))
            {
                $list_price_available2[$key]=new stdClass();
                $list_price_available2[$key]->departure_price=$value;
                $list_price_available2[$key]->date_select=$value->date_select;
            }else{
                $list_price_available2[$key]->departure_price=$value;
            }
        }
        if(count($list_price_available2)==0)
        {
            vmWarn('there are no departure availble');
            return false;
        }
        return $list_price_available2;
    }
    function getVendorAcceptedCurrrenciesList($vendorId = 0)
    {

        static $currencies = array();
        if ($vendorId === 0) {
            $multix = Vmconfig::get('multix', 'none');
            if (strpos($multix, 'payment') !== FALSE) {
                if (!class_exists('VirtueMartModelVendor'))
                    require(VMPATH_ADMIN . DS . 'models' . DS . 'vendor.php');
                $vendorId = VirtueMartModelVendor::getLoggedVendor();

            } else {
                $vendorId = 1;
            }
        }
        if (!isset($currencies[$vendorId])) {
            $db = JFactory::getDbo();
            $q = 'SELECT `vendor_accepted_currencies`, `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id`=' . $vendorId;
            $db->setQuery($q);
            $vendor_currency = $db->loadAssoc();
            if (!$vendor_currency['vendor_accepted_currencies']) {
                $vendor_currency['vendor_accepted_currencies'] = $vendor_currency['vendor_currency'];
                vmWarn('No accepted currencies defined');
                if (empty($vendor_currency['vendor_accepted_currencies'])) {
                    $uri = JFactory::getURI();
                    $link = $uri->root() . 'administrator/index.php?option=com_virtuemart&view=user&task=editshop';
                    vmWarn(vmText::sprintf('COM_VIRTUEMART_CONF_WARN_NO_CURRENCY_DEFINED', '<a href="' . $link . '">' . $link . '</a>'));
                    $currencies[$vendorId] = false;
                    return $currencies[$vendorId];
                }
            }
            $q = 'SELECT `virtuemart_currency_id`,CONCAT_WS(" ",`currency_name`,`currency_symbol`) as currency_txt
					FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id` IN (' . $vendor_currency['vendor_accepted_currencies'] . ')';
            if ($vendorId != 1) {
                $q .= ' AND (`virtuemart_vendor_id` = "' . $vendorId . '" OR `shared`="1")';
            }
            $q .= '	AND published = "1"
					ORDER BY `ordering`,`currency_name`';

            $db->setQuery($q);
            $currencies[$vendorId] = $db->loadObjectList();
        }

        return $currencies[$vendorId];
    }

    /**
     * Retireve a list of currencies from the database.
     *
     * This is written to get a list for selecting currencies. Therefore it asks for enabled
     * @author Max Milbers
     * @return object List of currency objects
     */
    function getCurrencies($vendorId = 1)
    {
        $db = JFactory::getDBO();
        $q = 'SELECT * FROM `#__virtuemart_currencies` WHERE (`virtuemart_vendor_id` = "' . (int)$vendorId . '" OR `shared`="1") AND published = "1" ORDER BY `ordering`,`#__virtuemart_currencies`.`currency_name`';
        $db->setQuery($q);
        return $db->loadObjectList();
    }
    public function create_children_departure($virtuemart_departure_id)
    {
        $query=$this->_db->getQuery(true);
        $query->delete('#__virtuemart_departure')
            ->where('virtuemart_departure_parent_id='.(int)$virtuemart_departure_id);
        $this->_db->setQuery($query);
        $ok = $this->_db->execute();
        if (!$ok) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $table_departure=$this->getTable();
        $table_departure->load($virtuemart_departure_id);
        $date_type=$table_departure->date_type;
        if($date_type=='day_select')
        {
            $days_seleted=explode(',',$table_departure->days_seleted);
        }else{
            $sale_period_from=$table_departure->sale_period_from;
            $sale_period_to=$table_departure->sale_period_to;
            $days_seleted=JUtility::dateRange($sale_period_from,$sale_period_to);
            $weekly=$table_departure->weekly;
            $weekly=explode(',',$weekly);
            foreach($days_seleted as $key=> $day)
            {
                $day_of_week=strtolower(date('D', strtotime( $day)));
                if(!in_array($day_of_week,$weekly))
                {
                    unset($days_seleted[$key]);
                }
            }
        }
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmdeparture.php';
        foreach($days_seleted as $day) {
            $table_departure->departure_date = $day;
            $day=JFactory::getDate($day);
            $table_departure->virtuemart_departure_id = 0;
            $table_departure->departure_code =vmDeparture::get_format_departure_code($virtuemart_departure_id,$day);
            $table_departure->virtuemart_departure_parent_id = $virtuemart_departure_id;
            $ok = $table_departure->store();
            if (!$ok) {
                $this->setError($table_departure->getErrors());
                return false;
            }
        }
        return true;

    }
    function store(&$data)
    {
        if (!vmAccess::manager('currency')) {
            vmWarn('Insufficient permissions to store currency');
            return false;
        }
        return parent::store($data);
    }

    function remove($ids)
    {
        if (!vmAccess::manager('currency')) {
            vmWarn('Insufficient permissions to remove currency');
            return false;
        }
        return parent::remove($ids);
    }

}
// pure php no closing tag