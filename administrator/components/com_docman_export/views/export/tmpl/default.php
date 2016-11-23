<?php
/**
 * @package     DOCman Export
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('_JEXEC') or die; ?>

<script type="text/javascript">
jQuery(function($) {
    var showStep = function(step) {
            $('.migrator__wrapper').hide();
            $('.migrator--step'+step).show();

            $('.migrator__steps__list__item').removeClass('item--active');
            $('.migrator__steps__list__item:nth-child('+step+')').addClass('item--active');
        },
        showError = function(message) {
            $('.migrator_alert').fadeOut('fast', function() {
                $(this).html(message).fadeIn('fast');
            });
            $('.bar').removeClass('bar-success').addClass('bar-danger')
                .parent().removeClass('active');
        },
        exportCategories = function() {
            var exporter = new DocmanExport({
                url: '<?php echo JRoute::_('index.php?option=com_docman_export&task=export&type=categories&format=json', false); ?>'
            });

            exporter.bind('exportUpdate', function(e, data) {
                $('#progress-bar').css('width', data.completed + '%');
            });

            exporter.bind('exportFailed', function(e, data) {
                showError(data.error);
            });

            exporter.bind('exportComplete', function(e, data) {
                $('#progress-bar').css('width', '100%').addClass('bar-success')
                    .parent().removeClass('active');

                exportDocuments();
            });

            exporter.start();
            $('#export-btn').addClass('disabled').click(function(e) {
                e.preventDefault();
            });
        },
        exportDocuments = function() {
            var exporter = new DocmanExport({
                url: '<?php echo JRoute::_('index.php?option=com_docman_export&task=export&type=documents&format=json', false); ?>'
            });

            exporter.bind('exportUpdate', function(e, data) {
                $('#progress-bar2').css('width', data.completed + '%');
            });

            exporter.bind('exportFailed', function(e, data) {
                showError(data.error);
            });

            exporter.bind('exportComplete', function(e, data) {
                $('#progress-bar2').css('width', '100%').addClass('bar-success')
                    .parent().removeClass('active');

                $('#progress-bar3').css('width', '50%');

                setTimeout(function() {
                    $.ajax('<?php echo JRoute::_('index.php?option=com_docman_export&task=prepare&format=json', false); ?>', {
                        type: 'get',
                        timeout: 30000,
                        tryCount : 0,
                        retryLimit : 2,
                        error: exporter.callbacks.error,
                        success: function() {
                            $('#progress-bar3').css('width', '100%').addClass('bar-success')
                                .parent().removeClass('active');

                            showStep(3);

                            window.location = "<?php echo JRoute::_('index.php?option=com_docman_export&task=export.download&format=raw', false)?>";
                        }
                    });

                }, 3000);
            });

            exporter.start();
        };

    $('#export-btn').one('click', function() {
        $(this).attr('disabled', 'disabled');
        showStep(2);
        exportCategories();
    });
});
</script>

<div class="koowa">
    <div class="migrator">
        <div class="migrator__header">
            <img class="joomlatools_logo" src="<?php echo JUri::root(true).'/media/com_docman_export/images/joomlatools_logo_80px.png'; ?>" alt="Joomlatools logo" /> DOCman <strong>migration tool</strong>
        </div>
        <div class="migrator__steps">
            <ul class="migrator__steps__list">
                <li class="migrator__steps__list__item item--active">Start</li>
                <li class="migrator__steps__list__item">Export</li>
                <li class="migrator__steps__list__item">Completed</li>
            </ul>
        </div>
        <div class="migrator__wrapper migrator--step1">
            <h1>Start export process</h1>
            <div class="migrator__content">
                <p>If you run into any problems please let us know on our <a href="http://www.joomlatools.com/membercenter/forums">forums</a>.</p>
            </div>
            <?php
            $missing = $this->getMissingDependencies();
            if ($missing): ?>
                <div class="alert alert-error">
                    <h3>Missing Requirements</h3>
                    <ul>
                    <?php foreach ($missing as $key => $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
            <div class="migrator__content">
                <p style="display:block;"><a id="export-btn" class="migrator_button" href="#">
                    Start export process
                </a></p>
            </div>
            <?php endif; ?>
        </div>
        <div class="migrator__wrapper migrator--step2" style="display: none">
            <h1>Exporting</h1>
            <div id="message-container" class="migrator__content"></div>
            <div class="migrator_alert">
                <p>Do not close this page or use the back button during export process!</p>
            </div>

            <div class="migrator__content">
                <h3>Exporting categories</h3>
                <div class="progress progress-striped active">
                    <div class="bar" style="width: 0%" id="progress-bar"></div>
                </div>
            </div>
            <div class="migrator__content">
                <h3>Exporting documents</h3>
                <div class="progress progress-striped active">
                    <div class="bar" style="width: 0%" id="progress-bar2"></div>
                </div>
            </div>
            <div class="migrator__content">
                <h3>Preparing archive</h3>
                <div class="progress progress-striped active">
                    <div class="bar" style="width: 0%" id="progress-bar3"></div>
                </div>
            </div>
        </div>
        <div class="migrator__wrapper migrator--step3" style="display: none">
            <h1>Export completed</h1>
            <div class="migrator_success">
                Congratulations! The export has been successfully completed! Your browser should automatically download the exported file now.
                You will be asked for this file in DOCman Importer.
            </div>
            <div class="migrator__content">
                <p>If you run into any problems please let us know on our <a href="http://www.joomlatools.com/membercenter/forums">forums</a>.</p>
            </div>
            <div class="migrator__content">
                <p><a class="migrator_button" href="<?php echo JRoute::_('index.php'); ?>">Go to home page</a></p>
            </div>
        </div>
    </div>
</div>
