<?php
/**
 *
 * View class for the product
 *
 * @package    tsmart
 * @subpackage
 * @author
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9041 2015-11-05 11:59:38Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the tsmart Component
 *
 * @package        tsmart
 * @author RolandD,Max Milbers
 */
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

class TsmartViewProduct extends VmView
{

    function display($tpl = null)
    {
        $model_product=tmsModel::getModel('product');
        $this->list_product=$model_product->getItemList();
        parent::display($tpl);
    }

    /**
     * This is wrong
     * @deprecated
     */
    function renderMail()
    {
        $this->setLayout('mail_html_waitlist');
        $this->subject = tsmText::sprintf('com_tsmart_PRODUCT_WAITING_LIST_EMAIL_SUBJECT', $this->productName);
        $notice_body = tsmText::sprintf('com_tsmart_PRODUCT_WAITING_LIST_EMAIL_BODY', $this->productName, $this->url);

        parent::display();
    }


    /**
     * Renders the list for the discount rules
     *
     * @author Max Milbers
     */
    function renderDiscountList($selected, $name = 'product_discount_id')
    {

        if (!class_exists('tsmartModelCalc')) require(VMPATH_ADMIN . DS . 'models' . DS . 'calc.php');
        $discounts = tsmartModelCalc::getDiscounts();

        $discountrates = array();
        $discountrates[] = JHtml::_('select.option', '-1', tsmText::_('com_tsmart_PRODUCT_DISCOUNT_NONE'), 'product_discount_id');
        $discountrates[] = JHtml::_('select.option', '0', tsmText::_('com_tsmart_PRODUCT_DISCOUNT_NO_SPECIAL'), 'product_discount_id');
        //		$discountrates[] = JHtml::_('select.option', 'override', vmText::_('com_tsmart_PRODUCT_DISCOUNT_OVERRIDE'), 'product_discount_id');
        foreach ($discounts as $discount) {
            $discountrates[] = JHtml::_('select.option', $discount->tsmart_calc_id, $discount->calc_name, 'product_discount_id');
        }
        $listHTML = JHtml::_('Select.genericlist', $discountrates, $name, '', 'product_discount_id', 'text', $selected);
        return $listHTML;

    }

    static function displayLinkToChildList($product_id, $product_name)
    {

        $db = JFactory::getDBO();
        $db->setQuery(' SELECT COUNT( * ) FROM `#__tsmart_products` WHERE `product_parent_id` =' . $product_id);
        if ($result = $db->loadResult()) {
            $result = tsmText::sprintf('com_tsmart_X_CHILD_PRODUCT', $result);
            echo JHtml::_('link', JRoute::_('index.php?view=product&product_parent_id=' . $product_id . '&option=com_tsmart'), $result, array('title' => tsmText::sprintf('com_tsmart_PRODUCT_LIST_X_CHILDREN', htmlentities($product_name))));
        }
    }

    static function displayLinkToParent($product_parent_id)
    {

        $db = JFactory::getDBO();
        $db->setQuery(' SELECT * FROM `#__tsmart_products_' . tsmConfig::$vmlang . '` as l JOIN `#__tsmart_products` using (`tsmart_product_id`) WHERE `tsmart_product_id` = ' . $product_parent_id);
        if ($parent = $db->loadObject()) {
            $result = tsmText::sprintf('com_tsmart_LIST_CHILDREN_FROM_PARENT', htmlentities($parent->product_name));
            echo JHtml::_('link', JRoute::_('index.php?view=product&product_parent_id=' . $product_parent_id . '&option=com_tsmart'), $parent->product_name, array('title' => $result));
        }
    }

}

//pure php no closing tag
