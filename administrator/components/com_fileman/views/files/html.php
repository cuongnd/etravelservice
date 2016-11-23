<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanViewFilesHtml extends ComDefaultViewHtml
{
	public function display()
	{
		if ($this->getLayout() !== 'select') 
		{
            $container = $this->getService('com://admin/files.controller.container')->slug('fileman-files')->read();

            // Note: PHP converts dots to underscores in cookie names
            $cookie = json_decode(KRequest::get('cookie.com_files_container_fileman-files_state', 'raw', ''), true);

            // Check if the folder exists.
            if (is_array($cookie) && isset($cookie['folder']))
            {
                $adapter = clone $container->getAdapter('folder');
                $adapter->setPath($container->path . '/' . $cookie['folder']);
                // Unset folder cookie if path does not exists.
                if (!$adapter->exists())
                {
                    unset($cookie['folder']);
                    //KRequest::set('cookie.com_files_container_docman-files_state', json_encode($cookie));
                    setcookie('com.files.container.fileman-files.state', json_encode($cookie), 0, $this->baseurl);
                }
            }

			$file_controller = $this->getService('com://admin/files.controller.file', array('request' => array('container' => 'fileman-files')));
			$view = $file_controller->getView();
			$view->setLayout('com://admin/fileman.view.files.tmpl.app.default');
			$view->getTemplate()->addFilter('com://admin/fileman.template.filter.overrider');
			

			$state = clone $this->getModel()->getState();
			$state->container = 'fileman-files';

            if ($cookie) {
                $state->setData($cookie);
            }

			if (!$state->limit) {
			    $state->limit = JFactory::getApplication()->getCfg('list_limit');
			}

			$node_controller = $this->getService('com://admin/files.controller.node')
			    ->setRequest($state->toArray())->format('json');
			
			$node_controller->thumbnails = $container->parameters->thumbnails;
			
			$initial_response = $node_controller->display();
			
			$html = $file_controller->limit($state->limit)
    			->offset($state->offset)
    			->container($container)
    			->config(array(
    				'initial_response' => $initial_response,
    				'router' => array(
    					'defaults' => (object) array(
    					    'option' => 'com_fileman',
    						'routed' => '1'
    					)
    				)
    			))
    			->display();
				
			$this->assign('app', $html);
			
		}
		return parent::display();
	}
}