<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanControllerSubmit extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.add', array($this, 'afterAdd'));
        $this->registerCallback('after.save', array($this, 'afterSave'));
    }
    
    public function getRequest()
    {
        $request = parent::getRequest();
    
        $request->container = 'fileman-files';
        $request->folder = trim($request->folder, '/');
    
        return $request;
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model' => 'com://admin/files.model.files'
        ));

        parent::_initialize($config);
    }

    protected function _actionAdd(KCommandContext $context)
    {
        $translator = $this->getService('translator')->getTranslator($this->getIdentifier());

        if (empty($context->data->file) && KRequest::has('files.file.tmp_name')) {
            $context->data->file = KRequest::get('files.file.tmp_name', 'raw');
            
            if (empty($context->data->name)) {
                $context->data->name = KRequest::get('files.file.name', 'raw');
            }
        }
        
        try {
            $menu = JFactory::getApplication()->getMenu()->getActive();
            if ($menu->query['view'] === 'submit') {
                $params = $this->getService('com://site/fileman.parameter.default', array(
                    'params' => $menu->params
                ));
                
                // Force the folder
                $context->data->folder = $params->get('folder');
            } else {
                $context->data->folder = $this->getRequest()->folder;
                $menu_folder = isset($menu->query['folder']) ? $menu->query['folder'] : '';
                
                if (!empty($menu_folder) && strpos($context->data->folder, $menu_folder) !== 0) {
                    throw new KControllerException($translator->translate('Invalid folder selection'));
                }
            }
            
            if (empty($context->data->file)) {
                throw new KControllerException($translator->translate('Please select a file to upload'));
            }
            
            if ($context->data->folder) {
                $this->getRequest()->folder = $context->data->folder;
            }
            
            $controller = $this->getService('com://admin/files.controller.file', array(
                'request' => $this->getRequest()
            ));

            $result = $controller->add($context);
                    
            return $result;
        } catch (KControllerException $e) {
            /*
             * We need to hack a bit here to redirect properly with an error message because
             * if an error exists in $context it displays a 500 error in the KControllerAbstract.
             * This should do it until KResponse package comes to the life
             */
            $context->setError(null);

            $context->error = $e;
            $this->afterSave($context);
            
            return false;
        }
    }

    public function afterAdd(KCommandContext $context)
    {
        if ($context->status === 201) {
            $this->sendNotifications($context);
        }
    }

    public function sendNotifications(KCommandContext $context)
    {
        $page = JFactory::getApplication()->getMenu()->getItem($this->getRequest()->Itemid);
        $translator = $this->getService('translator')->getTranslator($this->getIdentifier());
        $params = $this->getService('com://site/fileman.parameter.default', array(
            'params' => $page->params
        ));
        
        $emails = $params->get('notification_emails');
        if (empty($emails)) {
            return;
        }

        $emails = explode("\n", $emails);

        $config	= JFactory::getConfig();
        $from_name = $config->get('fromname');
        $mail_from = $config->get('mailfrom');
        $sitename = $config->get('sitename');
        $subject = $translator->translate('A new file was submitted for you to review on %sitename%', array(
            '%sitename%' => $sitename));

        $admin_link = JURI::root().'administrator/index.php?option=com_fileman&view=files';
        $name = $context->result->name;
        $admin_title = $translator->translate('File Manager');

        foreach ($emails as $email)
        {
            $body = $translator->translate('Submit notification mail body', array(
                '%name%'     => $name,
                '%sitename%' => $sitename,
                '%url%'      => $admin_link,
                '%url_text%' => $admin_title
            ));
            JFactory::getMailer()->sendMail($mail_from, $from_name, $email, $subject, $body, true);
        }
    }

    public function afterSave(KCommandContext $context)
    {
        $url = 'index.php?Itemid='.$this->getRequest()->Itemid;
        
        $menu = JFactory::getApplication()->getMenu()->getActive();
        if ($menu->query['view'] !== 'submit') {
            $url .= '&folder='.$this->getRequest()->folder;
        }
        
        $route = JRoute::_($url, false);
        $message = '';
        $type = 'message';
        
        if ($context->status === 201) {
            $translator = $this->getService('translator')->getTranslator($this->getIdentifier());
            $message = $translator->translate('File is uploaded successfully');
        } elseif ($context->error instanceof Exception) {
            $message = $context->error->getMessage();
            $type = 'error';
        }
        
        $this->setRedirect($route, $message, $type);
    }
}
