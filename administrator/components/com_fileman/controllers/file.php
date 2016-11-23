<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanControllerFile extends ComDefaultControllerResource
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback('before.get', array($this, 'beforeGet'));
	}

	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'behaviors' => array('discoverable'),
			'request' => array(
				'view' => 'files'
			)
		));

		parent::_initialize($config);
	}

	public function beforeGet(KCommandContext $context)
	{
		$container = $this->getService('com://admin/files.controller.container')
						->slug('fileman-files')
						->read();

		$params = JComponentHelper::getParams('com_media');

		$config = new stdclass;

		$extensions = array_map('strtolower', explode(',', $params->get('upload_extensions')));
		$config->allowed_extensions = array_values(array_unique($extensions));

		if ($params->get('check_mime'))
		{
			$mimes = array_map('strtolower', explode(',', $params->get('upload_mime')));
			$config->allowed_mimetypes = array_unique($mimes);
		}
		else $config->allowed_mimetypes = false;

		$config->allowed_media_usergroup = $params->get('allowed_media_usergroup');

		$config->maximum_size = $params->get('upload_maxsize');
		if (version_compare(JVERSION, '1.6', '>')) {
			$config->maximum_size *= 1024*1024;
		}

		// Do not overwrite thumbnail configuration
		$config->thumbnails = $container->parameters->thumbnails;

		$container->path = $params->get('file_path');

		$container->parameters->setData((array) $config);
		// KDatabaseRow cannot detect changes in the object properties so this is needed.
		$container->parameters = $container->parameters;

		$container->save();
	}
}