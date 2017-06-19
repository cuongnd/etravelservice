<?php
/**
 *
 * Main product information
 *
 * @package    tsmart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2015 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 8982 2015-09-14 09:45:02Z Milbo $
 */
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_edit_passenger_cost.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_edit_passenger_cost form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Service cost') ?></h3>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th>
                        <?php echo $this->sort('customer_name', 'Passenger type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Net  price'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'Mark up'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Tax'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Sale price'); ?>
                    </th>

                </tr>
                </thead>
                <?php
                $render_tr=function($departure,$type){
                    $i=0;
                    ob_start();
                    ?>
                    <tr>
                        <td><?php echo $type ?></td>
                        <td>
                            <?php
                            $key_price="price_".$type;
                            echo $departure->$key_price
                            ?>
                        </td>
                        <td class="cost">
                            <?php
                            $key_price="price_".$type;
                            echo $departure->$key_price
                            ?>
                        </td>
                        <td class="cost">
                            <?php
                            $key_price="price_".$type;
                            echo $departure->$key_price
                            ?>
                        </td>
                        <td class="cost">
                            <?php
                            $key_price="sale_price_".$type;
                            echo $departure->$key_price
                            ?>
                        </td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                echo $render_tr($this->departure,"senior");
                echo $render_tr($this->departure,"adult");
                echo $render_tr($this->departure,"teen");
                echo $render_tr($this->departure,"children1");
                echo $render_tr($this->departure,"children2");


                ?>
            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

