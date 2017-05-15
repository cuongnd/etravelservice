<?php
$input=JFactory::getApplication()->input;
$tab_selected=$input->getString('tab','general');
$list_tab=array(
    general=>"General",
    active=>"Active",
    passenger=>"Passenger",
    finance=>"Finance",
    conversation=>"Conversation",
);
?>
<ul class="menu pull-right">
    <?php foreach($list_tab as $key=> $tab){ ?>
    <li class=" <?php echo $key==$tab_selected?" active ":"" ?> "><a href="index.php?option=com_tsmart&view=orders&task=edit_item&tab=<?php echo $key ?>&cid[]=<?php echo $this->item->tsmart_order_id ?>" ><?php echo JText::_($tab) ?></a></li>
    <?php } ?>
</ul>