<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{
	public function canGet()
	{
		$result = false;

		if(version_compare(JVERSION,'1.6.0','ge')) {
			$result = JFactory::getUser()->authorise('core.manage', 'com_fileman') === true;
		} else {
			$result = JFactory::getUser()->get('gid') > 22;
		}
		return $result;
	}

	public function canMove()
	{
		return $this->canGet() && $this->canDelete() && $this->canAdd();
	}

	public function canCopy()
	{
		return $this->canGet() && $this->canAdd();
	}

	public function canAdd()
	{
		$result = $this->canGet();

		if ($result) {
			if(version_compare(JVERSION,'1.6.0','ge')) {
				$result = JFactory::getUser()->authorise('core.create', 'com_fileman') === true;
			} else {
				$result = JFactory::getUser()->get('gid') > 22;
			}
		}

		return $result;
	}

	public function canEdit()
	{
		$result = $this->canGet();

		if ($result) {
			if(version_compare(JVERSION,'1.6.0','ge')) {
				$result = JFactory::getUser()->authorise('core.edit', 'com_fileman') === true;
			} else {
				$result = JFactory::getUser()->get('gid') > 22;
			}
		}

		return $result;
	}

	public function canDelete()
	{
		$result = $this->canGet();

		if ($result) {
			if(version_compare(JVERSION,'1.6.0','ge')) {
				$result = JFactory::getUser()->authorise('core.delete', 'com_fileman') === true;
			} else {
				$result = JFactory::getUser()->get('gid') > 22;
			}
		}

		return $result;
	}
}
