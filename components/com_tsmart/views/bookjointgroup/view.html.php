<?php

/**
 *
 * Product details view
 *
 * @package VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9031 2015-10-29 20:20:33Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Product details
 *
 * @property  depatrure
 * @package VirtueMart
 * @author Max Milbers
 */
class virtuemartViewbookjointgroup extends VmView {
    public $trip;
    public $product;
    public $depatrure;

    /**
		 * Collect all data to show on the template
		 *
		 * @author RolandD, Max Milbers
		 */
		function display($tpl = null) {
            $app=JFactory::getApplication();
            $input=$app->input;
            $virtuemart_departure_id=$input->getInt('virtuemart_departure_id',0);
            $jontgrouptrip_model=tmsModel::getModel('jontgrouptrip');
            $item_departure=$jontgrouptrip_model->getData($virtuemart_departure_id);
            $virtuemart_product_id=$item_departure->virtuemart_product_id;
            $input->set('virtuemart_product_id',$virtuemart_product_id);
            $jontgrouptrip_model->setState('filter.virtuemart_departure_id',$virtuemart_departure_id);
            $this->depatrure=reset($jontgrouptrip_model->getItems());
            $product_model=tmsModel::getModel('product');
            $this->product=$product_model->getItem( $this->depatrure->virtuemart_product_id);
            require_once JPATH_ROOT.'/components/com_virtuemart/helpers/vmjointgroup.php';
            $this->rooming_select=Vmjointgroup::get_list_rooming();
            require_once JPATH_ROOT.'/libraries/php-loremipsum-master/src/LoremIpsum.php';
            $this->lipsum = new joshtronic\LoremIpsum();
            parent::display($tpl);
        }


}

// pure php no closing tag