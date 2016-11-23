<?php
/**
 * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://tsmart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class vmprice
{
	/**
	 * javascript strings
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $strings = array();

	/**
	 * Translates a string into the current language. This just jText of joomla 2.5.x
	 *
	 * Examples:
	 * <script>alert(Joomla.vmText._('<?php echo vmText::_("JDEFAULT", array("script"=>true));?>'));</script>
	 * will generate an alert message containing 'Default'
	 * <?php echo vmText::_("JDEFAULT");?> it will generate a 'Default' string
	 *
	 * @param   string   $string                The string to translate.
	 * @param   mixed    $jsSafe                Boolean: Make the result javascript safe.
	 * @param   boolean  $interpretBackSlashes  To interpret backslashes (\\=\, \n=carriage return, \t=tabulation)
	 * @param   boolean  $script                To indicate that the string will be push in the javascript language store
	 *
	 * @return  string  The translated string or the key is $script is true
	 *
	 * @since   11.1
	 */
		public static function get_list_group_size_by_tour_id($tsmart_product_id)
		{
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('group_size.tsmart_group_size_id,CONCAT(group_size.group_name,"(",group_size.from,"-",group_size.to,")") AS group_name,group_size.type AS group_type')
				->from('#__tsmart_tour_id_group_size_id AS tour_id_group_size_id')
				->leftJoin('#__tsmart_group_size AS group_size ON group_size.tsmart_group_size_id=tour_id_group_size_id.tsmart_group_size_id')
				->where('tour_id_group_size_id.tsmart_product_id='.(int)$tsmart_product_id)
				->where('group_size.tsmart_group_size_id!=0')
				->order('group_size.from')

			;
            $model_product = tmsModel::getModel('product');
            $product=$model_product->getItem($tsmart_product_id);
            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
            if($product->price_type!=tsmGroupSize::FLAT_PRICE)
            {
                $query->where('group_size.type!='.$query->q('flat_price'));
            }
			return $db->setQuery($query)->loadObjectList();
		}
		public static function get_list_tour_price_by_tour_price_id($tour_price_id)
		{
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('group_size_id_tour_price_id.*')
				->from('#__tsmart_group_size_id_tour_price_id AS group_size_id_tour_price_id')
				->where('group_size_id_tour_price_id.tsmart_price_id='.(int)$tour_price_id);
			return $db->setQuery($query)->loadObjectList();
		}
		public static function get_list_tour_price_by_tour_price_id_for_price($tour_price_id)
		{
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('group_size_id_tour_price_id.*')
				->from('#__tsmart_group_size_id_tour_price_id AS group_size_id_tour_price_id')
				->where('group_size_id_tour_price_id.tsmart_price_id='.(int)$tour_price_id)
			;
			return $db->setQuery($query)->loadObject();
		}
		public static function get_list_mark_up_by_tour_price_id($tour_price_id)
		{
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('mark_up_tour_price_id.*')
				->from('#__tsmart_mark_up_tour_price_id AS mark_up_tour_price_id')
				->where('mark_up_tour_price_id.tsmart_price_id='.(int)$tour_price_id);
			return $db->setQuery($query)->loadObjectList();
		}

	public static function get_list_price_type()
	{
		$list_price_type=array();


		$item=new stdClass();
		$item->value="multi_price";
		$item->text="Multi price";
		$list_price_type[]=$item;




		$item=new stdClass();
		$item->value="flat_price";
		$item->text="Flat price";
		$list_price_type[]=$item;



		return $list_price_type;
	}

    public static function get_list_base_price_by_service_class_id_and_tour_id($tour_id, $tsmart_service_class_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tour_price.*')
            ->from('#__tsmart_tour_price AS tour_price')
            ->where('tour_price.tsmart_service_class_id='.(int)$tsmart_service_class_id)
            ->where('tour_price.tsmart_product_id='.(int)$tour_id)
            ;
        $db->setQuery($query);
        $list=$db->loadObjectList();
        return $list;
    }

    public static function get_sale_price_by_mark_up_and_tax($price, $mark_up_percent, $mark_up_amount, $tax, $mark_up_type)
    {
        $price= $price+($mark_up_type=='amount'?$mark_up_amount:($price*$mark_up_percent)/100);
        return $price+($price*$tax)/100;
    }

}