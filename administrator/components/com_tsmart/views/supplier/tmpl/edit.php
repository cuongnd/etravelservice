<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.tsmart.net
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
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_supplier_edit.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', "supplier");
?>
    <div class="view-supplier-edit">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


            <div class="col50">
                <div class="admintable row-fluid">
                    <div class="span12">
                        <div class="main-info">
                            <div class="row-fluid ">
                                <div class="span12">

                                    <div class="row-fluid">
                                        <div class="span4">
                                            Supplier type
                                        </div>
                                        <div class="span8">
                                            <?php echo VmHTML::list_radio('supplier_type', $this->list_supplier_type, $this->item->supplier_type); ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <?php echo VmHTML::row_control('input', 'supplier name', 'supplier_name', $this->item->supplier_name, 'class="required"'); ?>
                                            <?php echo VmHTML::row_control('input', 'VAT detail', 'vat_detail', $this->item->vat_detail, ''); ?>
                                            <?php echo VmHTML::row_control('input', 'language', 'language', $this->item->language, ''); ?>
                                            <?php echo VmHTML::row_control('input', 'currency', 'currency_id', $this->item->currency_id, ''); ?>

                                        </div>
                                        <div class="span6">
                                            <?php
                                            $iframe_link=JUri::base().'/index.php?option=com_tsmart&view=servicetype&task=edit&cid[]=0';
                                            ?>
                                            <?php echo VmHTML::row_control('select_add_on', 'Service type', 'service_type_id',$this->list_service_type, $this->item->service_type_id,'','virtuemart_service_type_id','title',$iframe_link); ?>
                                            <?php echo VmHTML::row_control('input', 'Bank name', 'bank_name', $this->item->bank_name, ''); ?>
                                            <?php echo VmHTML::row_control('input', 'Bank account', 'bank_account', $this->item->bank_account, ''); ?>
                                            <?php echo VmHTML::row_control('input', 'Swift code', 'Swift_code', $this->item->Swift_code, ''); ?>
                                            <?php echo VmHTML::row_control('image', 'supplier logo', 'supplier_logo', $this->item->supplier_logo, ' class="image"'); ?>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="contact-info">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h3>Contact data</h3>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <?php echo VmHTML::row_control('select', 'Country', 'virtuemart_country_id', $this->list_country,$this->item->country_id,'', 'virtuemart_country_id','country_name'); ?>
                                    <?php echo VmHTML::row_control('select_state_province', 'state/province', 'virtuemart_state_id', $this->list_state_province,$this->item->virtuemart_state_id,'', 'virtuemart_state_id','state_name','select[name="virtuemart_country_id"]'); ?>
                                    <?php echo VmHTML::row_control('select', 'City name', 'virtuemart_cityarea_id', $this->list_city_area,$this->item->virtuemart_cityarea_id,'', 'virtuemart_cityarea_id','city_area_name'); ?>
                                    <?php echo VmHTML::row_control('input', 'Address', 'address', $this->item->address, ''); ?>
                                    <?php echo VmHTML::row_control('input', 'website', 'website', $this->item->website, ''); ?>
                                </div>
                                <div class="span6">
                                    <?php echo VmHTML::row_control('input', 'telephone', 'telephone', $this->item->telephone, ''); ?>
                                    <?php echo VmHTML::row_control('input', 'Mobile phone', 'mobile_phone', $this->item->mobile_phone, ''); ?>
                                    <?php echo VmHTML::row_control('input', 'Fax number', 'fax_number', $this->item->fax_number, ''); ?>
                                    <?php echo VmHTML::row_control('input', 'Email address', 'email_address', $this->item->email_address, ''); ?>
                                    <?php echo VmHTML::row_control('input', 'Contact person', 'contact_person', $this->item->contact_person, ''); ?>
                                    <?php echo VmHTML::row_control('input', 'Contact mobile', 'contact_mobile', $this->item->contact_mobile, ''); ?>
                                </div>
                            </div>
                        </div>
                        <div class="addtional-info">
                            <div class="row-fluid ">
                                <div class="span4">
                                    Additional info
                                </div>
                                <div class="span8">
                                    <?php echo VmHTML::textarea('additional_info', $this->item->additional_info, ''); ?>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_supplier_id"
                   value="<?php echo $this->item->virtuemart_supplier_id; ?>"/>
            <?php echo VmHTML::inputHidden(array(
                'key[virtuemart_product_id]' => $this->virtuemart_product_id
            )); ?>

            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>