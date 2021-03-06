<?php
/**
*
* The main product images
*
* @package	tsmart
* @subpackage Product
* @author RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_images.php 9021 2015-10-20 23:54:07Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


?>
<div class="col50">
	<div class="selectimage">
	<?php
		if(empty($this->product->images[0]->tsmart_media_id)) $this->product->images[0]->addHidden('file_is_product_image','1');
		if (!empty($this->product->tsmart_media_id)) echo $this->product->images[0]->displayFilesHandler($this->product->tsmart_media_id,'product');
		else echo $this->product->images[0]->displayFilesHandler(null,'product');
	?>
	</div>
	<div>
		<?php
			//echo '<div width="100px">'.vmText::_('com_tsmart_RTB_AD').'</div>';
			$jlang =JFactory::getLanguage();
			$tag = $jlang->getTag();
			$imgUrl = 'http://www.pixelz.com/images/gmail.png';
			if(strpos($tag,'de')!==FALSE){
				$url = 'http://de.pixelz.com/tsmart/';
			} else if(strpos($tag,'fr')!==FALSE){
				$url = 'http://fr.pixelz.com/tsmart/';
			} else {
				$url = 'http://uk.pixelz.com/tsmart/';
			}
			echo '<a href="'.$url.'" target="_blank" alt="'.tsmText::_('com_tsmart_RTB_AD').'"><img  style="width: 150px;" src="'.$imgUrl.'" title="'.tsmText::_('com_tsmart_RTB_AD').'"></a>';
		?>
	</div>
</div>
