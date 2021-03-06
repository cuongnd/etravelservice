<?php
/**
 *
 * Description
 *
 * @packagetsmart
 * @subpackage Config
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_shopfront.php 9035 2015-11-03 10:37:57Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<table width="100%">
<tr>
<td valign="top" width="50%">
<fieldset>
<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_MORE_CORE_SETTINGS'); ?></legend>
<table class="admintable">
	<?php
	echo VmHTML::row('raw','com_tsmart_WEIGHT_UNIT_DEFAULT',ShopFunctions::renderWeightUnitList('weight_unit_default', tsmConfig::get('weight_unit_default')));
	echo VmHTML::row('raw','com_tsmart_LWH_UNIT_DEFAULT',ShopFunctions::renderLWHUnitList('lwh_unit_default', tsmConfig::get('lwh_unit_default')));
	echo VmHTML::row('checkbox','com_tsmart_ADMIN_SHOW_PRINTICON','show_printicon',tsmConfig::get('show_printicon',1));
	echo VmHTML::row('checkbox','com_tsmart_PDF_ICON_SHOW','pdf_icon',tsmConfig::get('pdf_icon',0));
?>
</table>
</fieldset>
<fieldset>
<legend><?php echo tsmText::_('com_tsmart_CFG_RECOMMEND_ASK'); ?></legend>
<table class="admintable">
<?php
	echo VmHTML::row('checkbox','com_tsmart_ADMIN_SHOW_EMAILFRIEND','show_emailfriend',tsmConfig::get('show_emailfriend',0));
	echo VmHTML::row('checkbox','com_tsmart_RECCOMEND_UNATUH','recommend_unauth',tsmConfig::get('recommend_unauth',0));
	echo VmHTML::row('checkbox','com_tsmart_ASK_QUESTION_CAPTCHA','ask_captcha',tsmConfig::get('ask_captcha',0));
	echo VmHTML::row('checkbox','com_tsmart_ASK_QUESTION_SHOW','ask_question',tsmConfig::get('ask_question',0));
	echo VmHTML::row('input','com_tsmart_ASK_QUESTION_MIN_LENGTH','asks_minimum_comment_length',tsmConfig::get('asks_minimum_comment_length',50),'class="inputbox"','',4,4);
	echo VmHTML::row('input','com_tsmart_ASK_QUESTION_MAX_LENGTH','asks_maximum_comment_length',tsmConfig::get('asks_maximum_comment_length',2000),'class="inputbox"','',5,5);
?>
</table>
</fieldset>
<fieldset>
<legend><?php echo tsmText::_('com_tsmart_COUPONS_ENABLE'); ?></legend>
	<table class="admintable">
		<?php echo VmHTML::row('checkbox','com_tsmart_COUPONS_ENABLE','coupons_enable',tsmConfig::get('coupons_enable',0));

		$_defaultExpTime = array(
		'1,D' => '1 ' . tsmText::_('com_tsmart_DAY')
		, '1,W' => '1 ' . tsmText::_('com_tsmart_WEEK')
		, '2,W' => '2 ' . tsmText::_('com_tsmart_WEEK_S')
		, '1,M' => '1 ' . tsmText::_('com_tsmart_MONTH')
		, '3,M' => '3 ' . tsmText::_('com_tsmart_MONTH_S')
		, '6,M' => '6 ' . tsmText::_('com_tsmart_MONTH_S')
		, '1,Y' => '1 ' . tsmText::_('com_tsmart_YEAR')
		);
		echo VmHTML::row('raw','com_tsmart_COUPONS_EXPIRE',VmHTML::selectList('coupons_default_expire', tsmConfig::get('coupons_default_expire'), $_defaultExpTime));
		$attrlist = 'class="inputbox" multiple="multiple" ';
		echo VmHTML::row('genericlist','com_tsmart_COUPONS_REMOVE',$this->os_Options,'cp_rm[]',$attrlist, 'order_status_code', 'order_status_name', tsmConfig::get('cp_rm',array('C')), 'cp_rm',true);
	?>
	</table>
</fieldset>
<fieldset>
<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_PRODUCT_LISTING'); ?></legend>
<table class="admintable">
<?php
	echo VmHTML::row('checkbox','com_tsmart_PRODUCT_NAVIGATION_SHOW','product_navigation',tsmConfig::get('product_navigation',1));
	echo VmHTML::row('checkbox','com_tsmart_DISPLAY_STOCK','display_stock',tsmConfig::get('display_stock',1));
	echo VmHTML::row('checkbox','com_tsmart_SHOW_PRODUCT_CUSTOMS','show_pcustoms',tsmConfig::get('show_pcustoms',1));
	echo VmHTML::row('checkbox','com_tsmart_UNCAT_PARENT_PRODUCTS_SHOW','show_uncat_parent_products',tsmConfig::get('show_uncat_parent_products',0));
	echo VmHTML::row('checkbox','com_tsmart_UNCAT_CHILD_PRODUCTS_SHOW','show_uncat_child_products',tsmConfig::get('show_uncat_child_products',0));
	echo VmHTML::row('checkbox','com_tsmart_SHOW_PRODUCTS_UNPUBLISHED_CATEGORIES','show_unpub_cat_products',tsmConfig::get('show_unpub_cat_products',1));
	echo VmHTML::row('input','com_tsmart_LATEST_PRODUCTS_DAYS','latest_products_days',tsmConfig::get('latest_products_days',7),'class="inputbox"','',4,4);
	$latest_products_orderBy = array(
		'modified_on' => tsmText::_('com_tsmart_LATEST_PRODUCTS_ORDERBY_MODIFIED'),
		'created_on' => tsmText::_('com_tsmart_LATEST_PRODUCTS_ORDERBY_CREATED')
	);
	echo VmHTML::row('selectList','com_tsmart_LATEST_PRODUCTS_ORDERBY','latest_products_orderBy',tsmConfig::get('latest_products_orderBy', 'created_on'),$latest_products_orderBy);
?>
</table>
</fieldset>
</td>
<td>
	<fieldset class="checkboxes">
		<legend>
			<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_CFG_POOS_ENABLE_EXPLAIN'); ?>">
				<?php echo tsmText::_('com_tsmart_CFG_POOS_ENABLE'); ?>
			</span>
		</legend>
		<div>
			<?php echo VmHTML::checkbox('lstockmail', tsmConfig::get('lstockmail')); ?>
			<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_CFG_LOWSTOCK_NOTIFY_TIP'); ?>">
				<label for="reviews_autopublish">
					<?php echo tsmText::_('com_tsmart_CFG_LOWSTOCK_NOTIFY'); ?>
				</label>
			</span>
		</div>
		<?php
		$options = array(
			'none' => tsmText::_('com_tsmart_ADMIN_CFG_POOS_NONE'),
			'disableit' => tsmText::_('com_tsmart_ADMIN_CFG_POOS_DISABLE_IT'),
			'disableit_children' => tsmText::_('com_tsmart_ADMIN_CFG_POOS_DISABLE_IT_CHILDREN'),
			'disableadd' => tsmText::_('com_tsmart_ADMIN_CFG_POOS_DISABLE_ADD'),
			'risetime' => tsmText::_('com_tsmart_ADMIN_CFG_POOS_RISE_AVATIME')
		);
		echo VmHTML::radioList('stockhandle', tsmConfig::get('stockhandle', 'none'), $options);
		?>
		<div style="font-weight:bold;">
					<span class="hasTip" title="<?php echo tsmText::_('com_tsmart_AVAILABILITY_EXPLAIN'); ?>">
						<?php echo tsmText::_('com_tsmart_AVAILABILITY'); ?>
					</span>
		</div>
		<input type="text" class="inputbox" id="product_availability" name="rised_availability" value="<?php echo tsmConfig::get('rised_availability'); ?>"/>
		<span class="icon-nofloat vmicon vmicon-16-info tooltip" title="<?php echo '<b>' . tsmText::_('com_tsmart_AVAILABILITY') . '</b><br/ >' . tsmText::_('com_tsmart_PRODUCT_FORM_AVAILABILITY_TOOLTIP1') ?>"></span>

		<div class="clr"></div>
		<?php echo JHtml::_('list.images', 'image', tsmConfig::get('rised_availability'), " ", $this->imagePath); ?>
		<span class="icon-nofloat vmicon vmicon-16-info tooltip" title="<?php echo '<b>' . tsmText::_('com_tsmart_AVAILABILITY') . '</b><br/ >' . tsmText::sprintf('com_tsmart_PRODUCT_FORM_AVAILABILITY_TOOLTIP2', $this->imagePath) ?>"></span>

		<div class="clr"></div>
		<img border="0" id="imagelib" alt="<?php echo tsmText::_('com_tsmart_PREVIEW'); ?>" name="imagelib" src="<?php if (tsmConfig::get('rised_availability')) {
			echo JURI::root(true) . $this->imagePath . tsmConfig::get('rised_availability');
		}?>"/>
	</fieldset>
	<fieldset>
		<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_TITLE'); ?></legend>
		<table class="admintable">
			<?php
			echo VmHTML::row('checkbox','com_tsmart_REVIEWS_AUTOPUBLISH','reviews_autopublish',tsmConfig::get('reviews_autopublish',0));
			echo VmHTML::row('input','com_tsmart_ADMIN_CFG_REVIEW_MINIMUM_COMMENT_LENGTH','reviews_minimum_comment_length',tsmConfig::get('reviews_minimum_comment_length',0));
			echo VmHTML::row('input','com_tsmart_ADMIN_CFG_REVIEW_MAXIMUM_COMMENT_LENGTH','reviews_maximum_comment_length',tsmConfig::get('reviews_maximum_comment_length',0));
			$showReviewFor = array('none' => tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_SHOW_NONE'),
				'registered' => tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_SHOW_REGISTERED'),
				'all' => tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_SHOW_ALL')
			); //showReviewFor
			echo VmHTML::row('radioList','com_tsmart_ADMIN_CFG_REVIEW_SHOW','showReviewFor',tsmConfig::get('showReviewFor','all'),$showReviewFor);

			$reviewMode = array('none' => tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_MODE_NONE'),
				'bought' => tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_MODE_BOUGHT_PRODUCT'),
				'registered' => tsmText::_('com_tsmart_ADMIN_CFG_REVIEW_MODE_REGISTERED')
				//	3 => vmText::_('com_tsmart_ADMIN_CFG_REVIEW_MODE_ALL')
			);
			echo VmHTML::row('radioList','com_tsmart_ADMIN_CFG_REVIEW','reviewMode',tsmConfig::get('reviewMode','bought'),$reviewMode);

			echo VmHTML::row('radioList','com_tsmart_ADMIN_CFG_RATING_SHOW','showRatingFor',tsmConfig::get('showRatingFor','all'),$showReviewFor);
			echo VmHTML::row('radioList','com_tsmart_ADMIN_CFG_RATING','ratingMode',tsmConfig::get('ratingMode','bought'),$reviewMode);

			$attrlist = 'class="inputbox" multiple="multiple" ';
			echo VmHTML::row('genericlist','com_tsmart_REVIEWS_OS',$this->os_Options,'rr_os[]',$attrlist, 'order_status_code', 'order_status_name', tsmConfig::get('rr_os',array('C')), 'rr_os',true);
			?>

		</table>
	</fieldset>
</td>
</tr>
</table>
<?php
vmJsApi::addJScript('vm.imagechange','
	jQuery("#image").change(function () {
		var $newimage = jQuery(this).val();
		jQuery("#product_availability").val($newimage);
		jQuery("#imagelib").attr({ src:"'.JURI::root(true) . $this->imagePath.'" + $newimage, alt:$newimage });
	});');
?>

