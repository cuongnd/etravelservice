<?php
/**
 *
 * Data module for shop users
 *
 * @package    tsmart
 * @subpackage User
 * @author Oscar van Eijk
 * @author Max Milbers
 * @author    RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: user.php 9021 2015-10-20 23:54:07Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Hardcoded groupID of the Super Admin
define('__SUPER_ADMIN_GID', 25);

if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');


/**
 * Model class for shop users
 *
 * @package    tsmart
 * @subpackage    User
 * @author    RickG
 * @author Max Milbers
 */
class tsmartModelUser extends tmsModel
{


    /**
     * Constructor for the user model.
     *
     * The user ID is read and determined if it is an array of ids or just one single id.
     */
    function __construct()
    {

        parent::__construct('tsmart_user_id');

        $this->setToggleName('user_is_vendor');
        $this->addvalidOrderingFieldName(array('ju.username', 'ju.name', 'ju.email', 'sg.tsmart_shoppergroup_id', 'shopper_group_name', 'shopper_group_desc', 'vmu.tsmart_user_id'));
        $this->setMainTable('vmusers');
        $this->removevalidOrderingFieldName('tsmart_user_id');
        array_unshift($this->_validOrderingFieldName, 'ju.id');
    }

    /**
     * public function Resets the user id and data
     *
     *
     * @author Max Milbers
     */
    public function setId($cid)
    {

        $user = JFactory::getUser();
        //anonymous sets to 0 for a new entry
        if (empty($user->id)) {
            $userId = 0;
            //vmdebug('Recognized anonymous case');
        } else {
            //not anonymous, but no cid means already registered user edit own data
            if (empty($cid)) {
                $userId = $user->id;
                //vmdebug('setId setCurrent $user',$user->get('id'));
            } else {
                if ($cid != $user->id) {
                    $user = JFactory::getUser();
                    if (vmAccess::manager(array('user', 'user.edit'))) {
                        $userId = $cid;
                        //vmdebug('setId is Manager',$userId);
                    } else {
                        vmError('Blocked attempt setId ' . $cid . ' ' . $user->id);
                        $userId = $user->id;
                    }
                } else {
                    $userId = $user->id;
                    //vmdebug('setId setCurrent $user',$user->get('id'));
                }
            }
        }

        $this->setUserId($userId);
        return $userId;

    }

    /**
     * Internal function
     *
     * @param unknown_type $id
     */
    private function setUserId($id)
    {

        if ($this->_id != $id) {
            $this->_id = (int)$id;
            $this->_data = null;
            $this->customer_number = 0;
        }
    }

    public function getCurrentUser()
    {
        $user = JFactory::getUser();
        $this->setUserId($user->id);
        return $this->getUser();
    }

    private $_defaultShopperGroup = 0;

    /**
     * Sets the internal user id with given vendor Id
     *
     * @author Max Milbers
     * @param int $vendorId
     */
    function getVendor($vendorId = 1, $return = TRUE)
    {
        $vendorModel = tmsModel::getModel('vendor');
        $userId = tsmartModelVendor::getUserIdByVendorId($vendorId);
        if ($userId) {
            $this->setUserId($userId);
            if ($return) {
                return $this->getUser();
            }
        } else {
            return false;
        }
    }


    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     * @author Max Milbers
     */
    function getUser()
    {

        if (!empty($this->_data)) return $this->_data;

        $db = JFactory::getDBO();

        $this->_data = $this->getTable('vmusers');
        $this->_data->load((int)$this->_id);
        $this->_data->JUser = JUser::getInstance($this->_id);

        // Add the tsmart_shoppergroup_ids
        if (!empty($this->_id)) {
            $xrefTable = $this->getTable('vmuser_shoppergroups');
            $this->_data->shopper_groups = $xrefTable->load($this->_id);
        }
        if (empty($this->_data->shopper_groups)) $this->_data->shopper_groups = array();

        $site = JFactory::getApplication()->isSite();
        if ($site) {
            $shoppergroupmodel = tmsModel::getModel('ShopperGroup');
            $shoppergroupmodel->appendShopperGroups($this->_data->shopper_groups, $this->_data->JUser, $site);
        }

        if (!empty($this->_id)) {
            $q = 'SELECT `tsmart_userinfo_id` FROM `#__tsmart_userinfos` WHERE `tsmart_user_id` = "' . (int)$this->_id . '" ORDER BY `address_type` ASC';
            $db->setQuery($q);
            $userInfo_ids = $db->loadColumn(0);
        } else {
            $userInfo_ids = array();
        }

        $this->_data->userInfo = array();
        $BTuid = 0;

        foreach ($userInfo_ids as $uid) {

            $this->_data->userInfo[$uid] = $this->getTable('userinfos');
            $this->_data->userInfo[$uid]->load($uid);

            if ($this->_data->userInfo[$uid]->address_type == 'BT') {
                $BTuid = $uid;

                $this->_data->userInfo[$BTuid]->name = $this->_data->JUser->name;
                $this->_data->userInfo[$BTuid]->email = $this->_data->JUser->email;
                $this->_data->userInfo[$BTuid]->username = $this->_data->JUser->username;
                $this->_data->userInfo[$BTuid]->address_type = 'BT';
                // 				vmdebug('$this->_data->vmusers',$this->_data);
            }
        }

        // 		vmdebug('user_is_vendor ?',$this->_data->user_is_vendor);
        if ($this->_data->user_is_vendor) {

            $vendorModel = tmsModel::getModel('vendor');
            if (tsmConfig::get('multix', 'none') == 'none') {
                $this->_data->tsmart_vendor_id = 1;
                //vmdebug('user model, single vendor',$this->_data->tsmart_vendor_id);
            }

            $vendorModel->setId($this->_data->tsmart_vendor_id);
            $this->_data->vendor = $vendorModel->getVendor();
        }

        return $this->_data;
    }


    /**
     * Retrieve contact info for a user if any
     *
     * @return array of null
     */
    function getContactDetails()
    {
        if ($this->_id) {
            $db = JFactory::getDBO();
            $db->setQuery('SELECT * FROM #__contact_details WHERE user_id = ' . $this->_id);
            $_contacts = $db->loadObjectList();
            if (count($_contacts) > 0) {
                return $_contacts[0];
            }
        }
        return null;
    }


