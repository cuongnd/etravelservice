<?php
/**
*
* Product reviews table
*
* @package	tsmart
* @subpackage
* @author RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
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
class TableRating_reviews extends tsmTable {

	/** @var int Primary key */
	var $tsmart_rating_review_id	= 0;
	/** @var int Product ID */
	var $tsmart_product_id			= null;

	/** @var string The user comment */
	var $comment         				= null;
	/** @var int The number of stars awared */
	var $review_ok       				= null;

	/** The rating of shoppers for the review*/
	var $review_rates         			= null;
	var $review_ratingcount      		= null;
	var $review_rating      			= null;
	var $review_editable		   = 1;
	var $lastip      		= null;

	/** @var int State of the review */
	var $published         		= 0;


	/**
	* @author Max Milbers
	* @param JDataBase $db
	*/
	function __construct(&$db) {
		parent::__construct('#__tsmart_rating_reviews', 'tsmart_rating_review_id', $db);
		$this->setPrimaryKey('tsmart_rating_review_id');
		$this->setObligatoryKeys('comment');

		$this->setLoggable();
	}


}
// pure php no closing tag
