<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanModelDefault extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('container', 'identifier', null)
			->insert('folder'	, 'com://admin/files.filter.path', '')
			->insert('types'	, 'cmd', '')
			->insert('path'		, 'com://admin/files.filter.path', null, true) // unique
			->insert('name'		, 'com://admin/files.filter.path', null, true) // unique
			;
	}
}