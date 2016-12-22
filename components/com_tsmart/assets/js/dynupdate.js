/**
 * dynupdate.js: Dynamic update of product content for tsmart
 *
 * @package	tsmart
 * @subpackage Javascript Library
 * @author Max Galt
 * @copyright Copyright (c) 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

if (typeof tsmart === "undefined")
	var tsmart = {};
jQuery(function($) {

    // Add to cart and other scripts may check this variable and return while
    // the content is being updated.
    tsmart.isUpdatingContent = false;
    tsmart.updateContent = function(url, callback) {

        if(tsmart.isUpdatingContent) return false;
        tsmart.isUpdatingContent = true;
        urlSuf='tmpl=component&format=html';
        var glue = '&';
        if(url.indexOf('&') == -1 && url.indexOf('?') == -1){
			glue = '?';
        }
        url += glue+urlSuf;
		jQuery.ajax({
            url: url,
            dataType: 'html',
            success: function(data) {
                var el = $(data).find(tsmart.containerSelector);
				if (! el.length) el = $(data).filter(tsmart.containerSelector);
				if (el.length) {
					tsmart.container.html(el.html());
                    tsmart.updateCartListener();
                    tsmart.updateDynamicUpdateListeners();

					if (tsmart.updateImageEventListeners) tsmart.updateImageEventListeners();
					if (tsmart.updateChosenDropdownLayout) tsmart.updateChosenDropdownLayout();
				}
				tsmart.isUpdatingContent = false;
				if (callback && typeof(callback) === "function") {
					callback();
				}
            }
        });
        tsmart.isUpdatingContent = false;
    }

    // GALT: this method could be renamed into more general "updateEventListeners"
    // and all other VM init scripts placed in here.
    tsmart.updateCartListener = function() {
        // init VM's "Add to Cart" scripts
		tsmart.product(jQuery(".product"));
        //tsmart.product(jQuery("form.product"));
		jQuery('body').trigger('updatetsmartProductDetail');
        //jQuery('body').trigger('ready');
    }

    tsmart.updL = function (event) {
        event.preventDefault();
        var url = jQuery(this).attr('href');
        tsmart.setBrowserNewState(url);
        tsmart.updateContent(url);
    }

    tsmart.upd = function(event) {
        event.preventDefault();
        var url = jQuery(this).attr('url');
        if (typeof url === typeof undefined || url === false) {
            url = jQuery(this).val();
        }
        if(url!=null){
			url = url.replace(/amp;/g, '');
            tsmart.setBrowserNewState(url);
            tsmart.updateContent(url);
        }
    };

	tsmart.updForm = function(event) {

		cartform = jQuery("#checkoutForm");
		carturl = cartform.attr('action');
		if (typeof carturl === typeof undefined || carturl === false) {
			carturl = jQuery(this).attr('url');
			console.log('my form no action url, try attr url ',cartform);
			if (typeof carturl === typeof undefined || carturl === false) {
				carturl = 'index.php?option=com_tsmart&view=cart'; console.log('my form no action url, try attr url ',carturl);
			}
		}
		urlSuf='tmpl=component';
		carturlcmp = carturl;
		if(carturlcmp.indexOf(urlSuf) == -1){
			var glue = '&';
			if(carturlcmp.indexOf('&') == -1 && carturlcmp.indexOf('?') == -1){
				glue = '?';
			}
			carturlcmp += glue+urlSuf;
		}

		cartform.submit(function() {
			jQuery(this).vm2front("startVmLoading");
			if(tsmart.isUpdatingContent) return false;
			tsmart.isUpdatingContent = true;
			//console.log('my form submit url',carturlcmp);
			jQuery.ajax({
				type: "POST",
				url: carturlcmp,
				dataType: "html",
				data: cartform.serialize(), // serializes the form's elements.
				success: function(datas) {

					if (typeof window._klarnaCheckout !== "undefined"){
						window._klarnaCheckout(function (api) {
							console.log(' updateSnippet suspend');
							api.suspend();
						});
					}


					var el = jQuery(datas).find(tsmart.containerSelector);
					if (! el.length) el = jQuery(datas).filter(tsmart.containerSelector);
					if (el.length) {
						tsmart.container.html(el.html());
						//tsmart.updateCartListener();
						//tsmart.updDynFormListeners();
						//tsmart.updateCartListener();

						if (tsmart.updateImageEventListeners) tsmart.updateImageEventListeners();
						if (tsmart.updateChosenDropdownLayout) tsmart.updateChosenDropdownLayout();
					}
					tsmart.setBrowserNewState(carturl);
					tsmart.isUpdatingContent = false;
					jQuery(this).vm2front("stopVmLoading");
					if (typeof window._klarnaCheckout !== "undefined"){
						window._klarnaCheckout(function (api) {
							console.log(' updateSnippet suspend');
							api.resume();
						});
					}
				},
				error: function(datas) {
					alert('Error updating cart');
					tsmart.isUpdatingContent = false;
					jQuery(this).vm2front("stopVmLoading");
				},
				statusCode: {
					404: function() {
						tsmart.isUpdatingContent = false;
						jQuery(this).vm2front("stopVmLoading");
						alert( "page not found" );
					}
				}
			});
			return false; // avoid to execute the actual submit of the form.
		});
	};

	tsmart.updFormS = function(event) {
		tsmart.updForm();
		jQuery("#checkoutForm").submit();
	}

	tsmart.updDynFormListeners = function() {

		jQuery('#checkoutForm').find('*[data-dynamic-update=1]').each(function(i, el) {
			var nodeName = el.nodeName;
			el = jQuery(el);
			//console.log('updDynFormListeners ' + nodeName, el);
			switch (nodeName) {
				case 'BUTTON':
					el[0].onchange = null;
					el.off('click',tsmart.updForm);
					el.on('click',tsmart.updForm);
				default:
					el[0].onchange = null;
					el.off('click',tsmart.updFormS);
					el.on('click',tsmart.updFormS);
					break;
			}
		});
	}

    tsmart.updateDynamicUpdateListeners = function() {
        jQuery('*[data-dynamic-update=1]').each(function(i, el) {
            var nodeName = el.nodeName;
            el = jQuery(el);
            //console.log('updateDynamicUpdateListeners '+nodeName, el);
            switch (nodeName) {
                case 'A':
					el[0].onclick = null;
                    el.off('click',tsmart.updL);
                    el.on('click',tsmart.updL);
                    break;
                default:
					el[0].onchange = null;
                    el.off('change',tsmart.upd);
                    el.on('change',tsmart.upd);
            }
        });
    }

    var everPushedHistory = false;
    var everFiredPopstate = false;
    tsmart.setBrowserNewState = function (url) {
        if (typeof window.onpopstate == "undefined")
            return;
        var stateObj = {
            url: url
        }
        everPushedHistory = true;
        try {
            history.pushState(stateObj, "", url);
        }
        catch(err) {
            // Fallback for IE
            window.location.href = url;
            return false;
        }
    }

    tsmart.browserStateChangeEvent = function(event) {
        // Fix. Chrome and Safari fires onpopstate event onload.
        // Also fix browsing through history when mixed with Ajax updates and
        // full updates.
        if (!everPushedHistory && event.state == null && !everFiredPopstate)
            return;

        everFiredPopstate = true;
        var url;
        if (event.state == null) {
            url = window.location.href;
        } else {
            url = event.state.url;
        }
        tsmart.updateContent(url);
    }
    window.onpopstate = tsmart.browserStateChangeEvent;

});
