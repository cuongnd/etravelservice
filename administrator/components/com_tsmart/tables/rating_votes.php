<?php
/**
*
* Product reviews table
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
* @version $Id: ratings.php 3267 2011-05-16 22:51:49Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable')) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Product review table class
 * The class is is used to manage the reviews in the shop.
 *
 * @package		tsmart
 * @author Max Milbers
 */
class TableRating_votes extends tsmTable {

	/** @var int Primary key */
	var $tsmart_rating_vote_id	= 0;
	/** @var int Product ID */
	var $tsmart_product_id			= 0;

	var $vote				= '';
	var $lastip      		= '';


	/**
	* @author Max Milbers
	* @param JDataBase $db
	*/
	function __construct(&$db) {
		parent::__construct('#__tsmart_rating_votes', 'tsmart_rating_vote_id', $db);
		$this->setPrimaryKey('tsmart_rating_vote_id');

		$this->setLoggable();
	}


}
// pure php no closing tag
