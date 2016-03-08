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
    var $shared					= 0;
    var $published				= 0;
    function __construct($db)
    {
        parent::__construct('#__virtuemart_products', 'virtuemart_product_id', $db);

        $this->setLoggable();

        $this->setOrderable();

    }

}
// pure php no closing tag
