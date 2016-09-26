<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
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
JHtml::_('jquery.framework');
$doc=JFactory::getDocument();
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/system/js/datepicker/js/datepicker.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/jquery.dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js');


$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/departure.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/datepicker/css/base.css');
$doc->addStyleSheet(JUri::root().'/media/system/js/datepicker/css/clean.css');
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/departure.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start','com_tsmart_CURRENCY_DETAILS');
require_once JPATH_ROOT.'/libraries/upgradephp-19/upgrade.php';
$list_data=$this->departure->days_seleted;
$list_data = (array)up_json_decode($list_data, false, 512, JSON_PARSE_JAVASCRIPT);


?>

	<form action="index.php" method="post" name="adminForm" id="adminForm">


		<div class="col50">
			<div class="row-fluid">
				<div class="span6 ">
					<table class="admintable table">
						<tr>
							<td>Departure name</td>
							<td colspan="4"><input type="text"  size="16" value="<?php echo $this->departure->departure_name ?>" id="departure_name" required="true" name="departure_name"  class="inputbox"></td>
						</tr>
						<tr>
							<td>Open space</td>
							<td>Min</td>
							<td><input type="number"  size="7" value="<?php echo $this->departure->min_space ?>" id="min_space" name="min_space" required="true" class="inputbox"></td>
							<td>Max</td>
							<td><input type="number"  size="7" value="<?php echo $this->departure->max_space ?>" id="max_space" name="max_space" required="true" class="inputbox"></td>
						</tr>
						<tr>
							<td>Sale periol</td>
							<td>Open</td>
							<td  nowrap>
								<?php echo  vmJsApi::jDate ($this->departure->sale_period_open, 'sale_period_open'); ?>
							</td>
							<td>close</td>
							<td  nowrap>
								<?php echo  vmJsApi::jDate ($this->departure->sale_period_close, 'sale_period_close'); ?>
							</td>

						</tr>
						<tr>
							<td>Vaild periol</td>
							<td>From</td>
							<td>
								<?php echo  vmJsApi::jDate ($this->departure->vail_period_from, 'vail_period_from'); ?>
							</td>
							<td>To</td>
							<td>
								<?php echo  vmJsApi::jDate ($this->departure->vail_period_to, 'vail_period_to'); ?>
							</td>
						</tr>
						<tr>
							<td>G-Guarantee</td>
							<td colspan="4"><input type="number"  size="16" value="<?php echo $this->departure->g_guarantee ?>" id="g_guarantee" name="g_guarantee" required="true" class="inputbox" equired="true"></td>
						</tr>
						<tr>
							<td>L-limit space</td>
							<td colspan="4"><input type="number"  size="16" value="<?php echo $this->departure->limited_space ?>" id="limited_space" name="limited_space" required="true" class="inputbox" equired="true"></td>
						</tr>

					</table>

				</div>
				<div class="span6">
					<select multiple>
						<?php foreach($this->list_tour as $tour){ ?>
						<option value="<?php echo $tour->virtuemart_product_id ?>"><?php echo $tour->product_name ?></option>
						<?php } ?>
					</select>
					<select multiple>
						<?php foreach($this->list_tour_class as $tour_class){ ?>
						<option value="<?php echo $tour_class->virtuemart_tour_class_id ?>">sdsds<?php echo $tour_class->tour_class_name ?></option>
						<?php } ?>
					</select>
					<div class="row-fluid">
						<div class="span2"><h4>Weekly repeat setup</h4></div>
						<div class="span1"><i class="icon-rightarrow"></i></div>
						<div class="span1">
							MON
							<br/>
							<input name="mon" <?php echo $this->departure->mon==1?'checked':'' ?> value="1"  type="checkbox">
						</div>
						<div class="span1">
							Tue
							<br/>
							<input name="tue" <?php echo $this->departure->tue==1?'checked':'' ?> value="1"  type="checkbox">
						</div>
						<div class="span1">
							Wen
							<br/>
							<input name="wen" <?php echo $this->departure->wen==1?'checked':'' ?> value="1"  type="checkbox">
						</div>
						<div class="span1">
							Thu
							<br/>
							<input name="thu" <?php echo $this->departure->thu==1?'checked':'' ?>  value="1" type="checkbox">
						</div>
						<div class="span1">
							Fri
							<br/>
							<input name="fri" <?php echo $this->departure->fri==1?'checked':'' ?>  value="1" type="checkbox">
						</div>
						<div class="span1">
							Sat
							<br/>
							<input name="sat" <?php echo $this->departure->sat==1?'checked':'' ?>  value="1" type="checkbox">
						</div>
						<div class="span1">
							Sun
							<br/>
							<input name="sun" <?php echo $this->departure->sun==1?'checked':'' ?> value="1"  type="checkbox">
						</div>
						<div class="span2">
							<h4>Customer setting</h4>
						</div>
						<div class="span1"></div>
						<div class="span1"></div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div id="multi-calendar-departure">
								<input type="hidden" id="days_seleted" name="days_seleted"  value="<?php echo $this->departure->days_seleted ?>"  >

							</div>
						</div>
					</div>


				</div>
			</div>


		</div>
		<input type="hidden" name="virtuemart_departure_id" value="<?php echo $this->departure->virtuemart_departure_id; ?>" />
		<?php echo $this->addStandardHiddenToForm(); ?>
	</form>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#adminForm').view_departure({
			list_date:<?php echo json_encode($list_data); ?>
		});
	});
</script>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>