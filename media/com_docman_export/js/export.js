(function ($) {

this.DocmanExport = function(config) {
    var my = {
        init: function (config) {
            config = config || {};

            this.event_container = $('<div />'); // Events are fired on this element
            this.container = config.container || '#activities-export';
            this.init_offset = config.init_offset || 0;
            this.url = config.url;
            this.timeout = config.timeout || 20000;
            this.exported = 0;

            this.callbacks = {};

            var self = this;
            this.callbacks.success = function (data) {console.log(data);
                if (typeof data === 'string' && typeof Json.evaluate === 'function') {
                    data = Json.evaluate(data);
                }

                // Update progress bar.
                my.update(data);

                if (data.remaining && data.next) {
                    my.request(data.next);
                } else {
                    // Export completed.
                    self.event_container.trigger('exportComplete', $.extend({}, data));
                }
            };

            this.callbacks.error = function (data, textStatus) {
                if (textStatus == 'timeout') {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        //try again
                        $.ajax(this);
                    }

                    return;
                }

                var response = $.parseJSON(data.responseText);

                self.event_container.trigger('exportFailed', $.extend({}, response));
            };
        },

        update: function (data) {
            // Update total exported amount.
            this.exported += parseInt(data.exported);
            var completed = 100;
            if (data.remaining) {
                completed = parseInt(this.exported * 100 / (this.exported + parseInt(data.remaining)));
            }
            this.event_container.trigger('exportUpdate', $.extend({completed: completed}, data));
        },

        start: function () {
            this.request(this.url + '&offset=' + this.init_offset);
        },

        request: function (url) {
            $.ajax(url, {
                type: 'get',
                timeout: this.timeout,
                success: this.callbacks.success,
                error: this.callbacks.error,
                tryCount : 0,
                retryLimit : 2
            });
        },

        bind: function (event, callback) {
            this.event_container.on(event, callback);
        }
    };

    my.init(config);

    return my;
};

})(jQuery);