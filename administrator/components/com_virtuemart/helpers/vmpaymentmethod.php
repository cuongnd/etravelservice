<?php
/**
 * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1
 *
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class vmpaymentmethod
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_payment_method()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_paymentmethods_en_gb')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_payment_method_id_by_payment_id($virtuemart_payment_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_payment_method_id')
            ->from('#__virtuemart_payment_id_payment_method_id')
            ->where('virtuemart_payment_id='.(int)$virtuemart_payment_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }

    public static function get_list_mode_payment()
    {
        return array(
            'mode 1'=>'mode 1',
            'mode 2'=>'mode 2',
            'mode 3'=>'mode 3',
            'mode 4'=>'mode 4'
        );
    }


}