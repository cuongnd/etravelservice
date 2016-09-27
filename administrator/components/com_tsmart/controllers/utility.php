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

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerUtility extends TsmController {

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
	public function ajax_check_database()
	{
		$db=JFactory::getDbo();
		$prefix='me1u8_tsmart';

		$app=JFactory::getApplication();
		$input=$app->input;
		$json_database_path_file='administrator/components/com_tsmart/views/utility/tmpl/json_database/json_database.txt';
		$json_database=JFile::read(JPATH_ROOT.'/'.$json_database_path_file);
		$json_database=json_decode($json_database);
		$list_table=array();
		foreach($json_database as $table=>$rows)
		{
			$list_table[]=$table;
		}
		$list_table= self::synchronous_table($list_table,$prefix);
		$list_table_exists=$db->setQuery("SHOW TABLES")->loadColumn();
		foreach($json_database as $table=>$rows)
		{
			foreach($rows as $key=> $row)
			{
				$size=$row->size;
				$sql=$row->sql;
				switch($sql){
					case 'INTEGER':
					case 'FLOAT':
						$sql='int';
						$size=$size?$size:11;
						$rows[$key]->size=$size;
						$rows[$key]->sql=$sql;
						break;
					case 'MEDIUMTEXT':
						$rows[$key]->size=$size;
						break;
					case 'VARCHAR':
						$size=$size?$size:200;
						$rows[$key]->size=$size;
						break;
				}

			}
			$list_row=JArrayHelper::pivot($rows,'row_name');
			$list_row=array_change_key_case($list_row, CASE_LOWER);
			if(in_array($table,$list_table_exists))
			{
				$sql="SHOW INDEX FROM `$table`";
				$list_index=$db->setQuery($sql)->loadObjectList();
				$exists_primary_key=false;
				$exists_index_key=false;
				$list_primary_key=array();

				foreach($list_index as $index)
				{
					if($index->Key_name=='PRIMARY')
					{
						$exists_primary_key=true;
						$list_primary_key[]=$index->Column_name;
					}
					if($index->Cardinality==18)
					{
						$exists_index_key=true;
					}
				}
				if($exists_primary_key&&!$exists_index_key)
				{
					$first_primary_key=reset($list_primary_key);
					$sql="ALTER TABLE `$table` ADD INDEX(`$first_primary_key`)";
					$ok = $db->setQuery($sql)->execute();
					if (!$ok) {
						throw new Exception($db->getErrorMsg(), 505);
					}
				}

				if($exists_primary_key) {

					if(count($list_primary_key)>1)
					{
					}

					$sql = "ALTER TABLE `$table` DROP PRIMARY KEY";
					$ok = $db->setQuery($sql)->execute();
					if (!$ok) {
						throw new Exception($db->getErrorMsg(), 505);
					}
				}

				$fields=$db->getTableColumns($table);
				$fields=array_change_key_case($fields, CASE_LOWER);
				foreach($list_row as $field=>$item)
				{

					if($fields[$field]) {
						$current_type_field=$fields[$field];

						$item_row=$item;
						$ai=$item_row->ai;
						$nll=$item_row->nll;
						$nll=$nll?' NULL ':' NOT NULL ';
						if($ai)
						{
							$nll=' NOT NULL ';
						}
						$size=$item_row->size;
						$str_sql=$item_row->sql;
						$size=$size?"($size)":'';

						$sql="ALTER TABLE `$table` CHANGE  `$field` `$field` $str_sql$size $nll ";
						$db->redirectPage(false);
						$result=$db->setQuery($sql)->execute();
						if(!$result)
						{
							//echo $db->getErrorMsg();
							//die;
							//vmError($db->getErrorMsg());
						}

						$is_primary=$item_row->is_primary;

						if($ai&$is_primary)
						{

							$sql= "SELECT `$field`, COUNT(*) AS  total FROM `$table` GROUP BY `$field` HAVING total > 1;";
							$list_item_having = $db->setQuery($sql)->loadObjectList();
							if (!count($list_item_having)) {
								$sql = "ALTER TABLE `$table` ADD PRIMARY KEY(`$field`);";
								$result = $db->setQuery($sql)->execute();
								if (!$result) {
									throw new Exception($db->getErrorMsg(), 505);
								}
								$sql = "ALTER TABLE `$table` CHANGE `$field` `$field` $str_sql$size NOT NULL AUTO_INCREMENT;";
								$result = $db->setQuery($sql)->execute();
								if (!$result) {
									throw new Exception($db->getErrorMsg(), 505);
								}
							}

						}elseif(!$ai&$is_primary)
						{
							$sql= "SELECT `$field`, COUNT(*) AS  total FROM `$table` GROUP BY `$field` HAVING total > 1;";
							$list_item_having = $db->setQuery($sql)->loadObjectList();
							if (!count($list_item_having)) {
								$sql = "ALTER TABLE `$table` ADD PRIMARY KEY(`$field`);";
								$result = $db->setQuery($sql)->execute();
								if (!$result) {
									throw new Exception($db->getErrorMsg(), 505);
								}
							}

						}


					}else{
						$item_row = $item;
						$ai = $item_row->ai;
						$nll = $item_row->nll;
						$nll = $nll ? ' NULL ' : ' NOT NULL ';
						$size = $item_row->size;
						$str_sql = $item_row->sql;
						$size = $size ? "($size)" : '';
						$sql= "ALTER TABLE `$table` ADD  `$field` $str_sql$size $nll";
						$result = $db->setQuery($sql)->execute();
						if (!$result) {
							throw new Exception($db->getErrorMsg(), 505);
						}
						$is_primary=$item_row->is_primary;

						if($ai&$is_primary)
						{
							$db->redirectPage(false);
							$sql= "SET @count = 0;UPDATE `$table` SET `$table`.`$field` = @count:= @count + 1;";
							$result = $db->setQuery($sql)->execute();
							if (!$result) {
								//throw new Exception($db->getErrorMsg(), 505);
							}

							$sql = "ALTER TABLE `$table` ADD PRIMARY KEY(`$field`);";
							$result = $db->setQuery($sql)->execute();
							if (!$result) {
								throw new Exception($db->getErrorMsg(), 505);
							}
							$sql = "ALTER TABLE `$table` CHANGE `$field` `$field` $str_sql$size NOT NULL AUTO_INCREMENT;";
							$result = $db->setQuery($sql)->execute();
							if (!$result) {
								throw new Exception($db->getErrorMsg(), 505);
							}
						}elseif(!$ai&$is_primary)
						{
							$db->redirectPage(false);
							$sql= "SET @count = 0;UPDATE `$table` SET `$table`.`$field` = @count:= @count + 1;";
							$result = $db->setQuery($sql)->execute();
							if (!$result) {
								//throw new Exception($db->getErrorMsg(), 505);
							}
						}
					}
				}

				foreach($fields as $field=>$type)
				{
					if(!$list_row[$field])
					{
						$sql=" ALTER TABLE `$table` DROP `$field`";
						$result=$db->setQuery($sql)->execute();
						if(!$result)
						{
							throw new Exception($db->getErrorMsg(), 505);
						}
					}
				}
				$i=0;
				$prev_item=null;


				foreach($list_row as $field=>$item)
				{
					$size = $item->size;
					$sql = $item_row->sql;
					$size = $size ? "($size)" : '';

					if($i==0)
					{
						$sql="ALTER TABLE $table CHANGE  `$field` `$field` $sql$size  FIRST";
					}else {
						$prev_row=$prev_item->row_name;
						$sql = "ALTER TABLE $table CHANGE  `$field` `$field` $sql$size AFTER `$prev_row`";
					}
					$prev_item=$item;
					$db->redirectPage(false);
					$result=$db->setQuery($sql)->execute();
					if(!$result)
					{
						//throw new Exception($db->getErrorMsg(), 505);
					}
					$i++;
				}
			}else{
				$sql=array();
				$sql[]="CREATE TABLE $table(";
				$sql1=array();
				$list_primary_key=array();
				foreach($rows as $key=> $row)
				{

					$ai=$row->ai;
					$ai=$ai?' AUTO_INCREMENT ':'';
					$nll=$row->nll;
					$nll=$nll?' NULL ':' NOT NULL ';
					if($ai)
					{
						$nll=' NOT NULL ';
					}
					$size=$row->size;
					$str_sql=$row->sql;
					$size=$size?"($size)":'';
					$is_primary=$row->is_primary;
					if($is_primary)
					{
						$list_primary_key[]="`$row->row_name`";;
					}
					$sql1[]="`$row->row_name` $str_sql$size $nll $ai";
				}
				if(!count($list_primary_key))
				{
					$first_row=reset($rows)->row_name;
					$list_primary_key[]="`$first_row`";
				}
				$list_primary_key=implode(",",$list_primary_key);
				$sql1[]="PRIMARY KEY ($list_primary_key)";
				$sql1=implode(',',$sql1);
				$sql[]=$sql1;
				$sql[]=")";

				$sql=implode(" ",$sql);

				$result=$db->setQuery($sql)->execute();
				if(!$result)
				{
					throw new Exception($db->getErrorMsg(), 505);
				}
				$sql="ALTER TABLE `$table` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
				$result=$db->setQuery($sql)->execute();
				if(!$result)
				{
					throw new Exception($db->getErrorMsg(), 505);
				}
			}
		}
		echo 1;
		die;

	}
	public function synchronous_table($list_table_synchronous,$prefix)
	{
		$db=JFactory::getDbo();
		$tables=$db->setQuery("SHOW TABLES")->loadColumn();
		foreach($tables as $key=> $table)
		{
			if (strpos($table, $prefix) !== false) {
			}else{
				unset($tables[$key]);
			}

		}
		$list_need_table_deleted=array();

		foreach($tables as $key=> $table)
		{
			if (!in_array($table,$list_table_synchronous)) {
				$list_need_table_deleted[]="`$table`";
			}
		}
		if(!count($list_need_table_deleted))
		{
			return true;
		}
		$query='SET FOREIGN_KEY_CHECKS=0; DROP TABLE '.implode(',',$list_need_table_deleted);
		$result=$db->setQuery($query)->execute();
		if(!$result)
		{
			throw new Exception($db->getErrorMsg(), 505);
		}
		return true;
	}
}
// pure php no closing tag
