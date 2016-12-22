<?php
$product = $viewData['product'];
// Availability
$stockhandle = tsmConfig::get('stockhandle', 'none');
$product_available_date = substr($product->product_available_date,0,10);
$current_date = date("Y-m-d");
if (($product->product_in_stock - $product->product_ordered) < 1) {
	if ($product_available_date != '0000-00-00' and $current_date < $product_available_date) {
	?>	<div class="availability">
		<?php echo tsmText::_('com_tsmart_PRODUCT_AVAILABLE_DATE') .': '. JHtml::_('date', $product->product_available_date, tsmText::_('DATE_FORMAT_LC4')); ?>
	</div>
	<?php
	} else if ($stockhandle == 'risetime' and tsmConfig::get('rised_availability') and empty($product->product_availability)) {
		?>	<div class="availability">
			<?php echo (file_exists(JPATH_BASE . DS . tsmConfig::get('assets_general_path') . 'images/availability/' . tsmConfig::get('rised_availability'))) ? JHtml::image(JURI::root() . tsmConfig::get('assets_general_path') . 'images/availability/' . tsmConfig::get('rised_availability', '7d.gif'), tsmConfig::get('rised_availability', '7d.gif'), array('class' => 'availability')) : tsmText::_(tsmConfig::get('rised_availability')); ?>
		</div>
	<?php
	} else if (!empty($product->product_availability)) {
		?>
		<div class="availability">
			<?php echo (file_exists(JPATH_BASE . DS . tsmConfig::get('assets_general_path') . 'images/availability/' . $product->product_availability)) ? JHtml::image(JURI::root() . tsmConfig::get('assets_general_path') . 'images/availability/' . $product->product_availability, $product->product_availability, array('class' => 'availability')) : tsmText::_($product->product_availability); ?>
		</div>
	<?php
	}
}
else if ($product_available_date != '0000-00-00' and $current_date < $product_available_date) {
	?>	<div class="availability">
		<?php echo tsmText::_('com_tsmart_PRODUCT_AVAILABLE_DATE') .': '. JHtml::_('date', $product->product_available_date, tsmText::_('DATE_FORMAT_LC4')); ?>
	</div>
<?php
}
?>