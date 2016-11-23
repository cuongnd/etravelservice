<?
/**
 * @package     DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('KOOWA') or die; ?>

<? if (version_compare(JVERSION, '3.0', 'ge')) : ?>
<style src="media://com_docman/bootstrap/css/bootstrap-module.j3.css" />
<? else : ?>
<style src="media://com_docman/bootstrap/css/bootstrap.css" />
<style src="media://com_docman/bootstrap/css/bootstrap-module.css" />
<? endif ?>

<? if ($total): ?>
<div class="com_docman mod_docman <?= $params->moduleclass_sfx; ?>">
    <ul class="nav nav-list <?= $params->show_icon ? 'icons' :'' ?>">
    <? foreach ($documents as $document): ?>
        <li>
            <a href="<?= $document->link; ?>">
                <? if ($document->params->icon && $params->show_icon): ?>
                    <?
                        if (substr($document->params->icon, 0, 5) === 'icon:') {
                            $image = 'icon://'.substr($document->params->icon, 5);
                        } else {
                            $image = 'media://com_docman/images/icons/'.$document->params->icon;
                        }
                    ?>
                    <img align="left" src="<?= $image ?>" width="16px" height="16px" class="icon" onerror="this.parentNode.removeChild(this);">
                <? endif ?>

                <?= $document->title;?>

                <? if ($params->show_category): ?>
                    (<?= $document->category_title; ?>)
                <? endif; ?>
            </a>
        </li>
    <? endforeach; ?>
    </ul>
</div>
<? endif; ?>