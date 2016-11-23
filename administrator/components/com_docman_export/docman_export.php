<?php
/**
 * @package     DOCman Export
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

require_once dirname(__FILE__).'/controller.php';

try
{
    $controller	= DocmanExportController::getInstance('DocmanExport');
    $controller->execute(JRequest::getCmd('task'));
    $controller->redirect();
}
catch (Exception $e) {
    if (JFactory::getDocument()->getType() === 'json') {
        $output = array(
            'error' => $e->getMessage()
        );

        echo json_encode($output);
    }

    JResponse::setHeader('Status', 500, true);
}
