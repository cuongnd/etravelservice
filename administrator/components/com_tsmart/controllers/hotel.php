<?php
/**
*
* Currency controller
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerHotel extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct();


	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
    function save($data = 0){

        $input=JFactory::getApplication()->input;
        $data=$input->getArray();
        $model = VmModel::getModel($this->_cname);

        $id = $model->store($data);
        $msg = 'failed';
        if(!empty($id)) {
            $msg = tsmText::sprintf('com_tsmart_STRING_SAVED',$this->mainLangKey);
            $type = 'message';
        }
        else $type = 'error';

        $redir = 'index.php?option=com_tsmart&view=hotel';
        $this->setRedirect($redir, $msg,$type);
    }
    public function cancel()
    {
        $redir = 'index.php?option=com_tsmart&view=hotel';
        $this->setRedirect($redir);
    }
}
// pure php no closing tag
