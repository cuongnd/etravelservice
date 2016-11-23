<?php
/**
*
* photo View
*
* @package	tsmart
* @subpackage photo
* @author RickG, RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id$
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
jimport( 'joomla.application.component.view');

/**
 * HTML View class for maintaining the photo
 *
 * @package	tsmart
 * @subpackage photo
 * @author RolandD, jseros
 */
class TsmartViewphoto extends JViewLegacy {

	function display($tpl = null) {

		$photos = array();
		$db = JFactory::getDBO();
		//retrieving countries id
		$country_ids = vRequest::getString('tsmart_country_id');
		$country_ids = explode(',', $country_ids);
		
		foreach($country_ids as $country_id){
			$q= 'SELECT `tsmart_photo_id`, `photo_name` FROM `#__tsmart_photos`  WHERE `tsmart_country_id`= "'.(int)$country_id.'"
				ORDER BY `#__tsmart_photos`.`photo_name`';
			$db->setQuery($q);
			
			$photos[$country_id] = $db->loadAssocList();
		}
		
		echo vmJsApi::safe_json_encode($photos);
	}
}
// pure php no closing tag
