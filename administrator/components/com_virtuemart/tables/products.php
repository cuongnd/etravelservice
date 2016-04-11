<?php
/**
 *
 * Product table
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2009 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: products.php 8557 2014-11-09 21:06:17Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');

if (!class_exists('VmTable')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmtable.php');


class TableProducts extends VmTable
{

    var $virtuemart_product_id = 0;
    var $product_name = 0;
    var $tour_length = 0;
    var $start_city = 0;
    var $end_city = 0;
    var $virtuemart_tour_type_id = 0;
    var $virtuemart_tour_style_id = 0;
    var $virtuemart_physicalgrade_id = 0;
    var $min_person = 0;
    var $max_person = 0;
    var $min_age = 0;
    var $max_age = 0;
    var $virtuemart_tour_section_id = 0;
    var $shared					= 0;
    var $published				= 0;
    function __construct($db)
    {
        parent::__construct('#__virtuemart_products', 'virtuemart_product_id', $db);
        $this->setUniqueName('product_name');
        $this->setTranslatable(array('product_name'));
        $this->setLoggable();

        $this->setOrderable();

    }

}
// pure php no closing tag
