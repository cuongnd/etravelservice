<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanParameterDefault extends KObjectDecorator
{
	protected static $_map = array(
		'show_page_title' => 'show_page_heading',
		'page_title' 	  => 'page_heading'
	);

	protected function _initialize(KConfig $config)
	{
		if (is_string($config->params)) {
			$config->object = new JParameter($config->params);
		} else {
			$config->object = $config->params;
		}

		parent::_initialize($config);
	}

	public function __get($key)
	{
		$key = $this->_map($key);

		return $this->getObject()->get($key);
	}

	public function __set($key, $value)
	{
		$key = $this->_map($key);

		$this->getObject()->set($key, $value);
	}

	public function set()
	{
		$arguments = func_get_args();
		return $this->__call('set', $arguments);
	}

	public function get()
	{
		$arguments = func_get_args();
		return $this->__call('get', $arguments);
	}

	protected function _map($key)
	{
		if (version_compare(JVERSION, '1.6', '<')) {
			return $key;
		} else {
			return isset(self::$_map[$key]) ? self::$_map[$key] : $key;
		}
	}
}