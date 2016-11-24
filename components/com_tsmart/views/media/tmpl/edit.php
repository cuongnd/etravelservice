<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start','com_tsmart_PRODUCT_MEDIA');

echo'<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data">';
echo '<fieldset>';

$this->media->addHidden('view','media');
$this->media->addHidden('task','');
$this->media->addHidden(JSession::getFormToken(),1);
$this->media->addHidden('file_type',$this->media->file_type);

$tsmart_product_id = vRequest::getInt('tsmart_product_id', '');
if(!empty($tsmart_product_id)) $this->media->addHidden('tsmart_product_id',$tsmart_product_id);

$tsmart_category_id = vRequest::getInt('tsmart_category_id', '');
if(!empty($tsmart_category_id)) $this->media->addHidden('tsmart_category_id',$tsmart_category_id);

echo $this->media->displayFileHandler();
echo '</fieldset>';
echo '</form>';

AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea();