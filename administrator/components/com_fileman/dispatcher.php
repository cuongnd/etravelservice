<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanDispatcher extends ComDefaultDispatcher
{
	protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'controller' => 'file',
            'behaviors'  => array(
                'com://admin/fileman.controller.behavior.routable'
            )
        ));
        
        parent::_initialize($config);
    }
}