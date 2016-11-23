<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

jimport('joomla.parameter.element');

class JElementFolders extends JElement
{
	public $_name = 'Folders';

	function fetchElement($name, $value, &$node = null, $control_name = null, $show_root = true, $url_encode = false)
	{
		$el_name = $control_name ? $control_name.'['.$name.']' : $name;
		
		if ($node) {
		    $show_root = $node->attributes('show_root');
            $url_encode = $node->attributes('url_encode');
		}

		$tree = KService::get('com://admin/files.controller.folder')
			->container('fileman-files')
			->tree(1)
			->limit(0)
			->browse();

		$options = array();
		
		if ($show_root) {
		    $options[] = array('text' => JText::_('ROOT_FOLDER'), 'value' => '');
		}
		
		foreach ($tree as $folder) {
			$this->_addFolder($folder, $options, $url_encode);
		}

        $selected = htmlspecialchars(($url_encode ? urlencode($value) : $value), ENT_QUOTES);

		return KService::get('com://admin/fileman.template.helper.select')->optionlist(array(
			'name' => $el_name,
			'options' => $options,
			'showroot' => false,
			'selected' => $selected
		));
	}

	protected function _addFolder($folder, &$options, $url_encode = false)
	{
		$padded = str_repeat('&nbsp;', 2*(count(explode('/', $folder->path)))).$folder->name;
        $path = htmlspecialchars($url_encode ? urlencode($folder->path) : $folder->path, ENT_QUOTES);
		$options[] = array('text' => $padded, 'value' => $path);
		if ($folder->hasChildren()) {
			foreach ($folder->getChildren() as $child) {
				$this->_addFolder($child, $options, $url_encode);
			}
		}

	}
}
