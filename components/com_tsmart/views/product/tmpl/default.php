<ul>
<?php foreach($this->list_product as $product){ ?>
    <li ><a href="index.php?option=com_tsmart&view=productdetails&tsmart_product_id=<?php echo $product->tsmart_product_id ?>"><?php echo $product->product_name ?></a></li>
<?php } ?>
</ul>