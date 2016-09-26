<?php
/**
*
* Ratings table
*
* @package	tsmart
* @subpackage
* @author RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2012 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: ratings.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Product review table class
 * The class is is used to manage the reviews in the shop.
 *
 * @package		tsmart
 * @author Max Milbers
 */
class TableRatings extends tsmTable {

	/** @var int Product ID */

	var $tsmart_rating_id	= 0;
	var $tsmart_product_id           = 0;

	var $rates         					= 0;
	var $ratingcount      				= 0;
	var $rating      					= 0;

	/** @var int State of the review */
	var $published         		= 0;


	/**
	* @author Max Milbers
	* @param JDataBase $db
	*/
	function __construct(&$db) {
		parent::__construct('#__tsmart_ratings', 'tsmart_rating_id', $db);
		//In a VmTable the primary key is the same as the _tbl_key and therefore not needed
  		$this->setPrimaryKey('tsmart_rating_id');
//		$this->setObligatoryKeys('tsmart_product_id');

		$this->setLoggable();

		$this->setTableShortCut('r');
	}
}
// pure php no closing tag
