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
class tsmHelper
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    static private $_tsm_helper = array();

    public static function getHepler($name){
        if (!$name) {
            $name = vRequest::getCmd('view', '');
// 			vmdebug('Get standard model of the view');
        }
        $name = strtolower($name);
        $className = 'tsm' . ucfirst($name);
        if (empty(self::$_tsm_helper[strtolower($className)])) {
            if (!class_exists($className)) {
                $helperPath = VMPATH_ADMIN . DS . "helpers" . DS . "tsm".$name . ".php";
                if (file_exists($helperPath)) {
                    require($helperPath);
                } else {
                    vmWarn('helper ' . $name . ' not found.');
                    echo 'File for helper ' . $name . ' not found.';
                    return false;
                }
            }
            self::$_tsm_helper[strtolower($className)] = new $className();
            return self::$_tsm_helper[strtolower($className)];
        } else {
            return self::$_tsm_helper[strtolower($className)];
        }
    }


}