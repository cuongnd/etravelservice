<?php
$list_product=$this->products;
$col=3;
$list_list_product=array_chunk($list_product,$col);
?>
<?php foreach($list_list_product as $list_product){ ?>
    <div class="row">
        <?php foreach($list_product as $product){ ?>
            <div class="span<?php echo round(12/$col) ?>">
                <h3><a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id) ?>"><?php echo $product->product_name ?></a></h3>
            </div>
        <?php } ?>
    </div>
<?php } ?>
