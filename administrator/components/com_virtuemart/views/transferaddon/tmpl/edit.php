<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_virtuemart/assets/less/view_transferaddon_edit.less');
AdminUIHelper::startAdminArea($this);

AdminUIHelper::imitateTabs('start', "transferaddon");
?>
    <div class="view-transferaddon-edit">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


            <div class="col50">
                <fieldset>
                    <legend><?php echo vmText::_('Current transferaddon'); ?></legend>
                    <div class="row-fluid">
                        <div class="span7">
                            <?php echo VmHTML::row_control('input', 'service name', 'title', $this->item->title, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'description', 'description', $this->item->description, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('range_of_date', 'vail', 'vail', $this->item->vail, '', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('list_option', 'Price type', 'price_type', array('group_price', 'flat_price'), $this->item->price_type, 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', 'Flat price', 'flat_price', $this->item->flat_price, 'class="required"'); ?>
                            <?php
                            $item = new stdClass();
                            $item->name='senior_price';
                            $item->text='senior';
                            $item->value=$this->item->senior_price;
                            $list_group_price[]=$item;

                            $item = new stdClass();
                            $item->name='adult_price';
                            $item->text='adult';
                            $item->value=$this->item->adult_price;
                            $list_group_price[]=$item;

                            $item = new stdClass();
                            $item->name='teen_price';
                            $item->text='teen';
                            $item->value=$this->item->teen_price;
                            $list_group_price[]=$item;

                            $item = new stdClass();
                            $item->name='infant_price';
                            $item->text='infant';
                            $item->value=$this->item->infant_price;
                            $list_group_price[]=$item;

                            $item = new stdClass();
                            $item->name='children1_price';
                            $item->text='children 1';
                            $item->value=$this->item->children1_price;
                            $list_group_price[]=$item;

                            $item = new stdClass();
                            $item->name='children2_price';
                            $item->text='children 2';
                            $item->value=$this->item->children2_price;
                            $list_group_price[]=$item;

                            ?>
                            <?php echo VmHTML::row_control('group_price','Multiple price' ,$list_group_price, true, 3); ?>
                            <?php echo VmHTML::row_control('booleanlist', 'COM_VIRTUEMART_PUBLISHED', 'published', $this->item->published); ?>

                        </div>
                        <div class="span5">
                            <?php echo VmHTML::row_basic('list_checkbox', 'select tour', 'list_tour_id', $this->list_tour, $this->item->list_tour_id, '', 'virtuemart_product_id', 'product_name', false); ?>

                        </div>

                    </div>
                </fieldset>

            </div>
            <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
            <?php echo VmHTML::inputHidden(array(virtuemart_transferaddon_id => $this->item->virtuemart_transferaddon_id)); ?>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>

    </div>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>