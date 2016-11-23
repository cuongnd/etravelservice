<?php
/**
 * @package     DOCman Export
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('_JEXEC') or die;
?>
<!DOCTYPE html>
<html class="koowa-html" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?php echo JURI::root(true); ?>/templates/system/css/general.css" type="text/css" />
    <jdoc:include type="head" />

    <style type="text/css">
        html {
            overflow-y: hidden !important;
        }
    </style>
</head>
<body class="koowa">
<jdoc:include type="message" />
<jdoc:include type="component" />
</body>
</html>
