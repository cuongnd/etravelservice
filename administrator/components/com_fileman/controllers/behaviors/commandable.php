<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanControllerBehaviorCommandable extends ComDefaultControllerBehaviorCommandable
{
    public function _afterGet(KCommandContext $context)
    {
        if($this->_toolbar)
        {
            if ($this->canAdd())
            {
                $this->getToolbar()->addCommand('upload');
                $this->getToolbar()->addNew(array('label' => 'New Folder'));
            }

            if ($this->canDelete()) {
                $this->getToolbar()->addDelete();
            }
            
            $this->getToolbar()->addRefresh();

            if (version_compare(JVERSION, '1.6.0', '>=') && JFactory::getUser()->authorise('core.admin', 'com_fileman'))
            {
            	$this->getToolbar()->addOptions();
            }
        }

        parent::_afterGet($context);
    }
}