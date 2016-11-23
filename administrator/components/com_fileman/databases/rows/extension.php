<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanDatabaseRowExtension extends ComExtmanDatabaseRowExtension
{
	public function save()
	{
		$result = parent::save();

		if ($result)
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT COUNT(*) FROM #__files_containers WHERE slug = 'fileman-files'");

			if (!$db->loadResult())
			{
				$thumbnails = true;
				if (!extension_loaded('gd') && !extension_loaded('imagick')) {
					JFactory::getApplication()->enqueueMessage(JText::_('Your server does not have necessary image libraries for thumbnails. You need either GD or Imagemagick installed to make thumbnails visible.'));
					$thumbnails = false;
				}

				$params = json_encode((object) array(
					'allowed_media_usergroup' => 3,
					'thumbnails' => $thumbnails
				));
				$db->setQuery(
					"INSERT INTO `#__files_containers` (`files_container_id`, `slug`, `title`, `path`, `parameters`)
					VALUES (NULL, 'fileman-files', 'FILEman', 'images', '$params');"
				);
				$db->query();
			}

			if (version_compare(JVERSION, '1.6', '>'))
			{
				// Remove com_files from the menu table
				$db->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_files'");
				$id = $db->loadResult();
				if ($id)
				{
					$table	= JTable::getInstance('menu');
					$table->bind(array('id' => $id));
					$table->delete();
				}

				// Managers should be able to access the component by default just like com_media
				if ($this->event === 'install')
				{
					$rule = '{"core.admin":[],"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[]}';
					$db->setQuery(sprintf("UPDATE #__assets SET rules = '%s' WHERE name = '%s'", $rule, 'com_fileman'));
					$db->query();
				}
			}

			if ($this->old_version && version_compare($this->old_version, '1.0.0RC4', '<='))
			{
				// Path encoding got removed in 1.0.0RC5
				$row = KService::get('com://admin/files.model.containers')->slug('fileman-files')->getItem();
				$path = $row->path;
				$rename = array();
				$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
				foreach ($iterator as $f)
				{
					$name = $f->getFilename();
					if ($name === rawurldecode($name)) {
						continue;
					}
				
					$rename[$f->getPathname()] = $f->getPath().'/'.rawurldecode($name);
				}
					
				foreach ($rename as $from => $to) {
					rename($from, $to);
				}
			}
			
			if ($this->old_version && version_compare($this->old_version, '1.0.0RC4', '<='))
			{
				// format=raw was removed from URLs in RC4
				$id = JComponentHelper::getComponent('com_fileman')->id;
				if ($id)
				{
					$table = KService::get('com://admin/docman.database.table.menus', array('name' => 'menu'));
					$items = $table->select(array('component_id' => $id));
						
					foreach ($items as $item) {
						parse_str(str_replace('index.php?', '', $item->link), $query);
							
						if (!isset($query['view']) || $query['view'] !== 'file') {
							continue;
						}
							
						$view = $query['view'];
						
						$item->link = str_replace('&format=raw', '', $item->link);
							
						$item->save();
					}
				}
			}

            if ($this->old_version && version_compare($this->old_version, '1.0.0RC5', '<='))
            {
                // cache structure is changed. clean old cache folders
                jimport('joomla.filesystem.folder');

                $folders = JFolder::folders(JPATH_ROOT.'/cache', '^com_fileman');
                foreach ($folders as $folder) {
                    JFolder::delete(JPATH_ROOT.'/cache/'.$folder);
                }

                // thumbnail column is now a mediumtext in com_files
                $query = "ALTER TABLE `#__files_thumbnails` MODIFY `thumbnail` MEDIUMTEXT";
                JFactory::getDBO()->setQuery($query);
                JFactory::getDBO()->query();
            }
		}

		return $result;
	}

	public function delete()
	{
		$result = parent::delete();

		if ($result)
		{
			//Sometimes installer messes up and leaves stuff behind. Remove them too when uninstalling
			if (version_compare(JVERSION, '1.6', '>='))
			{
				$query = sprintf("DELETE FROM #__menu WHERE link = 'index.php?option=com_%s' AND component_id = 0 LIMIT 1", $this->component);
				$db = JFactory::getDbo();
				$db->setQuery($query);
				$db->query();
			}

			$db = JFactory::getDBO();
			$db->setQuery('SHOW TABLES LIKE '.$db->quote($db->replacePrefix('#__files_containers')));
			if ($db->loadResult()) {
				$db->setQuery("DELETE FROM `#__files_containers` WHERE `slug` = 'fileman-files'");
				$db->query();
			}

            JFactory::getCache()->clean('com_fileman');
		}

		return $result;
	}
}