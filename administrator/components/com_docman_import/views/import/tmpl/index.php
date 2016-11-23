<!DOCTYPE html>
<!-- FIXME subfolders -->
<html class="koowa-html" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?php echo JURI::root(true); ?>/media/com_docman_import/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo JURI::root(true); ?>/templates/system/css/general.css" type="text/css" />

    <script src="<?php echo JURI::root(true); ?>/media/com_docman_import/js/jquery.min.js"></script>
    <script src="<?php echo JURI::root(true); ?>/media/com_docman_import/js/koowa.js"></script>
    <jdoc:include type="head" />
</head>
<body class="koowa front_form">
<!--[if lte IE 8 ]><div class="old-ie"> <![endif]-->
    <div class="front_form_container">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </div>
<!--[if lte IE 8 ]></div><![endif]-->
</body>
</html>