<?php
/**
*
* Product table
*
* @package	tsmart
* @subpackage Category
* @author jseros
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: categories.php 8810 2015-03-26 11:12:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');
/**
 * Category table class
 * The class is is used to table-level abstraction for Categories.
 *
 * @package	tsmart
 * @subpackage Category
 * @author jseros
 */
class TableCategories extends tsmTable {

	/** @var int Primary key */
	var $tsmart_category_id	= null;
	/** @var integer Product id */
	var $tsmart_vendor_id		= 0;
	/** @var string Category name */
	var $category_name		=  '';
	var $slug		=  '';
	/** @var string Category description */
	var $category_description		= '';

	/** @var string Category browse page layout */
	var $category_template = null;
	/** @var string Category browse page layout */
	var $category_layout = null;
	/** @var int Category flypage */
	var $category_product_layout		= null;

	/** @var integer Products to show per row  */
	var $products_per_row		= null;
	/** @var int Category order */
	var $ordering		= 0;

	var $shared 		= 0;

	/** @var int category limit step*/
	var $limit_list_step 	 = 0;
	/** @var int category limit initial */
	var $limit_list_initial	= 0;
	/** @var string Meta description */
	var $metadesc	= '';
	/** @var string custom title */
	var $customtitle	= '';
	/** @var string Meta keys */
	var $metakey	= '';
	/** @var string Meta robot */
	var $metarobot	= '';
	/** @var string Meta author */
	var $metaauthor	= '';
        /** @var integer Category publish or not */
	var $published			= 0;

	/**
	 * Class contructor
	 *
	 * @author Max Milbers
	 * @param $db database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__tsmart_categories', 'tsmart_category_id', $db);

		//In a VmTable the primary key is the same as the _tbl_key and therefore not needed
// 		$this->setPrimaryKey('tsmart_category_id');
		$this->setObligatoryKeys('category_name');
		$this->setLoggable();
		$this->setTranslatable(array('category_name','category_description','metadesc','metakey','customtitle'));
		$this->setSlug('category_name');
		$this->setTableShortCut('c');
	}

	public function check(){

		$csValue = $this->limit_list_step;
		if(!empty($csValue)){
			$sequenceArray = explode(',', $csValue);
			foreach($sequenceArray as &$csV){
				$csV = (int)trim($csV);
			}
			$this->limit_list_step = implode(',',$sequenceArray);
		}

		return parent::check();
	}

	/**
	 * Overwrite method
	 *
	 * @author jseros
	 * @param $dirn movement number
	 * @param $parent_id category parent id
	 * @param $where sql WHERE clausule
	 */
	public function move( $dirn, $parent_id = 0, $where='' )
	{
		if (!in_array( 'ordering',  array_keys($this->getProperties())))
		{
			vmError( get_class( $this ).' does not support ordering' );
			return false;
		}

		$k = $this->_tbl_key;

		$sql = "SELECT c.".$this->_tbl_key.", c.ordering FROM ".$this->_tbl." c
				LEFT JOIN #__tsmart_category_categories cx
				ON c.tsmart_category_id = cx.category_child_id";

		$condition = 'cx.category_parent_id = '. $this->_db->Quote($parent_id);
		$where = ($where ? ' AND '.$condition : $condition);

		if ($dirn < 0)
		{
			$sql .= ' WHERE c.ordering < '.(int) $this->ordering;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY c.ordering DESC';
		}
		else if ($dirn > 0)
		{
			$sql .= ' WHERE c.ordering > '.(int) $this->ordering;
			$sql .= ($where ? ' AND '. $where : '');
			$sql .= ' ORDER BY c.ordering';
		}
		else
		{
			$sql .= ' WHERE c.ordering = '.(int) $this->ordering;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY c.ordering';
		}

		$this->_db->setQuery( $sql, 0, 1 );


		$row = null;
		$row = $this->_db->loadObject();
		if (isset($row))
		{
			$query = 'UPDATE '. $this->_tbl
			. ' SET ordering = '. (int) $row->ordering
			. ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->$k)
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->execute())
			{
				$err = $this->_db->getErrorMsg();
				JError::raiseError( 500, 'TableCategories move isset row this->k '.$err );
			}

			$query = 'UPDATE '.$this->_tbl
			. ' SET ordering = '.(int) $this->ordering
			. ' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($row->$k)
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->execute())
			{
				$err = $this->_db->getErrorMsg();
				JError::raiseError( 500, 'TableCategories move isset row $row->$k '.$err );
			}

			$this->ordering = $row->ordering;
		}
		else
		{
			$query = 'UPDATE '. $this->_tbl
			. ' SET ordering = '.(int) $this->ordering
			. ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->$k)
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->execute())
			{
				$err = $this->_db->getErrorMsg();
				JError::raiseError( 500, 'TableCategories move update '.$err );
			}
		}
		return true;
	}

	/**
	 * Overwrite method
	 * Compacts the ordering sequence of the selected records
	 * @author jseros
	 *
	 * @param $parent_id category parent id
	 * @param string Additional where query to limit ordering to a particular subset of records
	 */
	function reorder( $parent_id = 0, $where='' )
	{
		$k = $this->_tbl_key;

		if (!in_array( 'ordering', array_keys($this->getProperties() ) ))
		{
			vmError( get_class( $this ).' does not support ordering');
			return false;
		}

		$order2 = '';
		$query = 'SELECT c.'.$this->_tbl_key.', c.ordering'
		. ' FROM '. $this->_tbl . ' c'
		. ' LEFT JOIN #__tsmart_category_categories cx'
		. ' ON c.tsmart_category_id = cx.category_child_id'
		. ' WHERE c.ordering >= 0' . ( $where ? ' AND '. $where : '' )
		. ' AND cx.category_parent_id = '. $parent_id
		. ' ORDER BY c.ordering'.$order2;

		$this->_db->setQuery( $query );
		if (!($orders = $this->_db->loadObjectList()))
		{
			vmError($this->_db->getErrorMsg());
			return false;
		}
		// compact the ordering numbers
		for ($i=0, $n=count( $orders ); $i < $n; $i++)
		{
			if ($orders[$i]->ordering >= 0)
			{
				if ($orders[$i]->ordering != $i+1)
				{
					$orders[$i]->ordering = $i+1;
					$query = 'UPDATE '.$this->_tbl
					. ' SET ordering = '. (int) $orders[$i]->ordering
					. ' WHERE '. $k .' = '. $this->_db->Quote($orders[$i]->$k)
					;
					$this->_db->setQuery( $query);
					$this->_db->execute();
				}
			}
		}

	return true;
	}
}