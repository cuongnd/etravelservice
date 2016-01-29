<?php
/**
 * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1
 *
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class vm_service_class
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
		public static function get_list_service_class_by_tour_id($tour_id)
		{
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('tour_service_class.virtuemart_service_class_id,tour_service_class.service_class_name')
				->from('#__virtuemart_tour_id_service_class_id AS tour_id_service_class_id')
				->leftJoin('#__virtuemart_tour_service_class AS tour_service_class ON tour_service_class.virtuemart_service_class_id=tour_id_service_class_id.virtuemart_service_class_id')
				->where('tour_id_service_class_id.virtuemart_product_id='.(int)$tour_id);
			return $db->setQuery($query)->loadObjectList();
		}

}