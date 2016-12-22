<?php

/**
 *
 * Product details view
 *
 * @package tsmart
 * @subpackage
 * @author RolandD
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
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
 * @package tsmart
 * @author Max Milbers
 */
class TsmartViewbook extends VmView {

    /**
		 * Collect all data to show on the template
		 *
		 * @author RolandD, Max Milbers
		 */
		function display($tpl = null) {
            $app=JFactory::getApplication();
            $input=$app->input;
            $tsmart_price_id=$input->getInt('tsmart_price_id',0);
            $trip_model=tmsModel::getModel('trip');
            $this->trip=$trip_model->getItem($tsmart_price_id);
            $product_model=tmsModel::getModel('product');
            $this->product=$product_model->getItem( $this->trip->tsmart_product_id);
            parent::display($tpl);
        }


}

// pure php no closing tag