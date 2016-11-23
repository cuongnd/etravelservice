<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

function FilemanRouterEncode($string)
{
	$string = str_replace("\'", "'", $string);
	$string = str_replace('\"', '\"', $string);
	$string = str_replace('%2F', '/', rawurlencode($string));
	$string = str_replace('.', '%252E', $string);
	
	return $string;
}

function FilemanRouterDecode($string)
{
	$string = str_replace('/', '%2F', rawurldecode($string));
	$string = str_replace('%252E', '.', $string);
	$string = str_replace('%20', ' ', $string);

	return $string;
}

function FilemanBuildRoute(&$query)
{
	static $items;

	$segments	= array();
	$itemid		= null;

	if (empty($query['Itemid'])) {
		return $segments;
	}

	$menu_query = JFactory::getApplication()->getMenu()->getItem($query['Itemid'])->query;
	
	if ($menu_query['view'] === 'submit' || (isset($query['view']) && $query['view'] === 'submit')) {
	    if ($menu_query['view'] === 'submit') {
	        unset($query['view']);
	    }
	    
	    return $segments;
	}

	if (isset($query['view']) && $query['view'] === 'file') {
		$segments[] = 'file';
	}
	unset($query['view']);

	if (isset($query['layout']) && isset($menu_query['layout']) && $query['layout'] === $menu_query['layout']) {
		unset($query['layout']);
	}

	if (isset($query['folder']))
	{
		if (empty($menu_query['folder'])) {
			$segments[] = str_replace('%2F', '/', $query['folder']);
		}
		else if ($query['folder'] == $menu_query['folder']) { }
		else if (strpos($query['folder'], $menu_query['folder']) === 0) {
			$relative = substr($query['folder'], strlen($menu_query['folder'])+1, strlen($query['folder']));
			$relative = str_replace($menu_query['folder'].'/', '', $query['folder']);

			$segments[] = FilemanRouterEncode($relative);
		}
		unset($query['folder']);
	}

	if (isset($query['name']))
	{
		$name = FilemanRouterEncode($query['name']);
		$segments[] = $name;
		unset($query['name']);
	}

	return $segments;
}

function FilemanParseRoute($segments)
{
	$vars = array();

	// This is here so that we don't get problems with utf-8 characters in file names
	$url   = KRequest::url();
	$path  = $url->get(KHttpUrl::PATH | KHttpUrl::FORMAT);
	$query = $url->getQuery(true);
	$site  = JFactory::getApplication()->getName();
	$path  = str_replace($site, '', $path);

	$item = JFactory::getApplication()->getMenu()->getActive();
	$path = substr($path, strpos($path, '/'.$item->route)+strlen($item->route)+2);
	$type = JFactory::getDocument()->getType();

	$config = new JConfig();
	if ($config->sef_suffix) {
		$path = substr($path, 0, strrpos($path, '.'));
	}

	$segments = explode('/', $path);

	// Circumvent Joomla's auto encoding
	foreach ($segments as &$segment)
	{
		$segment = urldecode($segment);
		$pos = strpos($segment, ':');
		if ($pos !== false) {
			$segment[$pos] = '-';
		}
	}

	$item = JFactory::getApplication()->getMenu()->getActive();
	if ($segments[0] === 'file')
	{ // file view
		$vars['view']    = array_shift($segments);
		$vars['name']    = FilemanRouterDecode(array_pop($segments));
		$vars['folder']  = $item->query['folder'] ? $item->query['folder'].'/' : '';
		$vars['folder'] .= implode('/', $segments);
	}
	else
	{ // folder view
		$vars['view']   = 'folder';
		$vars['layout'] = $item->query['layout'];
		$vars['folder'] = $item->query['folder'].'/'.implode('/', $segments);
	}
	$vars['folder'] = str_replace('%2E', '.', $vars['folder']);

	return $vars;
}