<?php defined('_JEXEC') or die('Restricted access');

$product = $viewData['product'];

if ($viewData['showRating']) {
	$maxrating = tsmConfig::get('vm_maximum_rating_scale', 5);
	if (empty($product->rating)) {
	?>
		<div class="ratingbox dummy" title="<?php echo tsmText::_('com_tsmart_UNRATED'); ?>" >

		</div>
	<?php
	} else {
		$ratingwidth = $product->rating * 24;
  ?>

<div title=" <?php echo (tsmText::_("com_tsmart_RATING_TITLE") . round($product->rating) . '/' . $maxrating) ?>" class="ratingbox" >
  <div class="stars-orange" style="width:<?php echo $ratingwidth.'px'; ?>"></div>
</div>
	<?php
	}
}