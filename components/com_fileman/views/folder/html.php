<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanViewFolderHtml extends ComDefaultViewHtml
{
	public function display()
	{
		$state = $this->getModel()->getState();

		$menu = JFactory::getApplication()->getMenu()->getActive();
		$params = $this->getService('com://site/fileman.parameter.default', array(
			'params' => $menu->params
		));

		if ($params->limit != -1) {
			$state->limit = (int) $params->limit;
		} else {
            $state->limit = $state->limit ? $state->limit : JFactory::getApplication()->getCfg('list_limit');
        }
		
		$state->sort = $params->sort;
		$state->direction = $params->direction;

		$state_data = $state->getData();
		$state_data['container'] = 'fileman-files';
		$state_data['folder'] = isset($state_data['folder']) ? rawurldecode($state_data['folder']) : '';  

		if ($this->getLayout() === 'gallery') {
			$state_data['types'] = array('image');
		}

		$copy = $state_data;
		$copy['name'] = $state_data['folder'];
		unset($copy['folder']);

		$folder = $this->getService('com://admin/files.controller.folder', array(
			'request' => array('folder' => $state->folder, 'name' => $state->name)
		))->read();

		$folders = array();
		if ($params->show_folders) 
		{
			$folders = $this->getService('com://admin/files.controller.folder', array(
				'request' => $state_data
			))->browse();
		}

		$state_data['thumbnails'] = !!$params->show_thumbnails;
		$file_controller = $this->getService('com://admin/files.controller.file', array(
			'request' => $state_data
		));

		$files = $file_controller->browse();
		$total = $file_controller->getModel()->getTotal();

		foreach ($folders as $f)
		{
			if ($params->humanize_filenames) {
				$f->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $f->name));
			} else {
				$f->display_name = $f->name;
			}
		}
		
		foreach ($files as $f)
		{
			if ($params->humanize_filenames) {
				$f->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $f->filename));
			} else {
				$f->display_name = $f->name;
			}
		}
		
		$parent = null;
		if ($menu->query['folder'] !== $folder->path)
		{
			$path   = explode('/', $folder->path);
			$parent = count($path) > 1 ? implode('/', array_slice($path, 0, count($path)-1)) : '';
		}
		
		$params->page_title = $params->page_title ? $params->page_title : (isset($menu->name) ? $menu->name : $menu->title);
		
		if (JFactory::getUser()->guest) {
		    $params->allow_uploads = false;
		}

		$this->assign('folder',  $folder)
			 ->assign('files',   $files)
			 ->assign('total', 	 $total)
			 ->assign('folders', $folders)
			 ->assign('parent',  $parent)
			 ->assign('params',  $params)
			 ->assign('menu', 	 $menu)
			 ->assign('thumbnail_size', array('x' => 200, 'y' => 150));
			 ;

		$this->setPathway();

		return parent::display();
	}

	public function setPathway()
	{
		if ($this->parent !== null)
		{
			$pathway = JFactory::getApplication()->getPathway();
						
			$path = $this->folder->path;
			if (!empty($this->menu->query['folder']) && strpos($path, $this->menu->query['folder']) === 0) {
				$path = substr($path, strlen($this->menu->query['folder'])+1, strlen($path));
			    $append = $this->menu->query['folder'];
			}
			$parts = explode('/', $path);

			foreach ($parts as $i => $part)
			{
				if ($part !== $this->folder->name)
				{
					$path = $append.'/'.implode('/', array_slice($parts, 0, $i+1));
					$link = JRoute::_('index.php?option=com_fileman&layout='.$this->getLayout().'&view=folder&folder='.$path);
				}
				else $link = '';

				$pathway->addItem(ucfirst($part), $link);
			}
		}
	}
}