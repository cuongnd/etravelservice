<?php
/**
 * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://tsmart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class tsmart
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_group_product()
    {
       $list_product=array(
           tour=>JText::_('Tour'),
           transfer=>JText::_('Transfer'),
           hotel=>JText::_('Hotel'),
           flights=>JText::_('Flights')
       );
        return $list_product;
    }
    public static function get_list_trip_type()
    {
       $list_product=array(
           private_tours=>JText::_('private tours'),
           joint_tour=>JText::_('Joint tour'),
           private_excursion=>JText::_('Private excursion'),
           joint_excursion=>JText::_('Joint excursion')
       );
        return $list_product;
    }




}