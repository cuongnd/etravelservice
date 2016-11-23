<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

KService::setAlias('com://site/fileman.controller.filelink', 'com://admin/fileman.controller.filelink');
KService::setAlias('com://site/fileman.template.helper.listbox', 'com://admin/fileman.template.helper.listbox');
KService::setAlias('com://site/fileman.template.filter.bootstrap', 'com://admin/extman.template.filter.bootstrap');

if (KRequest::get('get.routed', 'int')) {
	KService::setAlias('com://site/fileman.dispatcher', 'com://admin/files.dispatcher');
}