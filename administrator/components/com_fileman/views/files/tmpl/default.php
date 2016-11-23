<?
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

defined('_JEXEC') or die; ?>

<?= @helper('behavior.bootstrap'); ?>

<?= $app ?>

<script>
window.addEvent('domready', function() {
	if (document.id('toolbar-upload')) {
		document.id('toolbar-upload').getElement('a').addEvent('click', function(e) {
			e.stop();
			document.id('files-show-uploader').fireEvent('click', e);
		});
	}

	if (document.id('toolbar-delete')) {
		// Enable/disable delete button by the number of selected checkboxes
		var el = document.id('toolbar-delete').getElement('a')
        if(el.getElement('span')) {
            el = el.getElement('span');
        }
		el.setStyle('background-position', 'bottom');

		Files.app.grid.addEvent('afterCheckNode', function() {
			var checked = Files.app.grid.container.getElements('input[type=checkbox]:checked');
			el.setStyle('background-position', checked.length ? 'top' : 'bottom');
		}.bind(this));

		Files.app.addEvent('afterNavigate', function() {
			el.setStyle('background-position', 'bottom');
		});

        // Cheesy hack to add our classes to the Mootools modal once it is loaded
        Files.app.grid.addEvent('clickFile', function() {
            var box = document.id('sbox-content'),
                interval = setInterval(function() {
                var element = box.getElement('div');
                if (element) {
                    clearInterval(interval);
                    element.addClass('com_fileman modal-inspector');
                    var size = element.getDimensions();
                    document.id('sbox-window').setStyle('height', size.height);
                }
            }, 100);
        });

		document.id('toolbar-delete').getElement('a').addEvent('click', function(e) {
			e.stop();
			document.id('files-batch-delete').fireEvent('click', e);
		});
	}

	if (document.id('toolbar-new')) {
		document.id('toolbar-new').getElement('a').addEvent('click', function(e) {
			e.stop();
			document.id('files-new-folder-toolbar').fireEvent('click', e);
		});
	}

	$$('#files-show-uploader', '#files-batch-delete', '#files-new-folder-toolbar').setStyle('display', 'none');
});
</script>