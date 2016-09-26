<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package    tsmart
 * @subpackage Currency
 */
class tsmartModelPrice extends tmsModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('price');
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getPrice($price_id = 0)
    {
        $item = $this->getData($price_id);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('tour_class.*')
            ->from('#__tsmart_service_class AS tour_class')
            ->where('tour_class.tsmart_service_class_id=' . (int)$item->service_class_id);
        $item->service_class = $db->setQuery($query)->loadObject();

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

        $data = $this->exeSortSearchListQuery(0, '*', ' FROM `#__tsmart_tour_price`', $whereString, '', $this->_getOrdering());

        return $data;
    }

    function getPricesListByTourid($tour_id)
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('tour_price.*')
            ->from('#__tsmart_tour_price AS tour_price')
            ->leftJoin('#__tsmart_service_class AS tour_service_class ON tour_service_class.tsmart_service_class_id=tour_price.tsmart_service_class_id')
            ->select('tour_service_class.service_class_name')
            ->where('tour_price.tsmart_product_id=' . (int)$tour_id)
            ->leftJoin('#__tsmart_products AS products ON products.tsmart_product_id=tour_price.tsmart_product_id')
            ->select('products.price_type')
            ->innerJoin('#__tsmart_tour_type AS tour_type ON tour_type.tsmart_tour_type_id=products.tsmart_tour_type_id')
            ->select('tour_type.title AS tour_type_name')
            ->order('tour_price.sale_period_from,tour_price.sale_period_to,tour_service_class.ordering')
        ;
        echo $query->dump();
        return $db->setQuery($query)->loadObjectList();

    }

    function getVendorAcceptedCurrrenciesList($vendorId = 0)
    {

        static $currencies = array();
        if ($vendorId === 0) {
            $multix = Vmconfig::get('multix', 'none');
            if (strpos($multix, 'payment') !== FALSE) {
                if (!class_exists('tsmartModelVendor'))
                    require(VMPATH_ADMIN . DS . 'models' . DS . 'vendor.php');
                $vendorId = tsmartModelVendor::getLoggedVendor();

            } else {
                $vendorId = 1;
            }
        }
        if (!isset($currencies[$vendorId])) {
            $db = JFactory::getDbo();
            $q = 'SELECT `vendor_accepted_currencies`, `vendor_currency` FROM `#__tsmart_vendors` WHERE `tsmart_vendor_id`=' . $vendorId;
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
            $q = 'SELECT `tsmart_currency_id`,CONCAT_WS(" ",`currency_name`,`currency_symbol`) as currency_txt
					FROM `#__tsmart_currencies` WHERE `tsmart_currency_id` IN (' . $vendor_currency['vendor_accepted_currencies'] . ')';
            if ($vendorId != 1) {
                $q .= ' AND (`tsmart_vendor_id` = "' . $vendorId . '" OR `shared`="1")';
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
        $tsmart_price_id = parent::store($data);
        if ($tsmart_price_id) {
            $tsmart_product_id = $data['tsmart_product_id'];
            $model_product = $this->getModel('product');
            $product = $model_product->getItem($tsmart_product_id);
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
            if ($tsmart_price_id && $product->price_type != tsmGroupSize::FLAT_PRICE) {
                $tour_price_by_tour_price_id = $data['tour_price_by_tour_price_id'];
                $this->save_price_group_size_by_price_id($tour_price_by_tour_price_id, $tsmart_price_id);
            } else {
                $tour_price_by_tour_price_id = $data['tour_price_by_tour_price_id'];

                $this->save_price_group_size_by_price_id_tour_private($tour_price_by_tour_price_id, $tsmart_price_id);
            }
            $amount = $data['amount'];
            $percent = $data['percent'];
            $this->save_markup_price($amount, $percent, $tsmart_price_id);
        }
        return $tsmart_price_id;
    }

    public function save_price_group_size_by_price_id($tour_price_by_tour_price_id, $tsmart_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__tsmart_group_size_id_tour_price_id')
            ->where('tsmart_price_id=' . (int)$tsmart_price_id);
        if (!$db->setQuery($query)->execute()) {
            throw new Exception(500, $db->getErrorMsg());
        }

        if (count($tour_price_by_tour_price_id)) {
            $price_extra_bed = reset($tour_price_by_tour_price_id)->price_extra_bed;
            foreach ($tour_price_by_tour_price_id as $item) {
                $group_size_id = $item->tsmart_group_size_id;
                unset($item->tsmart_group_size_id);
                unset($item->price_extra_bed);
                $query = $db->getQuery(true);
                $query->insert('#__tsmart_group_size_id_tour_price_id');
                foreach ($item as $key => $value) {
                    $query->set("$key=" . (int)$value);
                }
                $query->set("price_extra_bed=" . (int)$price_extra_bed)
                    ->set("tsmart_price_id=$tsmart_price_id")
                    ->set("tsmart_group_size_id=$group_size_id");
                if (!$db->setQuery($query)->execute()) {
                    throw new Exception(500, $db->getErrorMsg());
                }
            }
        }
    }

    public function save_markup_price($amount, $percent, $tsmart_price_id)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__tsmart_mark_up_tour_price_id')
            ->where('tsmart_price_id=' . (int)$tsmart_price_id);
        if (!$db->setQuery($query)->execute()) {
            throw new Exception(500, $db->getErrorMsg());
        }
        $amount1 = (array)$amount;
        if ($amount1['adult'] || $amount1['children1'] || $amount1['children2'] || $amount1['extra_bed'] || $amount1['infant'] || $amount1['private_room'] || $amount1['senior'] || $amount1['teen']) {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__tsmart_mark_up_tour_price_id');
            foreach ($amount as $key => $value) {
                $query->set("$key=" . (int)$value);
            }
            $query->set("tsmart_price_id=$tsmart_price_id")
                ->set('type=' . $query->q('amount'));
            if (!$db->setQuery($query)->execute()) {
                throw new Exception(500, $db->getErrorMsg());
            }
        } else {
            $query = $db->getQuery(true);
            $query->clear()
                ->insert('#__tsmart_mark_up_tour_price_id');
            foreach ($percent as $key => $value) {
                $query->set("$key=" . (int)$value);
            }
            $query->set("tsmart_price_id=$tsmart_price_id")
                ->set('type=' . $query->q('percent'));
            if (!$db->setQuery($query)->execute()) {
                throw new Exception(500, $db->getErrorMsg());
            }
        }
    }

    public function populateState($ordering = null, $direction = null)
    {
        parent::populateState($ordering, $direction); // TODO: Change the autogenerated stub
    }

    public function save_price_group_size_by_price_id_tour_private($tour_price_by_tour_price_id, $tsmart_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__tsmart_group_size_id_tour_price_id')
            ->where('tsmart_price_id=' . (int)$tsmart_price_id);
        if (!$db->setQuery($query)->execute()) {
            throw new Exception(500, $db->getErrorMsg());
        }

        if (count($tour_price_by_tour_price_id)) {
            $price_extra_bed = reset($tour_price_by_tour_price_id)->price_extra_bed;
            foreach ($tour_price_by_tour_price_id as $item) {
                $group_size_id = $item->tsmart_group_size_id;
                unset($item->tsmart_group_size_id);
                unset($item->price_extra_bed);
                $query = $db->getQuery(true);
                $query->insert('#__tsmart_group_size_id_tour_price_id');
                foreach ($item as $key => $value) {
                    $query->set("$key=" . (int)$value);
                }
                $query->set("price_extra_bed=" . (int)$price_extra_bed)
                    ->set("tsmart_price_id=$tsmart_price_id")
                    ->set("tsmart_group_size_id=$group_size_id");
                if (!$db->setQuery($query)->execute()) {
                    throw new Exception(500, $db->getErrorMsg());
                }
            }
        }
    }


}
// pure php no closing tag