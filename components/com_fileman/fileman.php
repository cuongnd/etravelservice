<?php
/**
 * @category    FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

if (!class_exists('Koowa')) {
	return '';
}

KService::get('koowa:loader')->loadIdentifier('com://admin/fileman.init');

KService::get('koowa:loader')->loadIdentifier('com://site/fileman.aliases');

// TODO: take this out
$jlang = JFactory::getLanguage();
$jlang->load('com_fileman', JPATH_COMPONENT, 'en-GB', true);
$jlang->load('com_fileman', JPATH_COMPONENT, $jlang->getDefault(), true);
$jlang->load('com_fileman', JPATH_COMPONENT, null, true);

echo KService::get('com://site/fileman.dispatcher')->dispatch();