<?
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('_JEXEC') or die; ?>

<?= @helper('com://admin/extman.template.helper.behavior.bootstrap', array(
    'namespace' => false,
    'javascript' => array('modal')
)); ?>
<!--
<script src="media://com_fileman/bootstrap/js/bootstrap-image-gallery.js" />
<script src="media://com_fileman/js/fileman.js" />
<script src="media://com_fileman/js/gallery.js" /> 
-->
<script type="text/javascript">
jQuery(function($) {
    new Fileman.Gallery($('div.files-gallery'), {thumbwidth: <?= json_encode((int)$thumbnail_size['x']) ?>});
});
</script>

<? if ($params->track_views): ?>
<script type="text/javascript">
jQuery(function($) {
	$('#modal-gallery').on('loadImage', function () {
		var modal = $(this).data('modal'),
	        link = $(modal.$links[modal.options.index]),
		    path = link ? link.attr('data-path') : null;
		    
		if (path) {
			Fileman.trackEvent({label: path});
		}
	});
});
</script>
<? endif; ?>

<div class="com_fileman files-gallery">
    <div id="modal-gallery" class="modal modal-fullscreen modal-gallery hide fades in">
        <div class="modal-header">
            <a class="close" style="cursor: pointer" data-dismiss="modal">&times;</a>
            <h3 class="modal-title"></h3>
        </div>
        <div class="modal-body"><div class="modal-image"></div></div>
    </div>

    <? if ($params->allow_uploads): ?>
    <p>
        <a class="btn" href="<?= @route('&view=submit&layout=form&folder='.$folder->path); ?>">
            <?= @text('Upload Files') ?>
        </a>
    </p>
    <? endif; ?>

    <? if ($params->show_page_title): ?>
    	<h2>
    		<?= @escape($params->page_title) ?>
    	</h2>
    <? endif ?>

    <? if ($parent !== null): ?>
    <h4>
    	<a href="<?= @route('layout=gallery&folder='.$parent) ?>">
    	    <!-- @TODO We should display the name of the parent here -->
    		<?= @text('Parent Folder') ?>
    	</a>
    </h4>
    <? endif ?>

    <? if (count($files) || count($folders)): ?>
    <ol class="gallery-thumbnails" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="a.fileman-view">
        <? foreach($folders as $folder): ?>
        <li class="gallery-folder">
            <a href="<?= @route('layout=gallery&folder='.$folder->path) ?>">
                <span class="file-thumbnail"></span>
                <span class="file-label"><?= @escape($folder->display_name) ?></span>
            </a>
        </li>
        <? endforeach ?>
        
        <? foreach($files as $file): ?>
    	<? if ($params->show_thumbnails && !empty($file->thumbnail)): ?>
        <li class="gallery-file">
    		<a class="fileman-view" data-path="<?= @escape($file->path); ?>"
    			href="<?= @route('view=file&folder='.$state->folder.'&name='.$file->name) ?>"
    		    title="<?= @escape($file->display_name) ?>"
            >
    		    <span class="file-thumbnail" style="min-height:<?= $thumbnail_size['y'] ?>px">
        		    <img class="img-polaroid" src="<?= $file->thumbnail ?>" alt="<?= @escape($file->display_name) ?>" />
        		</span>
        		<? if ($params->show_filenames): ?>
        		<span class="file-label"><?= @escape($file->display_name) ?></span>
        		<? endif; ?>    
        	</a>
        </li>
        <? /* @TODO deal with files and missing thumbnails in a better way */ ?>
    	<? else: ?>
        <li class="gallery-document">
    		<a class="fileman-view" data-path="<?= @escape($file->path); ?>"
    			href="<?= @route('view=file&folder='.$state->folder.'&name='.$file->name) ?>"
    		    title="<?= @escape($file->display_name) ?>"
            >
    			<span class="file-thumbnail" style="min-height:<?= $thumbnail_size['y'] ?>px"></span>
        		<? if ($params->show_filenames): ?>
        		<span class="file-label"><?= @escape($file->display_name) ?></span>
        		<? endif; ?>
    		</a>
        </li>
    	<? endif ?>
        <? endforeach ?>
    </ol>

    <? if ($state->limit && $total > $state->limit-$state->offset): ?>
    <?= @helper('paginator.pagination', array(
    	'total' => $total,
    	'limit' => $state->limit,
    	'offset' => $state->offset,
    	'show_count' => false,
    	'show_limit' => false
    )) ?>
    <? endif ?>

    <? else : ?>

    <h2><?= @text('Folder is empty.') ?></h2>

    <? endif ?>
</div>