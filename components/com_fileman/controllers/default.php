<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanControllerDefault extends ComDefaultControllerResource
{
	public function getRequest()
	{
		$request = parent::getRequest();

		$request->container = 'fileman-files';
		$request->folder = trim($request->folder, '/');

		$limit = $request->limit;

		if (empty($limit)) {
			$limit = JFactory::getApplication()->getCfg('list_limit');
		}

		if ($limit > 100) {
			$limit = 100;
		}

		$request->limit = $limit;

		return $request;
	}
}
