<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

if (!class_exists('JElement')) {
	class JElement {}
}

require_once dirname(__FILE__).'/folders.php';

class JFormFieldFolders17 extends JFormField
{
	protected $type = 'Folders17';

	protected function getInput()
	{
		$value = $this->value;
		$name = $this->name;
		$element = new JElementFolders();

        $show_root  = (bool) $this->element['show_root'];
        $url_encode = (bool) $this->element['url_encode'];
		
		$null = null;
		return $element->fetchElement($name, $value, $null, null, $show_root, $url_encode);
	}
}