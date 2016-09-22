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
$app=JFactory::getApplication();
$input=$app->input;
$virtuemart_itinerary_id=$input->getInt('virtuemart_itinerary_id',0);
$virtuemart_product_id=$input->getInt('virtuemart_product_id',0);
?>
<div class="view-accommodation-edit">
    <form action="index.php" method="post" class="form-horizontal"  name="adminForm" id="adminForm">
        <div class="row-fluid">
            <div class="span4">
                <h3>select location</h3>
                <h4>add node</h4>
                <?php echo VmHTML::textarea( 'description', $this->item->description, 'class="required"', 30, 10); ?>
            </div>
            <div class="span8">
                <h4> add hotel</h4>
                <?php foreach($this->list_hotel_selected_by_service_class_id_and_itinerary_id AS $service_class){ ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="pull-right"><?php echo $service_class->service_class_name ?></h4>
                            <input type="hidden" name="virtuemart_service_class_id" value="<?php echo $service_class->virtuemart_service_class_id; ?>"/>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <?php
                            $list_hotel=$service_class->list_hotel;
                            for($i=0;$i<2;$i++){
                                $hotel_service_class=$list_hotel[$i];
                            ?>
                            <?php echo VmHTML::select('list_hotel_service_class['.$service_class->virtuemart_service_class_id.']['.($hotel_service_class->id?"id:$hotel_service_class->id":'').']', $this->list_hotel,$hotel_service_class->virtuemart_hotel_id,'', 'virtuemart_hotel_id','hotel_name'); ?>
                                <br/>
                            <?php } ?>
                        </div>
                        <div class="span6">

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
        <input type="hidden" name="virtuemart_itinerary_id" value="<?php echo $virtuemart_itinerary_id; ?>"/>
        <input type="hidden" name="virtuemart_accommodation_id" value="<?php echo $this->item->virtuemart_accommodation_id; ?>"/>
        <input type="hidden" name="key[virtuemart_product_id]" value="<?php echo $virtuemart_product_id; ?>"/>

        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="accommodation" name="controller">
        <input type="hidden" value="accommodation" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="toolbar pull-right">
            <button class="btn btn-small btn-success save" type="submit"><span class="icon-save icon-white"></span>Save</button>
            <button class="btn btn-small btn-success reset" ><span class="icon-new icon-white"></span>Reset</button>
            <button class="btn btn-small btn-success cancel" ><span class="icon-new icon-white"></span>cancel</button>
        </div>

    </form>

</div>