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

require_once dirname(__FILE__).'/file.php';

class JFormFieldFile17 extends JFormField
{
	protected $type = 'File17';

	protected function getInput()
	{
		$value = $this->value;
		$name = $this->name;
		$element = new JElementFile();
		return $element->fetchElement($name, $value);
	}
}