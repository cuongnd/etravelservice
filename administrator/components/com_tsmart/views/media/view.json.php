<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage
* @author  Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 3006 2011-04-08 13:16:08Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * Json View class for the tsmart Component
 *
 * @package		tsmart
 * @author  Patrick Kohl
 */
class TsmartViewMedia extends tsmViewAdmin {

	/* json object */
	private $json = null;

	function display($tpl = null) {
		$document =JFactory::getDocument();
		$document->setMimeEncoding( 'application/json' );

		if ($tsmart_media_id = vRequest::getInt('tsmart_media_id')) {
			//JResponse::setHeader( 'Content-Disposition', 'attachment; filename="media'.$tsmart_media_id.'.json"' );

			$model = VmModel::getModel('Media');
			$image = $model->createMediaByIds($tsmart_media_id);
// 			echo '<pre>'.print_r($image,1).'</pre>';
			$this->json = $image[0];
			//echo json_encode($this->json);
			if (isset($this->json->file_url)) {
				$this->json->file_root = JURI::root(true).'/';
				$this->json->msg =  'OK';
				echo vmJsApi::safe_json_encode($this->json);
			} else {
				$this->json->msg =  '<b>'.tsmText::_('com_tsmart_NO_IMAGE_SET').'</b>';
				echo @json_encode($this->json);
			}
		}
		else {
			if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');
			$start = vRequest::getInt('start',0);

			$type = vRequest::getCmd('mediatype',0);
			$list = VmMediaHandler::displayImages($type,$start );
			echo vmJsApi::safe_json_encode($list);
		}

		jExit();
	}


}
// pure php no closing tag
