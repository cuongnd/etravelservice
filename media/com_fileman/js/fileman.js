/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

!function ($) {

    if(!this.Fileman) this.Fileman = {};

    Fileman.trackEvent = function(options) {
        options = $.extend({
            category: 'FILEman',
            action: 'View',
            label: null,
            value: null,
            noninteraction: false
        }, options);

        if (typeof _gaq !== 'undefined' && _gat._getTrackers().length) {
            _gaq.push(function() {
                var tracker = _gat._getTrackers()[0];
                tracker._trackEvent(options.category, options.action, options.label, options.value, options.noninteraction);
            });
        }
    };

}(window.jQuery);