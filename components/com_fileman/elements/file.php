<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

jimport('joomla.parameter.element');

class JElementFile extends JElement
{
	public $_name = 'File';

	function fetchElement($name, $value, &$node = null, $control_name = null)
	{
		$el_name = $control_name ? $control_name.'['.$name.']' : $name;
		
		JHtml::_('behavior.modal');
		
		$html = KService::get('com://admin/fileman.template.helper.modal')->select(array(
			'name'  => $el_name,
			'id'    => 'fileman-file-link-name',
			'value' => $value,
			'link'  => JRoute::_('index.php?option=com_fileman&view=files&layout=select&tmpl=component')
		));

		return $html;
	}
}
