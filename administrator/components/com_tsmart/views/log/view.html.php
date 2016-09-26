<?php
/**
 *
 * List/edit/remove Log Files
 *
 * @package    tsmart
 * @subpackage Log
 * @author Valérie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6307 2012-08-07 07:39:45Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('tsmViewAdmin')) {
	require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmviewadmin.php');
}

/**
 * HTML View class for log files
 *
 * @package    tsmart
 * @subpackage Log
 * @author Valérie isaksen
 */
class TsmartViewLog extends tsmViewAdmin {

	function display ($tpl = null) {

		// Load the helper(s)


		jimport('joomla.filesystem.file');
		$config = JFactory::getConfig();
		$log_path = $config->get('log_path', VMPATH_ROOT . "/log");
		$layoutName = vRequest::getCmd('layout', 'default');
		tsmConfig::loadJLang('com_tsmart_log');

		if ($layoutName == 'edit') {
			$logFile = basename(vRequest::filterPath(vRequest::getString('logfile', '')));
			$this->SetViewTitle('LOG', $logFile);
			$fileContent = file_get_contents($log_path . DS . $logFile);
			$this->fileContentByLine = explode("\n", $fileContent);
			JToolBarHelper::cancel();

		} else {
			if(!class_exists('JFolder')) require(VMPATH_LIBS.DS.'joomla'.DS.'filesystem'.DS.'folder.php');

			$this->logFiles = JFolder::files($log_path, $filter = '.', true, false, array('index.html'));

			$this->SetViewTitle('LOG');
			$this->path = $log_path;
		}

		parent::display($tpl);
	}
}

//No Closing Tag