    /**
     * Bind the post data to the JUser object and the VM tables, then saves it
     * It is used to register new users
     * This function can also change already registered users, this is important when a registered user changes his email within the checkout.
     *
     * @author Max Milbers
     * @author Oscar van Eijk
     * @return boolean True is the save was successful, false otherwise.
     */
    public function store(&$data)
    {

        $message = '';
        vRequest::vmCheckToken('Invalid Token, while trying to save user');

        if (empty($data)) {
            vmError('Developer notice, no data to store for user');
            return false;
        }

        //To find out, if we have to register a new user, we take a look on the id of the usermodel object.
        //The constructor sets automatically the right id.
        $new = false;
        if (empty($this->_id) or $this->_id < 1) {
            $new = true;
            $user = new JUser();    //thealmega http://forum.tsmart.net/index.php?topic=99755.msg393758#msg393758
        } else {
            $cUser = JFactory::getUser();
            if (!vmAccess::manager('user.edit') and $cUser->id != $this->_id) {
                vmWarn('Insufficient permission');
                return false;
            }
            $user = JFactory::getUser($this->_id);
        }

        $gid = $user->get('gid'); // Save original gid

        // Preformat and control user datas by plugin
        JPluginHelper::importPlugin('vmuserfield');
        $dispatcher = JDispatcher::getInstance();

        $valid = true;
        $dispatcher->trigger('plgVmOnBeforeUserfieldDataSave', array(&$valid, $this->_id, &$data, $user));
        // $valid must be false if plugin detect an error
        if (!$valid) {
            return false;
        }

        // Before I used this "if($cart && !$new)"
        // This construction is necessary, because this function is used to register a new JUser, so we need all the JUser data in $data.
        // On the other hand this function is also used just for updating JUser data, like the email for the BT address. In this case the
        // name, username, password and so on is already stored in the JUser and dont need to be entered again.

        if (empty ($data['email'])) {
            $email = $user->get('email');
            if (!empty($email)) {
                $data['email'] = $email;
            }
        } else {
            $data['email'] = vRequest::getEmail('email', '');
        }
        //$data['email'] = str_replace(array('\'','"',',','%','*','/','\\','?','^','`','{','}','|','~'),array(''),$data['email']);

        //This is important, when a user changes his email address from the cart,
        //that means using view user layout edit_address (which is called from the cart)
        $user->set('email', $data['email']);

        if (empty ($data['name'])) {
            $name = $user->get('name');
            if (!empty($name)) {
                $data['name'] = $name;
            }

        } else {
            $data['name'] = vRequest::getWord('name', '');

        }
        $data['name'] = str_replace(array('\'', '"', ',', '%', '*', '/', '\\', '?', '^', '`', '{', '}', '|', '~'), array(''), $data['name']);

        if (empty ($data['username'])) {
            $username = $user->get('username');
            if (!empty($username)) {
                $data['username'] = $username;
            } else {
                $data['username'] = vRequest::getWord('username', '');
            }
        }

        if (empty ($data['password'])) {
            $data['password'] = vRequest::getCmd('password', '');
            if ($data['password'] != vRequest::get('password')) {
                vmError('Password contained invalid character combination.');
                return false;
            }
        }

        if (empty ($data['password2'])) {
            $data['password2'] = vRequest::getCmd('password2');
            if ($data['password2'] != vRequest::get('password2')) {
                vmError('Password2 contained invalid character combination.');
                return false;
            }
        }

        if (!$new and empty($data['password2'])) {
            unset($data['password']);
            unset($data['password2']);
        }

        if (!vmAccess::manager('core')) {
            $whiteDataToBind = array();
            if (isset($data['name'])) $whiteDataToBind['name'] = $data['name'];
            if (isset($data['username'])) $whiteDataToBind['username'] = $data['username'];
            if (isset($data['email'])) $whiteDataToBind['email'] = $data['email'];
            if (isset($data['language'])) $whiteDataToBind['language'] = $data['language'];
            if (isset($data['editor'])) $whiteDataToBind['editor'] = $data['editor'];
            if (isset($data['password'])) $whiteDataToBind['password'] = $data['password'];
            if (isset($data['password2'])) $whiteDataToBind['password2'] = $data['password2'];
            unset($data['isRoot']);
        } else {
            $whiteDataToBind = $data;
        }

        // Bind Joomla userdata
        if (!$user->bind($whiteDataToBind)) {
            vmdebug('Couldnt bind data to joomla user');
            //array('user'=>$user,'password'=>$data['password'],'message'=>$message,'newId'=>$newId,'success'=>false);
        }

        if ($new) {
            // If user registration is not allowed, show 403 not authorized.
            // But it is possible for admins and storeadmins to save
            $usersConfig = JComponentHelper::getParams('com_users');

            $cUser = JFactory::getUser();
            if ($usersConfig->get('allowUserRegistration') == '0' and !(vmAccess::manager('user'))) {
                tsmConfig::loadJLang('com_tsmart');
                vmError(tsmText::_('com_tsmart_ACCESS_FORBIDDEN'));
                return;
            }
            // Initialize new usertype setting
            $newUsertype = $usersConfig->get('new_usertype');
            if (!$newUsertype) {
                $newUsertype = 2;
            }

            // Set some initial user values
            $user->set('usertype', $newUsertype);

            $user->groups[] = $newUsertype;

            $date = JFactory::getDate();
            $user->set('registerDate', $date->toSQL());

            // If user activation is turned on, we need to set the activation information
            $useractivation = $usersConfig->get('useractivation');
            $doUserActivation = false;
            if ($useractivation == '1' or $useractivation == '2') {
                $doUserActivation = true;
            }

            if ($doUserActivation) {
                jimport('joomla.user.helper');
                $user->set('activation', vRequest::getHash(JUserHelper::genRandomPassword()));
                $user->set('block', '1');
                //$user->set('lastvisitDate', '0000-00-00 00:00:00');
            }
        }

        $option = vRequest::getCmd('option');
        // If an exising superadmin gets a new group, make sure enough admins are left...
        if (!$new && $user->get('gid') != $gid && $gid == __SUPER_ADMIN_GID) {
            if ($this->getSuperAdminCount() <= 1) {
                vmError(tsmText::_('com_tsmart_USER_ERR_ONLYSUPERADMIN'));
                return false;
            }
        }

        if (isset($data['language'])) {
            $user->setParam('language', $data['language']);
        }

        // Save the JUser object
        if (!$user->save()) {
            $msg = tsmText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $user->getError());
            vmError($msg, $msg);
            return false;
        } else {
            $data['name'] = $user->get('name');
            $data['username'] = $user->get('username');
            $data['email'] = $user->get('email');
            $data['language'] = $user->get('language');
            $data['editor'] = $user->get('editor');
        }

        $newId = $user->get('id');
        $data['tsmart_user_id'] = $newId;    //We need this in that case, because data is bound to table later
        $this->setUserId($newId);

        //Save the VM user stuff
        if (!$this->saveUserData($data) || !self::storeAddress($data)) {
            vmError('com_tsmart_NOT_ABLE_TO_SAVE_USER_DATA');
            // 			vmError(vmText::_('com_tsmart_NOT_ABLE_TO_SAVE_USERINFO_DATA'));
        } else {


            if ($new) {
                $user->userInfo = $data;
                $password = '';
                if ($usersConfig->get('sendpassword', 1)) {
                    $password = $user->password_clear;
                }
                $this->sendRegistrationEmail($user, $password, $doUserActivation);
                if ($doUserActivation) {
                    vmInfo('com_tsmart_REG_COMPLETE_ACTIVATE');
                } else {
                    vmInfo('com_tsmart_REG_COMPLETE');
                    $user->set('activation', '');
                    $user->set('block', '0');
                    $user->set('guest', '0');
                }
            } else {
                vmInfo('com_tsmart_USER_DATA_STORED');
            }
        }

