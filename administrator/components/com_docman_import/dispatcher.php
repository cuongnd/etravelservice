<?php
/**
 * @package    DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComDocman_importDispatcher extends ComDefaultDispatcher
{
    /**
     * Whether the document in JFactory should be reset or not
     *
     * @var bool
     */
    protected $_reset_document = false;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('before.dispatch', array($this, 'checkManageRights'));
        $this->registerCallback('before.dispatch', array($this, 'checkDocumentType'));
        $this->registerCallback('after.dispatch',  array($this, 'resetDocumentType'));
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'controller' => 'import'
        ));

        parent::_initialize($config);
    }

    public function checkManageRights(KCommandContext $context)
    {
        if (!JFactory::getUser()->authorise('core.admin')) {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'), 'error');

            return false;
        }

        return true;
    }

    /**
     * Render the page using HTML format and don't let Joomla touch it
     *
     * @param KCommand $context
     * @throws RuntimeException
     */
    public function checkDocumentType(KCommandContext $context)
    {
        if ($this->getRequest()->format === 'html') {
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_docman_import&view=import&format=page', false));
        }
        elseif ($this->getRequest()->format === 'page')
        {
            $this->_reset_document = true;

            $this->_resetDocument('html');

            $this->getRequest()->format = 'html';
        }

        return true;
    }

    /**
     * Reset the document object in Joomla after rendering it via JDocument
     *
     * @param KCommand $context
     */
    public function resetDocumentType(KCommandContext $context)
    {
        if ($this->_reset_document)
        {
            $params = array(
                'directory'	=> JPATH_ROOT.'/administrator/components/com_docman_import/views/import',
                'template'	=> 'tmpl',
                'file'		=> 'index.php',
                'params'	=> array()
            );

            $document = JFactory::getDocument();
            $document->parse($params);

            $document->setBuffer($context->result, 'component');

            $context->result = $document->render(false, $params);

            // Revert back to JDocumentRaw to complete the request
            $this->_resetDocument('raw');
        }
    }

    protected function _resetDocument($format)
    {
        $input = JFactory::getApplication()->input;
        $input->set('format', $format);

        if (version_compare(JVERSION, '3.0', '<')) {
            JRequest::setVar('format', $format);
        }

        JFactory::$document = null;
        JFactory::getDocument();
    }
}
