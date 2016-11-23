<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die;

// Need to do this as Joomla 2.5 "protects" parent and manifest properties of the installer
global $installer_manifest, $installer_source;
$installer_manifest = simplexml_load_file($this->parent->getPath('manifest'));
$installer_source   = $this->parent->getPath('source');

class com_filemanInstallerScript
{
	/**
	 * Name of the component
	 */
	public $component;

	public function __construct($installer)
	{
		$class = get_class($this);
		preg_match('#^com_([a-z0-9_]+)#', get_class($this), $matches);
		$this->component = $matches[1];
	}

	public function preflight($type, $installer)
	{
		global $installer_manifest, $installer_source;
		
	    $return = true;
        $errors = array();
	    
		if (!class_exists('Koowa') || !class_exists('ComExtmanDatabaseRowExtension'))
		{
			if (file_exists(JPATH_ADMINISTRATOR.'/components/com_extman/extman.php') && !JPluginHelper::isEnabled('system', 'koowa'))
			{
				$link = version_compare(JVERSION, '1.6.0', '>=') ? '&view=plugins&filter_folder=system' : '&filter_type=system';
                $errors[] = sprintf(JText::_('This component requires System - Joomlatools Framework plugin to be installed and enabled. Please go to <a href=%s>Plugin Manager</a>, enable <strong>System - Joomlatools Framework</strong> and try again'), JRoute::_('index.php?option=com_plugins'.$link));
			}
			else $errors[] = JText::_('This component requires EXTman to be installed on your site. Please download this component from <a href=http://joomlatools.com target=_blank>joomlatools.com</a> and install it');
			
			$return = false;
		}

        // Check EXTman version.
        if ($return === true) {
            if (version_compare($this->getExtmanVersion(), '1.0.0RC5', '<')) {
                $errors[] = JText::_('This component requires a newer EXTman version. Please upgrade it first and try again.');
                $return   = false;
            }
        }
		
		// J1.5 does not remove menu items on unsuccessful installs
		if ($return === false && $type !== 'update' && version_compare(JVERSION, '1.6', '<'))
		{
		    $db = JFactory::getDBO();
		    $db->setQuery(sprintf("DELETE FROM #__components WHERE `option` = 'com_%s'", $this->component));
		    $db->query();
		}

        if ($return == false && $errors) {
            $error = implode('<br />', $errors);
            JError::raiseWarning(null, $error);
        }
		
		return $return;
	}

	public function postflight($type, $installer)
	{
		global $installer_manifest, $installer_source;
		// Need to do this as Joomla 2.5 "protects" parent and manifest properties of the installer
		$source       = $installer_source;
		$manifest     = $installer_manifest;
		$extension_id = ComExtmanInstaller::getExtensionId(array(
			'type'    => 'component',
			'element' => 'com_'.$this->component
		));

        $controller = KService::get('com://admin/extman.controller.extension', array(
            'request' => array(
                'view'   => 'extension',
                'layout' => 'success',
                'event'  => $type === 'update' ? 'update' : 'install'
            )
        ));

        $controller->add(array(
            'source' => $source,
            'manifest' => $manifest,
            'joomla_extension_id' => $extension_id,
            'event' => $type === 'update' ? 'update' : 'install'
        ));

        echo $controller->display();
    }

    /**
     * Returns the current installed version (if any) of EXTman.
     *
     * @return null|string The EXTman version if present, null otherwise.
     */
    public function getExtmanVersion()
    {
        return $this->_getExtensionVersion('com_extman');
    }

    /**
     * Extension version getter.
     *
     * @param $element The element name, e.g. com_extman, com_logman, etc.
     *
     * @return mixed|null|string The extension version, null if couldn't be determined.
     */
    protected function _getExtensionVersion($element)
    {
        $version = null;
        if (version_compare(JVERSION, '1.6', '<')) {
            // Do a manifest file check.
            if ($manifest = simplexml_load_file(JPATH_ADMINISTRATOR . '/components/'.$element.'/manifest.xml')) {
                $version = (string) $manifest->version;
            }
        } else {
            // Do a DB check.
            $query    = "SELECT manifest_cache FROM #__extensions WHERE element = '{$element}'";
            if ($result = JFactory::getDBO()->setQuery($query)->loadResult()) {
                $manifest = new JRegistry($result);
                $version  = $manifest->get('version', null);
            }
        }
        return $version;
    }
}