        //The extra check for isset vendor_name prevents storing of the vendor if there is no form (edit address cart)
        if ((int)$data['user_is_vendor'] == 1 and isset($data['vendor_currency'])) {
            vmdebug('vendor recognised ' . $data['tsmart_vendor_id']);
            if ($this->storeVendorData($data)) {
                if ($new) {
                    if ($doUserActivation) {
                        vmInfo('com_tsmart_REG_VENDOR_COMPLETE_ACTIVATE');
                    } else {
                        vmInfo('com_tsmart_REG_VENDOR_COMPLETE');
                    }
                } else {
                    vmInfo('com_tsmart_VENDOR_DATA_STORED');
                }
            }
        }

        return array('user' => $user, 'password' => $data['password'], 'message' => $message, 'newId' => $newId, 'success' => true);

    }

    public function activate($token)
    {
        $company_info = tsmConfig::get_company_info();
        $config = JFactory::getConfig();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $userParams = JComponentHelper::getParams('com_tsmart');
        if ($user->id) {
            $go_to=$app->input->getString('go_to','');
            if($go_to=='last_booking'){
                $app->redirect('index.php?option=com_tsmart&controller=orders&task=go_to_last_booking');
            }else
            {
                $app->redirect('index.php?option=com_tsmart&view=mypage');
            }
            return true;
        }
        $db = $this->getDbo();
        // Get the user id based on the token.
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('activation') . ' = ' . $db->quote($token))
            ->where($db->quoteName('block') . ' = ' . 1)
            ->where($db->quoteName('lastvisitDate') . ' = ' . $db->quote($db->getNullDate()));
        $db->setQuery($query);
        try {
            $userId = (int)$db->loadResult();
        } catch (RuntimeException $e) {

            $this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);

            return false;
        }
        // Get the user id based on the token.
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('activation') . ' = ' . $db->quote($token));
            $db->setQuery($query);
            try {
                $exists_activation = (int)$db->loadResult();
            } catch (RuntimeException $e) {

                $this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);

                return false;
            }

        // Check for a valid user id.
        if (!$userId && $exists_activation) {
            $go_to=$app->input->getString('go_to','');
            $app->redirect('index.php?option=com_tsmart&view=reset&token=' . $token.'&go_to='.$go_to);
            return false;
        }else if(!$userId && !$exists_activation)
        {
            $app->redirect('index.php?option=com_tsmart&view=user&layout=login');
            return false;
        }
        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        // Activate the user.
        $user = JFactory::getUser($userId);
        // Admin activation is on and user is verifying their email
        if($user->password!='')
        {
            $user->set('activation', '');
        }
        $user->set('block', '0');

        // Compile the user activated notification mail values.
        $data = $user->getProperties();
        $user->setParam('activate', 0);
        // Store the user object.
        if (!$user->save()) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_ACTIVATION_SAVE_FAILED', $user->getError()));

            return false;
        }

        return $user;
    }

    public function reset_password($data,$check_old_password){
        $token=$data['token'];
        $db = $this->getDbo();
        // Get the user id based on the token.
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('activation') . ' = ' . $db->quote($token));
        $db->setQuery($query);
        try {
            $object_user = $db->loadObject();
        } catch (RuntimeException $e) {

            $this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);

            return false;
        }
        if($object_user){
            $user=JFactory::getUser($object_user->id);
            $data['password2']=$data['password'];
            $data['activation']='';
            // Bind the data.
            if (!$user->bind($data))
            {
                $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));

                return false;
            }
            // Store the data.
            if (!$user->save())
            {
                $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));

                return false;
            }
            $current_user=JFactory::getUser();
            if(!$current_user->id)
            {
                $session=JFactory::getSession();
                $session->set('user',$user);
            }

            return true;

        }

    }
    public function create_new_user_from_contact_data($contact_data,$send_email=true)
    {

        $user = new JUser();
        require_once JPATH_ROOT . '/libraries/joomla/user/helper.php';
        $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
        $user->email = $contact_data->email_address;
        $user->username = $contact_data->email_address;
        $user->name = $contact_data->email_address;
        $user->groups[] = 2;
        $user->activation = $token;
        $user->block = 1;
        $company_info = tsmConfig::get_company_info();
        $user->save();
        if(!$send_email)
        {
            return true;
        }
        ob_start();
        ?>
        <html>

        <head>
            <meta http-equiv="Content-Language" content="en-us">
            <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
            <title>assddddfff</title>
            <style type="text/css">
                .ReadMsgBody {
                    width: 100%;
                }

                .ExternalClass {
                    width: 100%;
                }

                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
                    line-height: 100%;
                }

                body, table, td, a {
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                }

                table {
                    border-collapse: collapse !important;
                }

                table, td {
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                }

                img {
                    border: 0;
                    line-height: 100%;
                    outline: none;
                    text-decoration: none;
                    -ms-interpolation-mode: bicubic;
                }

                @media screen and (max-width: 480px) {
                    html {
                        -webkit-text-size-adjust: none;
                    }

                    *[class].mobile-width {
                        width: 100% !important;
                        padding-left: 10px;
                        padding-right: 10px;
                    }

                    *[class].mobile-width-nopad {
                        width: 100% !important;
                    }

                    *[class].stack {
                        display: block !important;
                        width: 100% !important;
                    }

                    *[class].hide {
                        display: none !important;
                    }

                    *[class].center, *[class].center img {
                        text-align: center !important;
                        margin: 0 auto;
                    }

                    *[class].scale img, *[class].editable_image img {
                        max-width: 100%;
                        height: auto;
                        margin: 0 auto;
                    }

                    *[class].addpad {
                        padding: 10px !important;
                    }

                    *[class].addpad-top {
                        padding-top: 30px !important;
                    }

                    *[class].sanpad {
                        padding: 0px !important;
                    }

                    *[class].sanborder {
                        border: none !important;
                    }
                }
            </style>
        </head>


        </head>

        <body style="margin:0; padding:0; width:100% !important; background-color:#ffffff; ">
        <div>
            <div class="mktEditable">
                <div
                    style="display: none; mso-hide: all; width: 0px; height: 0px; max-width: 0px; max-height: 0px; font-size: 0px; line-height: 0px;">
                    <br/></div>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="top" align="center" bgcolor="#E8E9E9" style="padding: 0px 10px;">
                            <table width="640" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0"
                                   class="mobile-width-nopad">
                                <tbody>
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top: 10px; padding-bottom: 10px;">
                                                    <table border="0" width="100%" cellspacing="0"
                                                           class="mobile-width-nopad">
                                                        <tbody>
                                                        <tr>
                                                            <td align="right">
                                                                <img border="0"
                                                                     src="<?php echo JUri::root() ?>/images/asian_logo.jpg"
                                                                     width="225" height="70"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>


                                            </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 10px;  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;">

                                                    <?php echo JText::sprintf('Hi %s', $contact_data->contact_name) ?>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td style="padding: 10px 10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 17px;">
                                                    <?php echo JText::sprintf('Welcome to "%s" !&nbsp; We have created an account under your name. To log in your account,&nbsp; please click the verify email address.', $company_info->company_name) ?>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td align="center" style="padding-top: 20px; padding-bottom: 20px;">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                        <tr>
                                                            <?php

                                                            ?>
                                                            <td bgcolor="#003366"
                                                                style="font-family:HelveticaNeueLight,HelveticaNeue-Light,'Helvetica Neue Light',HelveticaNeue,Helvetica,Arial,sans-serif;font-weight:300;font-stretch:normal;text-align:center;color:#fff;font-size:15px;background:#0079C1;;border-radius:7px!important; -moz-border-radius: 7px !important; -o-border-radius: 7px !important; -ms-border-radius: 7px !important;line-height:1.45em;padding:7px 15px 8px;margin:0 auto 16px;font-size:1em;padding-bottom:7px;">
                                                                <a href="<?php echo JUri::root() . '/index.php?option=com_tsmart&controller=user&task=activate&token=' . $token ?>"
                                                                   style="color:#ffffff; text-decoration:none; display:block; font-family:Arial,sans-serif; font-weight:bold; font-size:15px; line-height:15px;text-transform: uppercase"
                                                                   target="_blank"><?php echo JText::_('Verify your e mail address') ?> </a>
                                                            </td>

                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;</td>

                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 10px; text-align:left; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #000000; font-size: 17px;">
                                                    <?php echo JText::_('Kind regards') ?>, <br>
                                                    <?php echo JText::sprintf('"%s" Technical Support', $company_info->company_name) ?>
                                                </td>


                                            </tr>
                                            <tr>
                                                <td bgcolor="#E8E9E9">

                                                    &nbsp;</td>

                                            </tr>
                                            <td>
                                                <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                                       class="mobile-width-nopad">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;text-transform: uppercase"><?php echo JText::_('No booking fees') ?></td>
                                                        <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;text-transform: uppercase"><?php echo JText::_('Secure payment') ?> </td>
                                                        <td style="padding:10px; text-align:justify; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #304957; font-size: 15px;text-transform: uppercase"><?php echo JText::_('24 /7 support') ?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#003333" height="35px">&nbsp;</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                </tbody>
                            </table>
                    </tbody>
                </table>
            </div>
        </div>
        </body>

        </html>


        <?php
        $email_content = ob_get_clean();
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );

        $mailer->setSender($sender);
        $user = JFactory::getUser();
        $recipient = $user->email;

        $mailer->addRecipient($recipient);

        $recipient = array($contact_data->email_address, 'asianventuretours@gmail.com', 'hong@asianventure.com', 'cuong@asianventure.com');

        $mailer->addRecipient($recipient);
        $body = $email_content;
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject(JText::sprintf('%s-Verification email', $company_info->company_name));
        $mailer->setBody($body);

        $send = $mailer->Send();

        if ($send !== true) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This function is NOT for anonymous. Anonymous just get the information directly sent by email.
     * This function saves the vm Userdata for registered JUsers.
     * TODO, setting of shoppergroup isnt done
     *
     * TODO No reason not to use this function for new users, but it requires a Joomla <user> plugin
     * that gets fired by the onAfterStoreUser. I'll built that (OvE)
     *
     * Notice:
     * As long we do not have the silent registration, an anonymous does not get registered. It is enough to send the tsmart_order_id
     * with the email. The order is saved with all information in an extra table, so there is
     * no need for a silent registration. We may think about if we actually need/want the feature silent registration
     * The information of anonymous is stored in the order table and has nothing todo with the usermodel!
     *
     * @author Max Milbers
     * @author Oscar van Eijk
     * return boolean
     */
    public function saveUserData(&$data, $trigger = true)
    {

        if (empty($this->_id)) {
            echo 'This is a notice for developers, you used this function for an anonymous user, but it is only designed for already registered ones';
            vmError('This is a notice for developers, you used this function for an anonymous user, but it is only designed for already registered ones');
            return false;
        }

        $noError = true;

        $usertable = $this->getTable('vmusers');
        $alreadyStoredUserData = $usertable->load($this->_id);

        if (!vmAccess::manager('core')) {
            unset($data['tsmart_vendor_id']);
            unset($data['user_is_vendor']);
        } else {
            if (!isset($data['user_is_vendor']) and !empty($alreadyStoredUserData->user_is_vendor)) {
                $data['user_is_vendor'] = $alreadyStoredUserData->user_is_vendor;
            }
            if (!isset($data['tsmart_vendor_id']) and !empty($alreadyStoredUserData->tsmart_vendor_id)) {
                $data['tsmart_vendor_id'] = $alreadyStoredUserData->tsmart_vendor_id;
            }
        }

        unset($data['customer_number']);
        if (empty($alreadyStoredUserData->customer_number)) {
            //if(!class_exists('vmUserPlugin')) require(VMPATH_SITE.DS.'helpers'.DS.'vmuserplugin.php');
            ///if(!$returnValues){
            $data['customer_number'] = strtoupper(substr($data['username'], 0, 2)) . substr(md5($data['username']), 0, 9);
            //We set this data so that vmshopper plugin know if they should set the customer nummer
            $data['customer_number_bycore'] = 1;
            //}
        } else {
            if (!vmAccess::manager()) {
                $data['customer_number'] = $alreadyStoredUserData->customer_number;
            }
        }

        if ($trigger) {
            JPluginHelper::importPlugin('vmshopper');
            $dispatcher = JDispatcher::getInstance();

            $plg_datas = $dispatcher->trigger('plgVmOnUserStore', array(&$data));
            foreach ($plg_datas as $plg_data) {
                // 			$data = array_merge($plg_data,$data);
            }
        }

        $res = $usertable->bindChecknStore($data);
        if (!$res) {
            vmError('storing user adress data');
            $noError = false;
        }

        if (vmAccess::manager()) {
            $shoppergroupmodel = tmsModel::getModel('ShopperGroup');
            if (empty($this->_defaultShopperGroup)) {
                $this->_defaultShopperGroup = $shoppergroupmodel->getDefault(0);
            }

            if (empty($data['tsmart_shoppergroup_id']) or $data['tsmart_shoppergroup_id'] == $this->_defaultShopperGroup->tsmart_shoppergroup_id) {
                $data['tsmart_shoppergroup_id'] = array();
            }

            // Bind the form fields to the table
            if (!isset($data['tsmart_shoppergroup_id'])) {
                $data['tsmart_shoppergroup_id'] = array();
            }
            $shoppergroupData = array('tsmart_user_id' => $this->_id, 'tsmart_shoppergroup_id' => $data['tsmart_shoppergroup_id']);
            $user_shoppergroups_table = $this->getTable('vmuser_shoppergroups');
            $res = $user_shoppergroups_table->bindChecknStore($shoppergroupData);
            if (!$res) {
                vmError('Set shoppergroup error');
                $noError = false;
            }

        }

        if ($trigger) {
            $plg_datas = $dispatcher->trigger('plgVmAfterUserStore', array($data));
            foreach ($plg_datas as $plg_data) {
                $data = array_merge($plg_data);
            }
        }

        if (!empty($data['vendorId']) and $data['vendorId'] > 1) {
            //$vUserD = array('tsmart_user_id' => $data['tsmart_user_id'],'tsmart_vendor_id' => $data['vendorId']);
            $vUser = $this->getTable('vendor_users');
            $vUser->load((int)$data['vendorId']);
            if (!$vUser->tsmart_user_id) {
                $vUser->bind(array('tsmart_vendor_id' => (int)$data['vendorId'], 'tsmart_user_id' => $data['tsmart_user_id']));
            } else if (!in_array((int)$data['tsmart_user_id'], $vUser->tsmart_user_id)) {
                $arr = array_merge($vUser->tsmart_user_id, (array)$data['tsmart_user_id']);
                $vUser->bind(array('tsmart_vendor_id' => (int)$data['vendorId'], 'tsmart_user_id' => $arr));
            }
            $vUser->store();

        }

        return $noError;
    }

    public function storeVendorData($data)
    {

        if ($data['user_is_vendor']) {

            $vendorModel = tmsModel::getModel('vendor');

            //TODO Attention this is set now to tsmart_vendor_id=1 in single vendor mode, because using a vendor with different id then 1 is not completly supported and can lead to bugs
            //So we disable the possibility to store vendors not with tsmart_vendor_id = 1
            if (tsmConfig::get('multix', 'none') == 'none') {
                $data['tsmart_vendor_id'] = 1;
                vmdebug('no multivendor, set tsmart_vendor_id = 1');
            }
            $vendorModel->setId($data['tsmart_vendor_id']);

            if (!$vendorModel->store($data)) {
                vmdebug('Error storing vendor', $vendorModel);
                return false;
            }
        }

        return true;
    }

    /**
     * Take a data array and save any address info found in the array.
     *
     * @author unknown, oscar, max milbers
     * @param array $data (Posted) user data
     * @param sting $_table Table name to write to, null (default) not to write to the database
     * @param boolean $_cart Attention, this was deleted, the address to cart is now done in the controller (True to write to the session (cart))
     * @return boolean True if the save was successful, false otherwise.
     */
    function storeAddress(&$data)
    {

        $user = JFactory::getUser();

        $userinfo = $this->getTable('userinfos');

        $manager = vmAccess::manager();
        if ($data['address_type'] == 'BT') {

            if (isset($data['tsmart_userinfo_id']) and $data['tsmart_userinfo_id'] != 0) {

                if (!$manager) {

                    $userinfo->load($data['tsmart_userinfo_id']);

                    if ($userinfo->tsmart_user_id != $user->id) {
                        vmError('Hacking attempt as admin?', 'Hacking attempt storeAddress');
                        return false;
                    }
                }
            } else {

                if (!$manager) {
                    $userId = $user->id;
                } else {
                    $userId = (int)$data['tsmart_user_id'];
                }
                $q = 'SELECT `tsmart_userinfo_id` FROM #__tsmart_userinfos
				WHERE `tsmart_user_id` = ' . $userId . '
				AND `address_type` = "BT"';

                $db = JFactory::getDbo();
                $db->setQuery($q);
                $total = $db->loadColumn();

                if (count($total) > 0) {
                    $data['tsmart_userinfo_id'] = (int)$total[0];
                } else {
                    $data['tsmart_userinfo_id'] = 0;//md5(uniqid($this->tsmart_user_id));
                }
                $userinfo->load($data['tsmart_userinfo_id']);
                //unset($data['tsmart_userinfo_id']);
            }
            $data = (array)$data;
            if (!$this->validateUserData($data, 'BT')) {
                return false;
            }

            $userInfoData = self::_prepareUserFields($data, 'BT', $userinfo);
            //vmdebug('model user storeAddress',$data);
            $userinfo->bindChecknStore($userInfoData);
        }

        // Check for fields with the the 'shipto_' prefix; that means a (new) shipto address.
        if ($data['address_type'] == 'ST' or isset($data['shipto_address_type_name'])) {
            $dataST = array();
            $_pattern = '/^shipto_/';

            foreach ($data as $_k => $_v) {
                if (preg_match($_pattern, $_k)) {
                    $_new = preg_replace($_pattern, '', $_k);
                    $dataST[$_new] = $_v;
                }
            }

            $userinfo = $this->getTable('userinfos');
            if (isset($dataST['tsmart_userinfo_id']) and $dataST['tsmart_userinfo_id'] != 0) {
                $dataST['tsmart_userinfo_id'] = (int)$dataST['tsmart_userinfo_id'];

                if (!$manager) {

                    $userinfo->load($dataST['tsmart_userinfo_id']);

                    $user = JFactory::getUser();
                    if ($userinfo->tsmart_user_id != $user->id) {
                        vmError('Hacking attempt as admin?', 'Hacking attempt store address');
                        return false;
                    }
                }
            }

            if (empty($userinfo->tsmart_user_id)) {
                if (!$manager) {
                    $dataST['tsmart_user_id'] = $user->id;
                } else {
                    if (isset($data['tsmart_user_id'])) {
                        $dataST['tsmart_user_id'] = (int)$data['tsmart_user_id'];
                    } else {
                        //Disadvantage is that admins should not change the ST address in the FE (what should never happen anyway.)
                        $dataST['tsmart_user_id'] = $user->id;
                    }
                }
            }

            if (!is_array($dataST)) $dataST = (array)$dataST;
            if (!$this->validateUserData($dataST, 'ST')) {
                return false;
            }
            $dataST['address_type'] = 'ST';
            $userfielddata = self::_prepareUserFields($dataST, 'ST', $userinfo);

            $userinfo->bindChecknStore($userfielddata);

            $app = JFactory::getApplication();
            if ($app->isSite()) {
                if (!class_exists('tsmartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
                $cart = tsmartCart::getCart();
                if ($cart) {
                    $cart->selected_shipto = $userinfo->tsmart_userinfo_id;
                }
            }
        }


        return $userinfo->tsmart_userinfo_id;
    }

    /**
     * Test userdata if valid
     *
     * @author Max Milbers
     * @param String if BT or ST
     * @param Object If given, an object with data address data that must be formatted to an array
     * @return redirectMsg, if there is a redirectMsg, the redirect should be executed after
     */
    public function validateUserData(&$data, $type = 'BT', $showInfo = false)
    {

        if (!class_exists('tsmartModelUserfields'))
            require(VMPATH_ADMIN . DS . 'models' . DS . 'userfields.php');
        $userFieldsModel = tmsModel::getModel('userfields');

        if ($type == 'BT') {
            $fieldtype = 'account';
        } else if ($type == 'cartfields') {
            $fieldtype = 'cart';
        } else {
            $fieldtype = 'shipment';
        }

        $neededFields = $userFieldsModel->getUserFields(
            $fieldtype
            , array('required' => true, 'delimiters' => true, 'captcha' => true, 'system' => false)
            , array('delimiter_userinfo', 'name', 'username', 'password', 'password2', 'address_type_name', 'address_type', 'user_is_vendor', 'agreed'));

        $i = 0;

        $return = true;

        $required = 0;
        $missingFields = array();
        $lang = JFactory::getLanguage();
        foreach ($neededFields as $field) {

            //This is a special test for the tsmart_state_id. There is the speciality that the tsmart_state_id could be 0 but is valid.
            if ($field->name == 'tsmart_state_id') {
                if (!class_exists('tsmartModelState')) require(VMPATH_ADMIN . DS . 'models' . DS . 'state.php');
                if (!empty($data['tsmart_country_id'])) {
                    if (!isset($data['tsmart_state_id'])) $data['tsmart_state_id'] = 0;

                    if (!$msg = tsmartModelState::testStateCountry($data['tsmart_country_id'], $data['tsmart_state_id'])) {
                        //The state is invalid, so we set the state 0 here.
                        $data['tsmart_state_id'] = 0;
                        vmdebug('State was not fitting to country, set tsmart_state_id to 0');
                    } else if (empty($data['tsmart_state_id'])) {
                        vmdebug('tsmart_state_id is empty, but valid (country has not states, set to unrequired');
                        $field->required = false;
                    } else {
                        //vmdebug('validateUserData my country '.$data['tsmart_country_id'].' my state '.$data['tsmart_state_id']);
                    }
                }
            }

            if ($field->required) {
                $required++;
                if (empty($data[$field->name])) {
                    if ($lang->hasKey('com_tsmart_MISSING_' . $field->name)) {
                        $missingFields[] = tsmText::_('com_tsmart_MISSING_' . $field->name);
                    } else {
                        $missingFields[] = tsmText::sprintf('com_tsmart_MISSING_VALUE_FOR_FIELD', $field->title);
                    }

                    $i++;
                    $return = false;
                } else if ($data[$field->name] == $field->default) {
                    $i++;
                } else {

                }
            }
        }

        if (empty($required)) {
            vmdebug('Nothing to require');
            $return = true;
        } else if ($i == $required) {
            $return = -1;
        }
        //vmdebug('my i '.$i.' my data size $showInfo: '.(int)$showInfo.' required: '.(int)$required,$return);

        //if( ($required>2 and ($i+1)<$required) or ($required<=2 and !$return) or $showInfo){
        if ($showInfo or ($required > 2 and $i < ($required - 1)) or ($required < 3 and !$return)) {
            foreach ($missingFields as $fieldname) {
                vmInfo($fieldname);
            }
        }
        return $return;
    }


    function _prepareUserFields(&$data, $type, $userinfo = 0)
    {
        if (!class_exists('tsmartModelUserfields')) require(VMPATH_ADMIN . DS . 'models' . DS . 'userfields.php');
        $userFieldsModel = tmsModel::getModel('userfields');

        if ($type == 'ST') {
            $prepareUserFields = $userFieldsModel->getUserFields(
                'shipment'
                , array() // Default toggles
            );
        } else { // BT
            // The user is not logged in (anonymous), so we need tome extra fields
            $prepareUserFields = $userFieldsModel->getUserFields(
                'account'
                , array() // Default toggles
                , array('delimiter_userinfo', 'name', 'username', 'password', 'password2', 'user_is_vendor') // Skips
            );

        }

        $user = JFactory::getUser();
        $manager = vmAccess::manager();

        // Format the data
        foreach ($prepareUserFields as $fld) {
            if (empty($data[$fld->name])) $data[$fld->name] = '';

            if (!$manager and $fld->readonly) {
                $fldName = $fld->name;
                unset($data[$fldName]);
                if ($userinfo !== 0) {
                    if (property_exists($userinfo, $fldName)) {
                        $data[$fldName] = $userinfo->$fldName;
                    } else {
                        vmError('Your tables seem to be broken, you have fields in your form which have no corresponding field in the db');
                    }
                }
            } else {
                $data[$fld->name] = $userFieldsModel->prepareFieldDataSave($fld, $data);
            }
        }

        return $data;
    }

    function getBTuserinfo_id($id = 0)
    {
        if (empty($db)) $db = JFactory::getDBO();

        if ($id == 0) {
            $id = $this->_id;
            vmdebug('getBTuserinfo_id is ' . $this->_id);
        }

        $q = 'SELECT `tsmart_userinfo_id` FROM `#__tsmart_userinfos` WHERE `tsmart_user_id` = "' . (int)$id . '" AND `address_type`="BT" ';
        $db->setQuery($q);
        return $db->loadResult();
    }

    /**
     *
     * @author Max Milbers
     */
    function getUserInfoInUserFields($layoutName, $type, $uid, $cart = true, $isVendor = false)
    {

        // 		if(!class_exists('tsmartModelUserfields')) require(VMPATH_ADMIN.DS.'models'.DS.'userfields.php' );
        // 		$userFieldsModel = new tsmartModelUserfields();
        $userFieldsModel = tmsModel::getModel('userfields');
        $prepareUserFields = $userFieldsModel->getUserFieldsFor($layoutName, $type);

        if ($type == 'ST') {
            $preFix = 'shipto_';
        } else {
            $preFix = '';
        }
        /*
         * JUser  or $this->_id is the logged user
         */

        if (!empty($this->_data->JUser)) {
            $JUser = $this->_data->JUser;
        } else {
            $JUser = JUser::getInstance($this->_id);
        }

        $data = null;
        $userFields = array();
        if (!empty($uid)) {

            $dataT = $this->getTable('userinfos');
            $data = $dataT->load($uid);

            if ($data->tsmart_user_id !== 0 and !$isVendor) {

                $user = JFactory::getUser();
                if (!vmAccess::manager()) {
                    if ($data->tsmart_user_id != $this->_id) {
                        vmError('Blocked attempt loading userinfo, you got logged');
                        echo 'Hacking attempt loading userinfo, you got logged';
                        return false;
                    }
                }
            }

            if ($data->address_type != 'ST') {
                $BTuid = $uid;

                $data->name = $JUser->name;
                $data->email = $JUser->email;
                $data->username = $JUser->username;
                $data->address_type = 'BT';

            }
        } else {
            vmdebug('getUserInfoInUserFields case empty $uid');
            //New Address is filled here with the data of the cart (we are in the userview)
            if ($cart) {

                if (!class_exists('tsmartCart'))
                    require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
                $cart = tsmartCart::getCart();
                $adType = $type . 'address';

                if (empty($cart->$adType)) {
                    $data = $cart->$type;
                    if (empty($data)) $data = array();

                    if ($JUser) {
                        if (empty($data['name'])) {
                            $data['name'] = $JUser->name;
                        }
                        if (empty($data['email'])) {
                            $data['email'] = $JUser->email;
                        }
                        if (empty($data['username'])) {
                            $data['username'] = $JUser->username;
                        }
                        if (empty($data['tsmart_user_id'])) {
                            $data['tsmart_user_id'] = $JUser->id;
                        }
                    }
                    $data = (object)$data;
                }

            } else {

                if ($JUser) {
                    if (empty($data['name'])) {
                        $data['name'] = $JUser->name;
                    }
                    if (empty($data['email'])) {
                        $data['email'] = $JUser->email;
                    }
                    if (empty($data['username'])) {
                        $data['username'] = $JUser->username;
                    }
                    if (empty($data['tsmart_user_id'])) {
                        $data['tsmart_user_id'] = $JUser->id;
                    }
                    $data = (object)$data;
                }
            }
        }

        if (empty($data)) {
            vmdebug('getUserInfoInUserFields $data empty', $uid, $data);
            $cart = tsmartCart::getCart();
            $data = $cart->BT;
        }

        $userFields[$uid] = $userFieldsModel->getUserFieldsFilled(
            $prepareUserFields
            , $data
            , $preFix
        );

        return $userFields;
    }


    /**
     * This stores the userdata given in userfields
     *
     * @author Max Milbers
     */
    function storeUserDataByFields($data, $type, $toggles, $skips)
    {

        if (!class_exists('tsmartModelUserfields')) require(VMPATH_ADMIN . DS . 'models' . DS . 'userfields.php');
        $userFieldsModel = tmsModel::getModel('userfields');

        $prepareUserFields = $userFieldsModel->getUserFields(
            $type,
            $toggles,
            $skips
        );

        // Format the data
        foreach ($prepareUserFields as $_fld) {
            if (empty($data[$_fld->name])) $data[$_fld->name] = '';
            $data[$_fld->name] = $userFieldsModel->prepareFieldDataSave($_fld, $data);
        }

        $this->store($data);

        return true;

    }

    /**
     * This uses the shopFunctionsF::renderAndSendVmMail function, which uses a controller and task to render the content
     * and sents it then.
     *
     *
     * @author Oscar van Eijk
     * @author Max Milbers
     * @author Christopher Roussel
     * @author Valérie Isaksen
     */
    private function sendRegistrationEmail($user, $password, $doUserActivation)
    {
        if (!class_exists('shopFunctionsF')) require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
        $vars = array('user' => $user);

        // Send registration confirmation mail
        $password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
        $vars['password'] = $password;

        if ($doUserActivation) {
            jimport('joomla.user.helper');
            $activationLink = 'index.php?option=com_users&task=registration.activate&token=' . $user->get('activation');

            $vars['activationLink'] = $activationLink;
        }
        $vars['doVendor'] = true;
        // public function renderMail ($viewName, $recipient, $vars=array(),$controllerName = null)
        shopFunctionsF::renderMail('user', $user->get('email'), $vars);


    }

    /**
     * Delete all record ids selected
     *
     * @return boolean True is the remove was successful, false otherwise.
     */
    function remove($userIds)
    {

        if (vmAccess::manager('user')) {

            $userInfo = $this->getTable('userinfos');
            $vm_shoppergroup_xref = $this->getTable('vmuser_shoppergroups');
            $vmusers = $this->getTable('vmusers');
            $_status = true;
            foreach ($userIds as $userId) {

                $_JUser = JUser::getInstance($userId);

                if ($this->getSuperAdminCount() <= 1) {
                    // Prevent deletion of the only Super Admin
                    //$_u = JUser::getInstance($userId);
                    if ($_JUser->get('gid') == __SUPER_ADMIN_GID) {
                        vmError(tsmText::_('com_tsmart_USER_ERR_LASTSUPERADMIN'));
                        $_status = false;
                        continue;
                    }
                }

                if (!$userInfo->delete($userId)) {
                    return false;
                }

                if (!$vm_shoppergroup_xref->delete($userId)) {
                    $_status = false;
                    continue;
                }

                if (!$vmusers->delete($userId)) {
                    $_status = false;
                    continue;
                }

                if (!$_JUser->delete()) {
                    vmError($_JUser->getError());
                    $_status = false;
                    continue;
                }
            }
        }

        return $_status;
    }

    function removeAddress($tsmart_userinfo_id)
    {

        $db = JFactory::getDBO();

        if (isset($tsmart_userinfo_id) and $this->_id != 0) {
            //$userModel -> deleteAddressST();
            $q = 'DELETE FROM #__tsmart_userinfos  WHERE tsmart_user_id="' . $this->_id . '" AND tsmart_userinfo_id="' . (int)$tsmart_userinfo_id . '"';
            $db->setQuery($q);
            if ($db->execute()) {
                vmInfo('Address has been successfully deleted.');
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve a list of users from the database.
     *
     * @author Max Milbers
     * @return object List of user objects
     */
    function getUserList()
    {

        //$select = ' * ';
        //$joinedTables = ' FROM #__users AS ju LEFT JOIN #__tsmart_vmusers AS vmu ON ju.id = vmu.tsmart_user_id';
        $search = vRequest::getString('search', false);
        $tableToUse = vRequest::getString('searchTable', 'juser');

        $where = array();
        if ($search) {
            $where = ' WHERE ';
            $db = JFactory::getDbo();
            $searchArray = array('ju.name', 'ju.username', 'ju.email', 'shopper_group_name');    // removed ,'usertype' should be handled by extra dropdown
            $userFieldsValid = array();
            if ($tableToUse != 'juser') {

                if (!class_exists('TableUserinfos')) require(VMPATH_ADMIN . DS . 'tables' . DS . 'userinfos.php');

                $userfieldTable = new TableUserinfos($db);
                $userfieldFields = get_object_vars($userfieldTable);
                $userFieldSearchArray = array('company', 'first_name', 'last_name', 'address_1', 'zip', 'city', 'phone_1');
                //We must validate if the userfields actually exists, they could be removed

                foreach ($userFieldSearchArray as $ufield) {
                    if (array_key_exists($ufield, $userfieldFields)) {
                        $userFieldsValid[] = $ufield;
                    }
                }
                $searchArray = array_merge($userFieldsValid, $searchArray);
            }

            $search = str_replace(' ', '%', $db->escape($search, true));
            foreach ($searchArray as $field) {

                $whereOr[] = ' ' . $field . ' LIKE "%' . $search . '%" ';
            }
            //$where = substr($where,0,-3);
        }

        $select = ' ju.id AS id
			, ju.name AS name
			, ju.username AS username
			, ju.email AS email
			, IFNULL(vmu.user_is_vendor,"0") AS is_vendor
			, IFNULL(sg.shopper_group_name, "") AS shopper_group_name ';

        if ($search) {
            if ($tableToUse != 'juser') {
                $select .= ' , ui.name as uiname ';
            }

            foreach ($userFieldsValid as $ufield) {
                $select .= ' , ' . $ufield;
            }
        }

        $joinedTables = ' FROM #__users AS ju
			LEFT JOIN #__tsmart_vmusers AS vmu ON ju.id = vmu.tsmart_user_id
			LEFT JOIN #__tsmart_vmuser_shoppergroups AS vx ON ju.id = vx.tsmart_user_id
			LEFT JOIN #__tsmart_shoppergroups AS sg ON vx.tsmart_shoppergroup_id = sg.tsmart_shoppergroup_id ';
        if ($search and $tableToUse != 'juser') {
            $joinedTables .= ' LEFT JOIN #__tsmart_userinfos AS ui ON ui.tsmart_user_id = vmu.tsmart_user_id';
        }

        $whereAnd = array();
        if (tsmConfig::get('multixcart', 0) == 'byvendor') {
            $superVendor = vmAccess::isSuperVendor();
            if ($superVendor > 1) {
                $joinedTables .= ' LEFT JOIN #__tsmart_vendor_users AS vu ON ju.id = vmu.tsmart_user_id';
                $whereAnd[] = ' vu.tsmart_vendor_id = ' . $superVendor . ' ';
            }
        }

        $where = '';
        $whereStr = ' WHERE ';
        if (!empty($whereOr)) {
            $where = $whereStr . implode(' OR ', $whereOr);
            $whereStr = 'AND';
        }
        if (!empty($whereAnd)) {
            $where .= $whereStr . ' (' . implode(' OR ', $whereAnd) . ')';
        }
        return $this->_data = $this->exeSortSearchListQuery(0, $select, $joinedTables, $where, ' GROUP BY ju.id', $this->_getOrdering());

    }

    public function getSwitchUserList($superVendor = null, $adminID = false)
    {

        if (!isset($superVendor)) $superVendor = vmAccess::isSuperVendor();

        $result = false;
        if ($superVendor) {
            $db = JFactory::getDbo();
            $search = vRequest::getUword('usersearch', '');
            if (!empty($search)) {
                $search = ' WHERE (`name` LIKE "%' . $search . '%" OR `username` LIKE "%' . $search . '%" OR `customer_number` LIKE "%' . $search . '%")';
            } else if ($superVendor != 1) {
                $search = ' WHERE vu.tsmart_vendor_id = ' . $superVendor . ' ';
            }

            $q = 'SELECT ju.`id`,`name`,`username` FROM `#__users` as ju';

            if ($superVendor != 1 or !empty($search)) {
                $q .= ' LEFT JOIN #__tsmart_vmusers AS vmu ON vmu.tsmart_user_id = ju.id';
                if ($superVendor != 1) {
                    $q .= ' LEFT JOIN #__tsmart_vendor_users AS vu ON vu.tsmart_user_id = ju.id';
                    $search .= ' AND ( vmu.user_is_vendor = 0 OR (vmu.tsmart_vendor_id) IS NULL)';
                }
            }
            $current = JFactory::getUser();
            $hiddenUserID = $adminID ? $adminID : $current->id;
            if (!empty($search)) {
                $search .= ' AND ju.id!= "' . $hiddenUserID . '" ';
            } else {
                $q .= ' WHERE ju.id!= "' . $hiddenUserID . '" ';
            }


            $q .= $search . ' ORDER BY `name` LIMIT 0,10000';
            $db->setQuery($q);
            $result = $db->loadObjectList();

            if ($result) {
                foreach ($result as $k => $user) {
                    $result[$k]->displayedName = $user->name . '&nbsp;&nbsp;( ' . $user->username . ' )';
                }
            } else {
                $result = array();
            }

            if ($adminID) {

                $user = JFactory::getUser($adminID);
                if ($current->id != $user->id) {
                    $toAdd = new stdClass();
                    $toAdd->id = $user->id;
                    $toAdd->name = $user->name;
                    $toAdd->username = $user->username;
                    $toAdd->displayedName = tsmText::sprintf('com_tsmart_RETURN_TO', $user->name, $user->username);
                    array_unshift($result, $toAdd);
                }
            }

            $toAdd = new stdClass();
            $toAdd->id = 0;
            $toAdd->name = '';
            $toAdd->username = '';
            $toAdd->displayedName = '-' . tsmText::_('com_tsmart_REGISTER') . '-';
            array_unshift($result, $toAdd);
        }

        return $result;
    }

    /**
     * If a filter was set, get the SQL WHERE clase
     *
     * @return string text to add to the SQL statement
     */
    function _getFilter()
    {
        if ($search = vRequest::getString('search', false)) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            //$search = $db->Quote($search, false);
            $searchArray = array('name', 'username', 'email', 'usertype', 'shopper_group_name');

            $where = ' WHERE ';
            foreach ($searchArray as $field) {
                $where .= ' `' . $field . '` LIKE ' . $search . ' OR ';
            }
            $where = substr($where, 0, -3);
            return ($where);
        }
        return ('');
    }

    /**
     * Retrieve a single address for a user
     *
     * @param $_uid int User ID
     * @param $_tsmart_userinfo_id string Optional User Info ID
     * @param $_type string, addess- type, ST (ShipTo, default) or BT (BillTo). Empty string to ignore
     */
    function getUserAddressList($_uid = 0, $_type = 'ST', $_tsmart_userinfo_id = -1)
    {

        //Todo, add perms, allow admin to see 0 entries.
        if ($_uid == 0 and $this->_id == 0) {
            return array();
        }
        $_q = 'SELECT * FROM #__tsmart_userinfos  WHERE tsmart_user_id="' . (($_uid == 0) ? $this->_id : (int)$_uid) . '"';
        if ($_tsmart_userinfo_id !== -1) {
            $_q .= ' AND tsmart_userinfo_id="' . (int)$_tsmart_userinfo_id . '"';
        } else {
            if ($_type !== '') {
                $_q .= ' AND address_type="' . $_type . '"';
            }
        }
        //vmdebug('getUserAddressList execute '.$_q);
        return ($this->_getList($_q));
    }

    /**
     * Retrieves the Customer Number of the user specified by ID
     *
     * @param int $_id User ID
     * @return string Customer Number
     */
    private $customer_number = 0;

    public function getCustomerNumberById()
    {
        if ($this->customer_number === 0) {
            $_q = "SELECT `customer_number` FROM `#__tsmart_vmusers` "
                . "WHERE `tsmart_user_id`='" . $this->_id . "' ";
            $_r = $this->_getList($_q);

            if (!empty($_r[0])) {
                $this->customer_number = $_r[0]->customer_number;
            } else {
                $this->customer_number = false;
            }
        }

        return $this->customer_number;
    }

    /**
     * Get the number of active Super Admins
     *
     * @return integer
     */
    function getSuperAdminCount()
    {

        $db = JFactory::getDBO();
        if (JVM_VERSION > 1) {
            $q = ' SELECT COUNT(us.id)  FROM #__users as us ' .
                ' INNER JOIN #__user_usergroup_map as um ON us.id = um.user_id ' .
                ' INNER JOIN #__usergroups as ug ON um.group_id = ug.id ' .
                ' WHERE ug.id = "8" AND block = "0" ';
        } else {
            $q = 'SELECT COUNT(id) FROM #__users'
                . ' WHERE gid = ' . __SUPER_ADMIN_GID . ' AND block = 0';
        }

        $db->setQuery($q);
        return ($db->loadResult());
    }


    /**
     * Return a list of Joomla ACL groups.
     *
     * The returned object list includes a group anme and a group name with spaces
     * prepended to the name for displaying an indented tree.
     *
     * @author RickG
     * @return ObjectList List of acl group objects.
     */
    function getAclGroupIndentedTree()
    {

        //TODO check this out

        $name = 'title';
        $as = '`';
        $table = '#__usergroups';
        $and = '';

        //Ugly thing, produces Select_full_join
        $query = 'SELECT `node`.`' . $name . $as . ', CONCAT(REPEAT("&nbsp;&nbsp;&nbsp;", (COUNT(`parent`.`' . $name . '`) - 1)), `node`.`' . $name . '`) AS `text` ';
        $query .= 'FROM `' . $table . '` AS node, `' . $table . '` AS parent ';
        $query .= 'WHERE `node`.`lft` BETWEEN `parent`.`lft` AND `parent`.`rgt` ';
        $query .= $and;
        $query .= 'GROUP BY `node`.`' . $name . '` ';
        $query .= ' ORDER BY `node`.`lft`';

        $db = JFactory::getDBO();
        $db->setQuery($query);
        //$app = JFactory::getApplication();
        //$app -> enqueueMessage($db->getQuery());
        $objlist = $db->loadObjectList();
        // 		vmdebug('getAclGroupIndentedTree',$objlist);
        return $objlist;
    }
}


//No Closing tag
