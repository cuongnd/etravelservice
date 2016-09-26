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

if (!class_exists('VmModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package    VirtueMart
 * @subpackage Currency
 */
class VirtueMartModelpromotion extends VmModel
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
    function get_promotion_price($virtuemart_promotion_price_id = 0)
    {
        $item= $this->getData($virtuemart_promotion_price_id);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tour_class.*')
            ->from('#__virtuemart_service_class AS tour_class')
            ->where('tour_class.virtuemart_service_class_id='.(int)$item->virtuemart_service_class_id)
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
            ->leftJoin('#__virtuemart_service_class AS tour_service_class ON tour_service_class.virtuemart_service_class_id=tour_promotion_price.virtuemart_service_class_id')
            ->select('tour_service_class.service_class_name')
            ->where('tour_promotion_price.virtuemart_product_id='.(int)$tour_id)
            ->order('tour_price.sale_period_from,tour_price.sale_period_to,tour_service_class.ordering')
            ->leftJoin('#__virtuemart_products AS products ON products.virtuemart_product_id=tour_price.virtuemart_product_id')
            ;
        return $db->setQuery($query)->loadObjectList();

    }
    function get_list_promotion_price($virtuemart_product_id=0)
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true)
            ->select('tour_promotion_price.*,language_products.product_name AS tour_name')
            ->from('#__virtuemart_tour_promotion_price AS tour_promotion_price')

            ->leftJoin('#__virtuemart_service_class AS tour_service_class ON tour_service_class.virtuemart_service_class_id=tour_promotion_price.virtuemart_service_class_id')
            ->select('tour_service_class.service_class_name')
            ->leftJoin('#__virtuemart_products AS products ON products.virtuemart_product_id=tour_promotion_price.virtuemart_product_id')
            ->innerJoin('#__virtuemart_tour_type AS tour_type ON tour_type.virtuemart_tour_type_id=products.virtuemart_tour_type_id')
            ->select('tour_type.title AS tour_type_name')
            ->leftJoin('#__virtuemart_products_'.VmConfig::$vmlang.' AS language_products ON language_products.virtuemart_product_id=products.virtuemart_product_id')
            ;
        if($virtuemart_product_id)
        {
            $query->where('products.virtuemart_product_id='.(int)$virtuemart_product_id);
        }
        echo $query->dump();
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
                    $link = $uri->root() . 'administrator/index.php?option=com_tsmart&view=user&task=editshop';
                    vmWarn(tsmText::sprintf('com_tsmart_CONF_WARN_NO_CURRENCY_DEFINED', '<a href="' . $link . '">' . $link . '</a>'));
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
        if($virtuemart_promotion_price_id) {
            $virtuemart_product_id = $data['virtuemart_product_id'];
            $model_product = $this->getModel('product');
            $product = $model_product->getItem($virtuemart_product_id);
            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
            if ($virtuemart_promotion_price_id && $product->price_type != tsmGroupSize::FLAT_PRICE) {

                $tour_promotion_price_by_tour_promotion_price_id = $data['tour_promotion_price_by_tour_promotion_price_id'];
                $this->save_promotion_price_group_size_by_promotion_price_id($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_promotion_price_id);
                $amount = $data['amount']->markup_promotion_price;
                $percent = $data['percent']->markup_promotion_price;
                $this->save_markup_promotion_price($amount, $percent, $virtuemart_promotion_price_id);

                $amount = $data['amount']->net_markup_promotion_price;
                $percent = $data['percent']->net_markup_promotion_price;
                $this->save_net_markup_promotion_price($amount, $percent, $virtuemart_promotion_price_id);

            } else {
                $tour_promotion_price_by_tour_promotion_price_id = $data['tour_promotion_price_by_tour_promotion_price_id'];

                $this->save_promotion_price_group_size_by_promotion_price_id_tour_private($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_promotion_price_id);

                $amount = $data['amount']->mark_up;
                $percent = $data['percent']->promotion;
                $this->save_markup_promotion_price($amount, $percent, $virtuemart_promotion_price_id);

                $amount = $data['amount']->mark_up;
                $percent = $data['percent']->promotion;
                $this->save_net_markup_promotion_price($amount, $percent, $virtuemart_promotion_price_id);

            }

        }
        return $virtuemart_promotion_price_id;
    }

    public function save_promotion_price_group_size_by_promotion_price_id($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_promotion_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_group_size_id_tour_promotion_price_id')
            ->where('virtuemart_promotion_price_id=' . (int)$virtuemart_promotion_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }
        if (count($tour_promotion_price_by_tour_promotion_price_id)) {
            foreach ($tour_promotion_price_by_tour_promotion_price_id as  $item) {
                $group_size_id=$item->virtuemart_group_size_id;
                unset($item->virtuemart_group_size_id);
                $query = $db->getQuery(true);
                $query->insert('#__virtuemart_group_size_id_tour_promotion_price_id');
                foreach ($item as $key => $value) {
                    $query->set("$key=".(int)$value);
                }
                $query->set("virtuemart_promotion_price_id=$virtuemart_promotion_price_id")
                    ->set("virtuemart_group_size_id=$group_size_id");
                if(!$db->setQuery($query)->execute())
                {
                    throw new Exception(500, $db->getErrorMsg());
                }
            }
        }

    }
    public function save_markup_promotion_price($amount, $percent, $virtuemart_promotion_price_id)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_mark_up_tour_promotion_price_id')
            ->where('virtuemart_promotion_price_id=' . (int)$virtuemart_promotion_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }
        if (count($amount)&&($amount->senior>0||$amount->adult>0||$amount->teen>0||$amount->children1>0||$amount->children2>0||$amount->infant>0||$amount->private_room>0||$amount->extra_bed>0)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_price_id');
            foreach ($amount as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_promotion_price_id=$virtuemart_promotion_price_id")
                ->set('type='.$query->q('amount'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }
        }

        if (count($percent)&&($percent->senior>0||$percent->adult>0||$percent->teen>0||$percent->children1>0||$percent->children2>0||$percent->infant>0||$percent->private_room>0||$percent->extra_bed>0)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_price_id');
            foreach ($percent as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_promotion_price_id=$virtuemart_promotion_price_id")
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
            ->where('virtuemart_promotion_price_id=' . (int)$virtuemart_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }

        if (count($amount)&&($amount->senior>0||$amount->adult>0||$amount->teen>0||$amount->children1>0||$amount->children2>0||$amount->infant>0||$amount->private_room>0||$amount->extra_bed>0)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_net_price_id');
            foreach ($amount as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_promotion_price_id=$virtuemart_price_id")
                ->set('type='.$query->q('amount'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }

        }
        if (count($percent)&&($percent->senior>0||$percent->adult>0||$percent->teen>0||$percent->children1>0||$percent->children2>0||$percent->infant>0||$percent->private_room>0||$percent->extra_bed>0)) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__virtuemart_mark_up_tour_promotion_net_price_id');
            foreach ($percent as $key => $value) {
                $query->set("$key=".(int)$value);
            }
            $query->set("virtuemart_promotion_price_id=$virtuemart_price_id")
                ->set('type='.$query->q('percent'))
                ;
            if(!$db->setQuery($query)->execute())
            {
                throw new Exception(500, $db->getErrorMsg());
            }
        }
    }

    public function save_promotion_price_group_size_by_promotion_price_id_tour_private($tour_promotion_price_by_tour_promotion_price_id, $virtuemart_promotion_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__virtuemart_group_size_id_tour_promotion_price_id')
            ->where('virtuemart_promotion_price_id=' . (int)$virtuemart_promotion_price_id);
        if(!$db->setQuery($query)->execute())
        {
            throw new Exception(500, $db->getErrorMsg());
        }


        if (count($tour_promotion_price_by_tour_promotion_price_id)) {
            foreach ($tour_promotion_price_by_tour_promotion_price_id as  $item) {
                $group_size_id=$item->virtuemart_group_size_id;
                unset($item->virtuemart_group_size_id);
                $query = $db->getQuery(true);
                $query->insert('#__virtuemart_group_size_id_tour_promotion_price_id');
                foreach ($item as $key => $value) {
                    $query->set("$key=".(int)$value);
                }
                $query->set("virtuemart_promotion_price_id=$virtuemart_promotion_price_id")
                    ->set("virtuemart_group_size_id=$group_size_id");
                if(!$db->setQuery($query)->execute())
                {
                    throw new Exception(500, $db->getErrorMsg());
                }
            }
        }
    }


}
// pure php no closing tag