<?
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('_JEXEC') or die;
?>

<?= @helper('com://admin/extman.template.helper.behavior.jquery') ?>
<?= @helper('com://admin/extman.template.helper.behavior.bootstrap', array('namespace' => 'com_fileman')); ?>
<!-- 
<script src="media://com_fileman/js/fileman.js" />
-->

<? if ($params->track_downloads): ?>
<script type="text/javascript">
jQuery(function($) {
    $('.fileman-view').click(function(e) {
        Fileman.trackEvent({action: 'Download', label: $(this).attr('data-path')});
    });
});
</script>
<? endif; ?>

<? if ($params->show_page_title): ?>
	<h2>
		<?= @escape($params->page_title); ?>
	</h2>
<? endif; ?>

<? if ($params->allow_uploads): ?>
    <div class="btn-toolbar">
        <a class="btn" href="<?= @route('&view=submit&layout=form&folder='.$folder->path); ?>">
            <i class="icon-plus"></i> <?= @text('Upload Files') ?>
        </a>
    </div>
<? endif; ?>

<form action="" method="get" class="-koowa-form">
<table class="table table-striped files-list">
	<? if ($state->limit): ?>
	<tfoot>
	    <tr>
	        <td colspan="2">
	        	<?= @helper('paginator.pagination', array(
	        		'total' 	 => $total,
	        		'limit' 	 => $state->limit,
	        		'offset' 	 => $state->offset,
	        		'show_count' => false,
	        		'show_limit' => $params->limit != 0
	        	)) ?>
	        </td>
	    </tr>
	</tfoot>
	<? endif; ?>
	<tbody>
		<? if ($parent !== null): ?>
		<tr>
			<td colspan="3">
                <? if ($params->show_icon): ?>
                    <a href="<?= @route('layout=table&folder='.$parent); ?>">
                        <img src="<?= @helper('icon.path', array('icon' => 'folder')) ?>" style="height: 22px; width: 22px" class="icon autosize" /></a>
                <? endif ?>

                <a href="<?= @route('layout=table&folder='.$parent); ?>">
					<?= @text('Parent Folder') ?>
				</a>
			</td>
		</tr>
		<? endif; ?>
		<? if ($folders): foreach($folders as $folder): ?>
		<tr>
            <td colspan="3">
                <? if ($params->show_icon): ?>
                    <a href="<?= @route('layout=table&folder='.$folder->path);?>">
                        <img src="<?= @helper('icon.path', array('icon' => 'folder')) ?>" style="height: 22px; width: 22px" class="icon autosize" /></a>
                <? endif ?>
				<a href="<?= @route('layout=table&folder='.$folder->path);?>">
					<?=@escape($folder->display_name)?>
				</a>
			</td>
		</tr>
		<? endforeach; endif; ?>
		<? if ($files): foreach($files as $file): ?>
		<tr>
			<td>
                <? if ($params->show_icon): ?>
                    <a class="fileman-download" data-path="<?= @escape($file->path); ?>"
                       href="<?= @route('view=file&folder='.$state->folder.'&name='.$file->name);?>">
                        <img src="<?= @helper('icon.path', array('extension' => $file->extension)) ?>" style="height: 22px; width: 22px" class="icon autosize" /></a>
                <? endif ?>
				<a class="fileman-download" data-path="<?= @escape($file->path); ?>"
					href="<?= @route('view=file&folder='.$state->folder.'&name='.$file->name);?>">
					<?=@escape($file->display_name)?>
				</a>
			</td>
            <td width="10" style="white-space: nowrap">
                <small>
                    <?= @helper('com://admin/files.template.helper.filesize.humanize', array('size' => $file->size));?>
                </small>
            </td>
            <td width="10" style="white-space: nowrap">
                <a class="btn btn-mini fileman-download" data-path="<?= @escape($file->path); ?>"
                   href="<?= @route('view=file&folder='.$state->folder.'&name='.$file->name);?>">
                    <i class="icon-download"></i>
                    <?= @text('Download'); ?>
                </a>
            </td>
		</tr>
		<? endforeach; endif; ?>
	</tbody>
</table>
</form>
