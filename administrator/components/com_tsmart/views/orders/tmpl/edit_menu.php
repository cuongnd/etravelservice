<ul class="menu pull-right">
    <?php foreach($this->list_tab as $key=> $tab){ ?>
    <li class=" <?php echo $key==$this->tab_selected?" active ":"" ?> "><a href="index.php?option=com_tsmart&view=orders&task=edit_item&tab=<?php echo $key ?>&cid[]=<?php echo $this->item->tsmart_order_id ?>" ><?php echo JText::_($tab) ?></a></li>
    <?php } ?>
</ul>