<?php
/**
 * @package     DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComFilemanControllerBehaviorCommandable extends KControllerBehaviorCommandable
{
    protected function _beforeGet(KCommandContext $context)
    {
        parent::_beforeGet($context);
        
        if ($context->caller->getIdentifier()->name === 'submit') {
            // Load the language strings for toolbar button labels
            JFactory::getLanguage()->load('joomla', JPATH_ADMINISTRATOR);
            $this->getView()->setToolbar($this->getToolbar());
            
            $this->getToolbar()
                ->addCommand('save', array('label' => 'Upload', 'icon' => 'icon-32-upload'));
        }
    }

    public function _afterGet(KCommandContext $context)
    {
    }

    protected function _afterBrowse(KCommandContext $context)
    {
    }
    
    protected function _afterRead(KCommandContext $context)
    {
    }
}
