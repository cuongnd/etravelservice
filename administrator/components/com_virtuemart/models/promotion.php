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
class VirtueMartModelPromotion extends VmModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('promotion');
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function get_promotion_price($price_id = 0)
    {
        $item= $this->getData($price_id);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tour_class.*')
            ->from('#__virtuemart_tour_service_class AS tour_class')
            ->where('tour_class.virtuemart_service_class_id='.(int)$item->service_class_id)
            ;
        $item->service_class=$db->setQuery($query)->loadObject();
        return $item;
    }


    /**
     * Retireve a list of currencies from the database.
     * This function is used in the backend for the currency listing, therefore no asking if enabled or not
     * @author Max Milbers
     * @return object List of currency objects
     */
    function getPricesList($search)
    {

        $where = array();

        $user = JFactory::getUser();

        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $where[] = '`title` LIKE ' . $search;
        }

        $whereString = '';
        if (count($where) > 0) $whereString = ' WHERE ' . implode(' AND ', $where);

        $data = $this->exeSortSearchListQuery(0, '*', ' FROM `#__virtuemart_tour_price`', $whereString, '', $this->_getOrdering());

        return $data;
    }
    function getPricesListByTourid($tour_id)
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true)
            ->select('tour_price.*')
            ->from('#__virtuemart_tour_promotion_price AS tour_promotion_price')
            ->leftJoin('#__virtuemart_tour_service_class AS tour_service_class ON tour_service_class.virtuemart_service_class_id=tour_promotion_price.service_class_id')
            ->select('tour_service_class.service_class_name')
            ->where('tour_promotion_price.virtuemart_product_id='.(int)$tour_id)
            ->leftJoin('#__virtuemart_products AS products ON products.virtuemart_product_id=tour_price.virtuemart_product_id')
            ->select('products.tour_methor')
            ;
        return $db->setQuery($query)->loadObjectList();

    }
    function getPromotionPricesList($tour_id=0)
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true)
            ->select('tour_promotion_price.*,language_products.product_name AS tour_name')
            ->from('#__virtuemart_tour_promotion_price AS tour_promotion_price')
            ->leftJoin('#__virtuemart_tour_service_class AS tour_service_class ON tour_service_class.virtuemart_service_class_id=tour_promotion_price.service_class_id')
            ->select('tour_service_class.service_class_name')
            ->leftJoin('#__virtuemart_products AS products ON products.virtuemart_product_id=tour_promotion_price.virtuemart_product_id')
            ->leftJoin('#__virtuemart_products_'.VmConfig::$vmlang.' AS language_products ON language_products.virtuemart_product_id=products.virtuemart_product_id')
            ->select('products.tour_methor')
            ->where('products.virtuemart_product_id='.(int)$tour_id)
            ;
        return $db->setQuery($query)->loadObjectList();

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

    function store(&$data)
    {
        $virtuemart_promotion_price_id = parent::store($data);
        if ($virtuemart_promotion_price_id && $data['tour_methor'] == 'tour_group') {
            $tour_promotion_price_by_tour_promotion_price_id = $data['tour_promotion_price_by_tour_promotion_price_id'];
            $this->save_promotion_price_group_size_by_promotion_price_id($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_promotion_price_id);
        } else {
            $tour_promotion_price_by_tour_promotion_price_id = $data['tour_price_by_tour_promotion_price_id'];
            $this->save_promotion_price_group_size_by_promotion_price_id_tour_private($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_promotion_price_id);
        }
        $amount = $data['amount']->markup_promotion_price;
        $percent = $data['percent']->markup_promotion_price;
        $this->save_markup_promotion_price($amount,$percent, $virtuemart_promotion_price_id);

        $amount = $data['amount']->net_markup_promotion_price;
        $percent = $data['percent']->net_markup_promotion_price;
        $this->save_net_markup_promotion_price($amount,$percent, $virtuemart_promotion_price_id);
        return $virtuemart_promotion_price_id;
    }

    public function save_promotion_price_group_size_by_promotion_price_id($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_group_size_id_tour_promotion_price_id')
            ->where('virtuemart_tour_promotion_price_id=' . (int)$virtuemart_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }

        if (count($tour_promotion_price_by_tour_promotion_price_id)) {
            foreach ($tour_promotion_price_by_tour_promotion_price_id as $group_size_id => $items) {
                if(!is_object($items))
                    continue;
                $query = $db->getQuery(true);
                $query->insert('#__virtuemart_group_size_id_tour_promotion_price_id');
                foreach ($items as $key => $value) {
                    $query->set("$key=".(int)$value);
                }
                $query->set("virtuemart_tour_promotion_price_id=$virtuemart_price_id")
                    ->set("virtuemart_group_size_id=$group_size_id");
                if(!$db->setQuery($query)->execute())
                {
                    throw new Exception(500, $db->getErrorMsg());
                }
            }
        }
    }
    public function save_markup_promotion_price($amount,$percent, $virtuemart_price_id)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_mark_up_tour_promotion_price_id')
            ->where('virtuemart_tour_promotion_price_id=' . (int)$virtuemart_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }

        if (count($amount)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_price_id');
            foreach ($amount as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_tour_promotion_price_id=$virtuemart_price_id")
                ->set('type='.$query->q('amount'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }
        }
        if (count($percent)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_price_id');
            foreach ($percent as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_tour_promotion_price_id=$virtuemart_price_id")
                ->set('type='.$query->q('percent'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }
        }
    }
    public function save_net_markup_promotion_price($amount,$percent, $virtuemart_price_id)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_mark_up_tour_promotion_net_price_id')
            ->where('virtuemart_tour_promotion_price_id=' . (int)$virtuemart_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }

        if (count($amount)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_net_price_id');
            foreach ($amount as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_tour_promotion_price_id=$virtuemart_price_id")
                ->set('type='.$query->q('amount'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }

        }
        if (count($percent)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_net_price_id');
            foreach ($percent as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_tour_promotion_price_id=$virtuemart_price_id")
                ->set('type='.$query->q('percent'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }
        }
    }

    public function save_promotion_price_group_size_by_promotion_price_id_tour_private($data, $virtuemart_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_group_size_id_tour_promotion_price_id')
            ->where('virtuemart_tour_promotion_price_id=' . (int)$virtuemart_price_id);

        $db->setQuery($query)->execute();
        $price_senior=(float)$data->price_senior;
        $price_adult=(float)$data->price_adult;
        $price_infant=(float)$data->price_infant;
        $price_teen=(float)$data->price_teen;
        $price_children1=(float)$data->price_children1;
        $price_children2=(float)$data->price_children2;
        $price_private_room=(float)$data->price_private_room;
        $query->insert('#__virtuemart_group_size_id_tour_promotion_price_id')
            ->set("virtuemart_tour_promotion_price_id=$virtuemart_price_id")
                ->set("price_senior=$price_senior")
                ->set("price_adult=$price_adult")
                ->set("price_teen=$price_teen")
                ->set("price_infant=$price_infant")
                ->set("price_children1=$price_children1")
                ->set("price_children2=$price_children2")
                ->set("price_private_room=$price_private_room")
                ->set("virtuemart_group_size_id=0");
            $db->setQuery($query)->execute();
    }


}
// pure php no closing tag