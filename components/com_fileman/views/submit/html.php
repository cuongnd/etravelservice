<?php
/**
 * @package    DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanViewSubmitHtml extends ComDefaultViewHtml
{
    protected $_toolbar;
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));

        parent::_initialize($config);
    }
    
    public function display()
    {
        $menu = JFactory::getApplication()->getMenu()->getActive();
        
        $state = $this->getModel()->getState();
        $params = $this->getService('com://site/fileman.parameter.default', array(
            'params' => $menu->params
        ));
        $params->page_title = $params->page_title ? $params->page_title : (isset($menu->name) ? $menu->name : $menu->title);
        $menu_folder = $params->get('folder');

        if (!$state->folder || ($menu_folder && strpos($state->folder, $menu_folder) !== 0)) {
            $state->folder = $menu_folder;
        }
        
        $this->assign('menu', $menu)
             ->assign('params', $params);
        
        return parent::display();
    }
    
    public function setToolbar($toolbar)
    {
        $this->_toolbar = $toolbar;
    
        return $this;
    }
    
    public function getToolbar()
    {
        return $this->_toolbar;
    }    
}
