(function ($) {
    this.DocmanMigrator = Koowa.Class.extend({});

    this.DocmanMigrator.Chunker = Koowa.Class.extend({
        completed: 0,
        getOptions: function() {
            var self = this;

            return $.extend(this.supr(), {
                event_container: $('<div />'), // Events are fired on this element
                init_offset: 0,
                url: '',
                timeout: 30000,
                request: {
                    type: 'get',
                    data: {}
                },
                callbacks: {
                    success: function (data) {
                        // Update progress bar.
                        self.update(data);

                        if (data.remaining && data.next) {
                            self.request(data.next);
                        } else {
                            self.trigger('processComplete', $.extend({}, data));
                        }
                    },
                    error: function (data, textStatus) {
                        if (textStatus == 'timeout') {
                            this.tryCount++;
                            var request = this;
                            if (this.tryCount <= this.retryLimit) {
                                //try again
                                setTimeout(function() {
                                    $.ajax(request);
                                }, 5000);
                            } else {
                                self.trigger('processFailed', {error: 'Request timed out'});
                            }

                            return;
                        }

                        var response = $.parseJSON(data.responseText);

                        self.trigger('processFailed', $.extend({}, response));
                    }
                }
            });
        },
        initialize: function(options){
            this.supr();
            this.setOptions(options);
        },
        update: function (data) {
            // Update total exported amount.
            this.completed += parseInt(data.completed, 10);
            var percentage = 100;
            if (data.remaining) {
                percentage = parseInt(this.completed * 100 / (this.completed + parseInt(data.remaining, 10)), 10);
            }

            this.trigger('processUpdate', $.extend({percentage: percentage}, data));
        },

        start: function () {
            this.request(this.options.url + '&offset=' + this.options.init_offset);
        },

        request: function (url) {
            $.ajax(url, {
                type: this.options.request.type,
                data: this.options.request.data,
                timeout: this.options.timeout,
                success: this.options.callbacks.success,
                error: this.options.callbacks.error,
                tryCount : 0,
                retryLimit : 4
            });
        },

        bind: function (event, callback) {
            this.options.event_container.on(event, callback);

            return this;
        },
        trigger: function (event, data) {
            this.options.event_container.trigger(event, data);

            return this;
        }
    });

})(jQuery);