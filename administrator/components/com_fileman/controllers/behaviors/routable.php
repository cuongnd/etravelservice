<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Routes requests marked with routed=1 through com_files
 *
 */
class ComFilemanControllerBehaviorRoutable extends KControllerBehaviorAbstract
{
    protected function _beforeDispatch(KCommandContext $context)
    {
        // Use our own ACL instead of com_files'
        KService::setAlias('com://admin/files.controller.behavior.executable', 'com://admin/fileman.controller.behavior.executable');
        
        // Cache the hell out of JSON requests
        foreach (array('file', 'folder', 'node', 'thumbnail') as $name) {
            KService::setConfig('com://admin/files.controller.'.$name, array(
            'behaviors' => array('com://admin/fileman.controller.behavior.cacheable')
            ));
        }
        
        if ($this->getRequest()->routed) {
            $this->getRequest()->container = 'fileman-files';

            // Work-around the bug here: http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=28249
            // TODO: Move this to the Nooku Framework plugin
            JFactory::getSession()->set('com_docman.fix.the.session.bug', microtime(true));

            // Swap the language object temporarily to use our own translator
            /*$lang = JFactory::getLanguage();
            JFactory::$language = $this->getService('com://admin/docman.language.decorator', array('object' => $lang));*/

            $context->result = $this->getService('com://admin/files.dispatcher')->dispatch();

            //JFactory::$language = $lang;

            return false;
        }
    }

    protected function _beforeForward(KCommandContext $context)
    {
        if ($this->getRequest()->routed) {
            return false;
        }
    }
}
