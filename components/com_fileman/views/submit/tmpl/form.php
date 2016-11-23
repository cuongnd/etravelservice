<?
/**
 * @package     DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
        
defined('KOOWA') or die; ?>

<?= @helper('behavior.mootools'); ?>
<?= @helper('behavior.keepalive'); ?>
<?= @helper('behavior.validator'); ?>

<?= @helper('com://admin/extman.template.helper.behavior.bootstrap', array('namespace' => false)); ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />

<style src="media://com_fileman/css/toolbar.css" />
<? if (version_compare(JVERSION, '1.6', '<')): ?>
<style src="media://com_fileman/css/toolbar15.css" />
<? endif; ?>
-->

<? if ($params->show_page_title): ?>
	<h2>
		<?= @escape($params->page_title) ?>
	</h2>
<? endif ?>

<div class="frontend-toolbar">
<?= @helper('com://admin/extman.template.helper.toolbar.render', array(
	'toolbar' => $this->getView()->getToolbar()
)); ?>
</div>
<div class="com_fileman">
<form action="" method="post" data-toolbar="" class="-koowa-form" enctype="multipart/form-data">
	<fieldset class="form-horizontal">
	    <legend><?= @text('Select a file')?></legend>
		<div class="control-group upload-method-box" id="document-file-path-row" style="display: block">
			<input type="file" name="file" class="required" />
		</div>
	</fieldset>
</form>
</div>