<?php
/**
 * @package     DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('KOOWA') or die;

JFactory::getDocument()->setTitle('DOCman migration tool');
?>

<script src="media://com_files/plupload/plupload.core.html5.flash.queue.js" />
<script src="media://com_docman_import/js/migrator.js" />

<style src="media://com_docman_import/css/admin.css" />

<script type="text/javascript">
var docman_migration_in_progress = false;
var docman_migrate_from_backup = 0;

window.onbeforeunload = function(e) {
    if (docman_migration_in_progress) {
        return 'Navigating away from this page may result in a broken site. Are you sure you want to continue?';
    }
};

jQuery(function($) {
    var token = '<?= JSession::getFormToken(); ?>',
        uploader = new plupload.Uploader({
            runtimes : 'html5,flash,html4',
            browse_button : 'pickfiles',
            dragdrop: true,
            container : 'migrator-container',
            max_file_size : '<?= ComDocmanDatabaseRowExtension::getMaximumUploadSize() ?>b',
            url: '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json', false); ?>',
            flash_swf_url: 'media://koowa/com_files/plupload/plupload.flash.swf',
            urlstream_upload: true, // required for flash
            multi_selection: false,
            multipart_params: {
                action: 'upload',
                _token: token
            },
            headers: {
                'X-Requested-With': 'xmlhttprequest'
            },
            filters : [
                {title : "Zip files", extensions : "zip"}
            ]
        }),
        showStep = function(step) {
            $('.migrator__wrapper').hide();
            $('.migrator--step'+step).show();

            $('.migrator__steps__list__item').removeClass('item--active');
            $('.migrator__steps__list__item:nth-child('+step+')').addClass('item--active');
        },
        showError = function(message) {
            docman_migration_in_progress = false;

            $('.migrator_alert').fadeOut('fast', function() {
                $(this).html(message).fadeIn('fast');
            });
            $('.bar').removeClass('bar-success').addClass('bar-danger')
                .parent().removeClass('active');
        },
        updateProgress = function(progress_bar, percent) {
            progress_bar.css('width', percent + '%');

            if (percent == '100') {
                progress_bar.addClass('bar-success')
                    .parent().removeClass('active');
            }
        },
        uploader_progress = $('#progress-bar1'),
        prepare_category_progress = $('#progress-bar2'),
        prepare_document_progress = $('#progress-bar3'),
        category_progress  = $('#progress-bar4'),
        document_progress  = $('#progress-bar5'),
        fileUploaded = function(uploader, file, response) {
            var json = $.parseJSON(response.response) || {};

            if (!json.status) {
                showError(json.error || 'Unknown error');
                return;
            }

            updateProgress(uploader_progress, '100');

            prepare();
        },
        prepare = function() {
            $.ajax({
                url: '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json', false); ?>',
                type: 'post',
                data: {
                    action: 'prepare',
                    _token: token
                }
            }).then(function(response) {
                updateProgress(prepare_document_progress, 10);

                if (response.status) {
                    insertDocuments();
                } else {
                    showError(response.error || 'Unknown error while preparing tables');
                }

            });
        },
        insertDocuments = function() {
            var chunker = new DocmanMigrator.Chunker({
                url:  '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json&type=documents', false); ?>',
                request: {
                    type: 'post',
                    data: {
                        'action': 'insert',
                        _token: token
                    }
                }
            }).bind('processUpdate', function(e, data) {
                    updateProgress(prepare_document_progress, data.percentage*9/10+10);
                }).bind('processFailed', function(e, data) {
                    showError(data.error);
                }).bind('processComplete', function(e, data) {
                    insertCategories();
                }).start();
        },
        insertCategories = function() {
            updateProgress(prepare_category_progress, 10);

            var chunker = new DocmanMigrator.Chunker({
                url:  '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json&type=categories', false); ?>',
                request: {
                    type: 'post',
                    data: {
                        'action': 'insert',
                        _token: token
                    }
                }
            }).bind('processUpdate', function(e, data) {
                updateProgress(prepare_category_progress, data.percentage*9/10);
            }).bind('processFailed', function(e, data) {
                showError(data.error);
            }).bind('processComplete', function(e, data) {
                cacheTree();
            }).start();
        },
        cacheTree = function() {
            $.ajax({
                url: '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json', false); ?>',
                type: 'post',
                data: {
                    action: 'cache_tree',
                    _token: token,
                    from_backup: docman_migrate_from_backup
                }
            }).then(function(response) {
                updateProgress(prepare_category_progress, 100);

                if (response.status) {
                    importCategories();
                } else {
                    showError(response.error || 'Unknown error while preparing categories');
                }
            });
        },
        importCategories = function() {
            updateProgress(category_progress, 10);

            var chunker = new DocmanMigrator.Chunker({
                url:  '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json&type=categories', false); ?>',
                request: {
                    type: 'post',
                    data: {
                        'action': 'import_categories',
                        _token: token,
                        from_backup: docman_migrate_from_backup
                    }
                }
            }).bind('processUpdate', function(e, data) {
                    updateProgress(category_progress, data.percentage*9/10+10);
            }).bind('processFailed', function(e, data) {
                showError(data.error);
            }).bind('processComplete', function(e, data) {
                importDocuments();
            }).start();
        },
        importDocuments = function() {
            updateProgress(document_progress, 10);

            var chunker = new DocmanMigrator.Chunker({
                url:  '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json&type=documents', false); ?>',
                request: {
                    type: 'post',
                    data: {
                        'action': 'import_documents',
                        _token: token,
                        from_backup: docman_migrate_from_backup
                    }
                }
            }).bind('processUpdate', function(e, data) {
                updateProgress(document_progress, data.percentage*9/10+10);
            }).bind('processFailed', function(e, data) {
                showError(data.error);
            }).bind('processComplete', function(e, data) {
                showStep(3);

                $.ajax({
                    url: '<?= JRoute::_('index.php?option=com_docman_import&view=import&format=json', false); ?>',
                    type: 'post',
                    data: {
                        action: 'cleanup',
                        _token: token
                    }
                });

                docman_migration_in_progress = false;
            }).start();
        }
        ;

    uploader.init();

    uploader.bind('FilesAdded', function(uploader, files) {
        docman_migration_in_progress = true;

        $('#pickfiles').css('display', 'none');

        showStep(2);

        uploader.start();
    });
    uploader.bind('UploadProgress', function(uploader, file) {
        updateProgress(uploader_progress, file.percent);
    });
    uploader.bind('Error', function(uploader, error) {
        showError(error);
    });
    uploader.bind('FileUploaded', fileUploaded);

    $('#importfrombackup').click(function(event) {
        event.preventDefault();

        docman_migration_in_progress = true;
        docman_migrate_from_backup = 1;

        $(this).css('display', 'none');

        showStep(2);

        updateProgress(uploader_progress, 100);
        updateProgress(prepare_document_progress, 100);

        cacheTree();

    });
});
</script>

<div class="koowa">
<div class="migrator" id="migrator-container">
    <div class="migrator__header">
        <img class="joomlatools_logo" src="media://com_docman_import/images/joomlatools_logo_80px.png" alt="Joomlatools logo" /> DOCman <strong>migration tool</strong>
    </div>
    <div class="migrator__steps">
        <ul class="migrator__steps__list">
            <li class="migrator__steps__list__item item--active">Start</li>
            <li class="migrator__steps__list__item">Migrate</li>
            <li class="migrator__steps__list__item">Completed</li>
        </ul>
    </div>
    <div class="migrator__wrapper migrator--step1">
        <h1>Start migration</h1>
        <?
        $missing = $this->getView()->getMissingDependencies();
        if ($missing): ?>
            <div class="alert alert-error">
                <h3>Missing Requirements</h3>
                <ul>
                    <? foreach ($missing as $key => $error): ?>
                        <li><?= $error; ?></li>
                    <? endforeach; ?>
                </ul>
            </div>
            <div class="migrator__content">
                <p><a class="migrator_button" href="<?= @route('option=com_docman&view=documents&layout=default&format=html'); ?>">Go to DOCman</a></p>
            </div>
        <? else: ?>
        <? if ($this->getView()->hasBackupTables()): ?>
            <div class="migrator__content">
                <p>You uninstalled DOCman 1.6 before. Would you like to import documents
                    and categories from the deleted installation?</p>
                <p>
                    <a id="importfrombackup" class="migrator_button" href="#">Yes, import from DOCman 1.6</a>
                </p>
                <p>
                    or,
                </p>
            </div>
        <? endif; ?>
        <div class="migrator__content">
            <p>Select the migration file you downloaded from the DOCman exporter.</p>
            <p>
                <a id="pickfiles" class="migrator_button" href="#">Select migration file</a>
            </p>

        </div>
        <? endif; ?>
    </div>
    <div class="migrator__wrapper migrator--step2" style="display: none">
        <h1>Migrating</h1>
        <div class="migrator_alert">
            Do not close this page or use the back button!
        </div>
        <div class="migrator__content">
            <h3>Uploading migration file</h3>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 0" id="progress-bar1"></div>
            </div>
        </div>
        <div class="migrator__content">
            <h3>Preparing documents for import</h3>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 0" id="progress-bar3"></div>
            </div>
        </div>
        <div class="migrator__content">
            <h3>Preparing categories for import</h3>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 0" id="progress-bar2"></div>
            </div>
        </div>
        <div class="migrator__content">
            <h3>Importing categories</h3>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 0" id="progress-bar4"></div>
            </div>
        </div>
        <div class="migrator__content">
            <h3>Importing documents</h3>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 0" id="progress-bar5"></div>
            </div>
        </div>
    </div>
    <div class="migrator__wrapper migrator--step3" style="display: none">
        <h1>Migration completed</h1>
        <div class="migrator_success">
            Congratulations! The migration has been successfully completed!
        </div>
        <div class="migrator__content">
            <h3>Thank you for using DOCman!</h3>
        </div>
        <div class="migrator__content">
            <p>If you run into any problems please let us know on our <a href="http://www.joomlatools.com/membercenter/forums">forums</a>.</p>
        </div>
        <div class="migrator__content">
            <p><a class="migrator_button" href="<?= @route('option=com_docman&view=documents&layout=default&format=html'); ?>">Go to DOCman</a></p>
        </div>
    </div>
</div>
</div>