<?php
defined('_JEXEC') or die('');
/**
 * Helper to handle the templates
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2014 VirtueMart Team and author. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
 

class Vmjointgroup {

	static $_templates = array();
	static $_home = array(false,false);


    public static function get_list_rooming()
    {
        $list_rooming=array(
            'share_room'=>'Willing to share room',
            'build_room'=>'Build your room'
        );
        $a_list_rooming=array();
        foreach($list_rooming as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_rooming[]=$a_item;
        }
        return $a_list_rooming;

    }


}