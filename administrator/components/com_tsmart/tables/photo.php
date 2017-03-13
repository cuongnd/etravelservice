<?php
/**
*
* Currency table
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
* @version $Id: currencies.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');

/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		tsmart
 * @author RickG, Max Milbers
 */
class Tablephoto extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_photo_id				= 0;
	var $photo_name				= "";
	var $photo_store				= "";
	var $meta_title				= "";
	var $key_word				= "";
	var $tsmart_product_id				= null;
	var $file_upload				= "";
	var $description				= "";

	var $shared					= 0;
	var $published				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_photo', 'tsmart_photo_id', $db);


		$this->setLoggable();

		$this->setOrderable();
	}

	function check(){

		$tsmart_photo_id=$this->tsmart_photo_id;

		if($tsmart_photo_id)
		{
			$table_photo=tsmTable::getInstance('photo','Table');
			$photo=$table_photo->load($tsmart_photo_id);
		}


		return parent::check();
	}
	public function delete($oid = null, $where = 0)
	{
		$table_photo=tsmTable::getInstance('photo','Table');
		$photo=$table_photo->load($oid);
		$file_upload=$photo->file_upload;
		$ok= parent::delete($oid, $where); // TODO: Change the autogenerated stub

		if($ok)
		{
			jimport('joomla.filesystem.file');
			if($file_upload!="" && JFile::exists(JPATH_ROOT.DS.$file_upload))
			{
				JFile::delete(JPATH_ROOT.DS.$file_upload);
			}
		}
		return $ok;
	}

	public function store($updateNulls = false)
	{

		$tsmart_photo_id=$this->tsmart_photo_id;
		if($tsmart_photo_id)
		{
			$table_photo=tsmTable::getInstance('photo','Table');
			$photo=$table_photo->load($tsmart_photo_id);
			$file_upload=$photo->file_upload;
			$file_upload1=$this->file_upload;
			if($file_upload1=="")
			{
				$this->file_upload=$file_upload;
			}
		}

		return parent::store($updateNulls); // TODO: Change the autogenerated stub
	}
	/**
	 * ATM Unused !
	 * Checks a departure symbol wether it is a HTML entity.
	 * When not and $convertToEntity is true, it converts the symbol
	 * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
	 *
	 */
}
// pure php no closing tag
