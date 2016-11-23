<?
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die; ?>

<?= @service('com://admin/files.controller.file')
	->container('fileman-files')
	->types(array('file', 'image'))
	->layout('compact')
	->config(array(
    	'router' => array(
        	'defaults' => (object) array(
        	    'option' => 'com_fileman',
        	    'routed' => '1'
        	)
    	)
	))
	->display(); ?>

<script>
window.addEvent('domready', function() {
	var selected = {folder: '', name: ''},
		folder_id = '<?= version_compare(JVERSION, '1.6', '<') ? 'urlparamsfolder': 'jform_request_folder'; ?>';

	document.id('insert-document').addEvent('click', function(e) {
		e.stop();
		console.log(selected.folder);
		window.parent.document.id('fileman-file-link-name').set('value', selected.name);
		window.parent.document.id(folder_id).set('value', selected.folder);
		window.parent.SqueezeBox.close();
	});

	document.id('details').adopt(document.id('document-insert-form'));

	var evt = function(e) {
		var target = document.id(e.target).getParent('.files-node');
		var row = target.retrieve('row');

		selected.folder = row.folder;
		selected.name = row.name;
		document.id('insert-document').set('disabled', false);
	};
	Files.app.grid.addEvent('clickFile', evt);
	Files.app.grid.addEvent('clickImage', evt);
});
</script>

<div id="document-insert-form" style="text-align: center; ">
	<button class="btn" type="button" id="insert-document" disabled><?= @text('Insert') ?></button>
</div>