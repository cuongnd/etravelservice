<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin')) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for the tsmart Component
 *
 * @package        tsmart
 * @author
 */
class TsmartViewAbout extends tsmViewAdmin {

	function display ($tpl = null) {

		JToolBarHelper::title( tsmText::_( 'com_tsmart_ABOUT' )."::".tsmText::_( 'com_tsmart_CONTROL_PANEL' ), 'vm_store_48' );

		parent::display( $tpl );
	}


}

//pure php no tag