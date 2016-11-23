<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{
    /**
     * Common method to run on both GET and POST
     * 
     * @return boolean
     */
    public function canDoStuff() 
    {
        $menu = JFactory::getApplication()->getMenu()->getActive();
        if (!$menu) {
            return false;
        }
        
        if (isset($menu->query['view'])) {
            $view = $menu->query['view'];
        
            if ($this->getMixer()->getIdentifier()->name === 'submit') {
                $params = $this->getService('com://site/fileman.parameter.default', array(
                    'params' => $menu->params
                ));
                
                return $view === 'submit' || $params->get('allow_uploads');
            }
        }
    }
    
    public function canAdd()
    {
        return $this->canDoStuff();
    }
    
	public function canGet()
	{
	    if ($this->canDoStuff() === false) {
	        return false;
	    }
	    
	    $controller = $this->getMixer()->getIdentifier()->name;
	    
		if ($controller === 'filelink') {
			return true;
		}

		$menu = JFactory::getApplication()->getMenu()->getActive();
		if ($controller !== 'submit') {
		    $folder = $menu->query['folder'];
		    
		    if (!empty($folder) && strpos(KRequest::get('get.folder', 'raw'), $folder) !== 0) {
		        return false;
		    }    
		}

		return true;
	}
}