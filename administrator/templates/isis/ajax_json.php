<?php
$doc=JFactory::getDocument();
$doc->html= $doc->getBuffer('component');
echo json_encode($doc);
?>
