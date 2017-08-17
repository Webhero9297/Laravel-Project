/**
 * LocalBitcoins.com site-wide Javascript
 */

// public utility functions
localBitcoins = {};

(function($) {

    "use strict";

    if (typeof(window.console) === 'undefined') {
        console = {};
        console.log = console.warn = console.error = function(a) {};
    }

    // http://stackoverflow.com/questions/3974827/detecting-touch-screen-devices-with-javascript
    var hasTouch = 'ontouchstart' in document.documentElement;
    window.hasTouch = hasTouch;

    var isNarrow = window.screen.availWidth < 800;

    // Export currently active language
    var lang = $("html").attr("lang");
    window.lang = lang;

    // Helper for determining max element height of selected elements
    $.fn.max = function(selector) {
        return Math.max.apply(null, this.map(function(index, el) {
            return selector.apply(el);
        }).get());
    };

    function deleteCookie(name) {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    };

    // http://stackoverflow.com/questions/10730362/javascript-get-cookie-by-name
    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return null;
    }

    // Make sure cookie value is uri
    function decodeCookieValue(val) {
        if(val.substring("%") >= 0) {
            return window.decodeURIComponent(val);
        }
    }
    window.decodeCookieValue = decodeCookieValue;

    // http://stackoverflow.com/a/13164238/315168
    function writeCookie(key, value, days) {
        var date = new Date();
        // Get unix milliseconds at current time plus number of days
        date.setTime(+date + (days * 86400000)); //24 * 60 * 60 * 1000
        window.document.cookie = key + "=" + '"' + value + '"' + "; expires=" + date.toGMTString() + "; path=/";
        return value;
    }

    var sendAnalyticsEvent = function(category, action, label, value) {
        if(value){
            _gaq.push(['_trackEvent', category, action, label, value]);
        }else{
            _gaq.push(['_trackEvent', category, action, label]);
        }
    };

    /**
     * Tracks a custom JavaScript click when the click is not targeting a real link
     *
     * @param {jQuery.Event} e orignal click() event
     */
    function trackEvent(e, category, action) {
        var id = $(e.target).attr("id");
        sendAnalyticsEvent(category, action, id);
    }

    /** Pop-upify some links */
    function popuify() {

        //var psinet = getPSINETScore();
        //console.log("Psinet:" + psinet);


        var passwordIndicatorInitialized = false;

        if (!$.support.cors) {
            // IE9 and below
            // CDN'd Bootstrap does not allow AJAX loading without setting CORS headers
            // http://stackoverflow.com/questions/10232017/ie9-jquery-ajax-with-cors-returns-access-is-denied
            return;
        }

        // Don't do modals on mobile
        if (hasTouch && isNarrow) {
            return;
        }
        // (Temporary?) removing Sign Up modal: May, 2016

        // Don't popuify register link on register page
        // if (window.location.href.indexOf("/register/") >= 0 || window.location.href.indexOf("/accounts/login/") >= 0) {
        //     return;
        // }

        // $(".register-link").click(function(e) {
        //     e.preventDefault();

        //     // Track register link click by its id
        //     var id = $(e.target).attr("id");

        //     var nexturl = "?next=" + window.location.pathname;

        //     var remote = window.exchange.serverUrl + "reg_popup/" + nexturl;

        //     // XXX: Fix this proper by giving the urls from the server-side
        //     if(lang == "es") {
        //         remote = window.exchange
        //         .serverUrl + "es/reg_popup/";
        //     }

        //     $('#register-modal .modal-body').load(remote);


        //     $('#register-modal').modal({
        //         keyboard: true
        //     });

        //     // Focus on first field on opening
        //     $("#register-modal").on("shown", function() {
        //         $("#register-modal #id_username").focus();
        //         if (!passwordIndicatorInitialized) {
        //             passwordStrength($("#id_password1"));
        //             passwordIndicatorInitialized = true;
        //         }
        //     });

        // });

    }

    // attach a very simple password strength checker to $selector
    function passwordStrength($selector) {
        var $response = $("<span></span>");
        $response.addClass("pwstr");
        $selector.after($response);
        $selector.keyup(function() {
            var $el = $(this);
            var len = $el.val().length;
            if (len < 8) {
                $response.removeClass("pwstr-medium pwstr-good pwstr-better");
                $response.addClass("pwstr-weak");
                $response.html("Very weak");
            } else if (len < 16) {
                $response.removeClass("pwstr-weak pwstr-good pwstr-better");
                $response.addClass("pwstr-medium");
                $response.html("Medium");
            } else if (len < 25) {
                $response.removeClass("pwstr-medium pwstr-weak pwstr-better");
                $response.addClass("pwstr-good");
                $response.html("Good");
            } else {
                $response.removeClass("pwstr-medium pwstr-good pwstr-weak");
                $response.addClass("pwstr-better");
                $response.html("Better");
            }
        });
    }
    window.passwordStrength = passwordStrength;

    // Equalize the height of front page boxes
    function tuneFrontPage() {

        // http://stackoverflow.com/a/9189443/315168
        $('.frontpage-promo .well').height(function() {
            var maxHeight = $('.frontpage-promo .well').max(function() {
                return $(this).height();
            });
            return maxHeight;
        });
    }

    // Equalize the height of front page boxes
    function tuneAdPage() {

        // http://stackoverflow.com/a/9189443/315168
        $('#ad-tips .well').height(function() {
            var maxHeight = $('#ad-tips .well').max(function() {
                return $(this).height();
            });
            return maxHeight;
        });
    }

    function tuneSplash() {

        function fixSplashScreenHeight() {
            $('.super-front-page .super-front-page-column').height(function() {
                var maxHeight = $('.super-front-page .super-front-page-column').max(function() {
                    return $(this).height();
                });
                return maxHeight;
            });
        }

        fixSplashScreenHeight();
    }

    /** Migrated body <script> */
    function oldFixes() {

        if (!window.exchange) {
            throw new Error("JavaScript dynamic options not correctly passed by HTML");
        }

        $('ul.nav > li > a[href="' + window.exchange.fullPath + '"]').parent().addClass("active");
        $(".noscripthidden").show();
        $(".noscriptvisible").hide();
    }

    /**
     * Extract zipcode, city and country from Google Geocoder response
     *
     * @param results {Object} Google Places API output
     *
     * @return {Object}      {zipcode, city, countryCode, locationString}
     */
    function splitLocation(results) {

        var result = {};
        var lastBit = results[results.length - 1];
        var countryCode = null;
        var i;
        var locationBit;

        // Store lat and long
        result.lat = results.geometry.location.lat();
        result.lon = results.geometry.location.lng();

        // Full text geocoded response
        result.locationString = results.formatted_address;

        console.log(results);

        // Street name + postal code
        result.streetAddress = "";

        // Go for less accurate to more accurate
        for (i = results.address_components.length - 1; i >= 0; i--) {

            locationBit = results.address_components[i];

            console.log(locationBit);

            // Extract country code from geo lookup
            if ($.inArray("country", locationBit.types) >= 0) {
                result.countryCode = locationBit.short_name; // FI
            }

            // Extract city from geo lookup
            // http://stackoverflow.com/a/6335080/315168
            if ($.inArray("administrative_area_level_1", locationBit.types) >= 0 ||
                $.inArray("administrative_area_level_3", locationBit.types) >= 0 ||
                $.inArray("locality", locationBit.types) >= 0) {
                result.city = locationBit.long_name; // FI
            }

            if ($.inArray("street_number", locationBit.types) >= 0 ||
                $.inArray("postal_code", locationBit.types) >= 0 ||
                $.inArray("route", locationBit.types) >= 0) {
                result.streetAddress += locationBit.long_name;
            }
        }

        // Fallback to the generic place name if the Google Places didn't provide city
        // Helsingin Messukeskus case
        if (!result.city && results.address_components.length === 1) {
            if (locationBit.long_name !== results.name) {
                // Avoid Finland, Finalnd
                result.locationString = results.name + ", " + results.formatted_address; // Messukeskus, Finland
                result.city = results.name;
            }
        }

        // Would fail specularly later...
        if (!result.countryCode) {
            throw new Error("Cannot handle a place without a country code");
        }

        return result;
    }

    /**
     * Store the chosen location in the cookies.
     * @param  {String} address splitLocation() result
     */
    function storeLocation(address) {
        writeCookie("lat", address.lat, 30);
        writeCookie("lon", address.lon, 30);
        // Use URI encoding scheme on the plain text strings,
        // as IE encodes latin1, other browsers encode UTF-8.
        // Try to clean up the mess on the server-side.
        writeCookie("location_string", window.encodeURIComponent(address.locationString), 30);
        writeCookie("countrycode", address.countryCode, 30);
    }

    // export
    window.splitLocation = splitLocation;

    /**
     * Front page geo-location helper.
     *
     * 1. Page asks permission to look up users geolocation
     * 2. If permission given use HTML5 API to get lat and lng
     * (delay 0-30 seconds)
     * 3. Reverse engineer there coordinates to address using Google Places API
     * 4. a) If process took less than 2 second automatically refresh ads list using AJAX
     * 4. b) If process took some time ask the user before refreshing the list
     *
     *
     */
    function locateByBrowser() {

        if (!window.navigator.geolocation) {
            // Unsupported
            return;
        }

        if (Date.now === undefined) {
            // IE8
            return;
        }

        var allowedGeolocation = false;

        // Timer when geolocation was started
        var geoStarted = Date.now();

        var geoOpts = {
            enableHighAccuracy: false,
            maximumAge: 24 * 60 * 60 * 1000, //  24hours
            timeout: 30000
        };

        var geocoder = new google.maps.Geocoder();

        // Reverse geocode the gPS coordinates to get the location string
        function reverseGeocode(lat, lng) {

            var latlng = new google.maps.LatLng(lat, lng);

            geocoder.geocode({
                'latLng': latlng
            }, function(results, status) {

                var address = window.splitLocation(results[0]);

                if (status === google.maps.GeocoderStatus.OK) {
                    submitChangeLocation(address);
                }
            });
        }

        // geolocation API get us location, reverse it to address
        function geoSuccess(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            reverseGeocode(latitude, longitude);
        }

        function geoFail(error) {
            console.log(error);
            window.alert("Could not use browser locationing. Please change location by typing in the address.");
            $("#change-location-locating").hide();
        }

        $("#change-location-locating").show();

        // Start HTML5 geolocating
        window.navigator.geolocation.getCurrentPosition(geoSuccess, geoFail, geoOpts);
    }


    /**
     * Make Google Places Autocomplete widget behave on enter press
     *
     * http://stackoverflow.com/a/11703018/315168
     */
    function createPlaceAutocompleteSelectFirst(input, types) {

        if (!input) {
            throw new Error("Input was undefined");
        }

        // store the original event binding function
        var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

        function addEventListenerWrapper(type, listener) {
            // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
            // and then trigger the original listener.
            if (type === "keydown") {
                var orig_listener = listener;
                listener = function(event) {

                    var suggestion_selected = $(".pac-item.pac-selected").length > 0;

                    if (!suggestion_selected) {
                        // Looks like Google changed their code in some point and the old
                        // class names does not match anymore
                        suggestion_selected = $(".pac-item-refresh.pac-selected").length > 0;
                    }

                    // Google changed again
                    if (!suggestion_selected) {
                        suggestion_selected = $(".pac-item-selected").length > 0;
                    }


                    var suggestion_open = $(".pac-container > *").size() > 0;

                    if ((event.which === 13 || event.which === 9) && !suggestion_selected && suggestion_open) {

                        event.preventDefault();
                        var simulated_downarrow = $.Event("keydown", {
                            keyCode: 40,
                            which: 40
                        });
                        orig_listener.apply(input, [simulated_downarrow]);
                        orig_listener.apply(input, [event]);
                        return false;
                    }

                    orig_listener.apply(input, [event]);
                };
            }

            _addEventListener.apply(input, [type, listener]);
        }

        input.addEventListener = addEventListenerWrapper;
        input.attachEvent = addEventListenerWrapper;

        var autocomplete = new google.maps.places.Autocomplete(input);
        return autocomplete;
    }
    window.createPlaceAutocompleteSelectFirst = createPlaceAutocompleteSelectFirst;


    /**
     * Based on the location form inputs (hidden fields), change the user location.
     *
     * @param {Object} address Parsed Google Places address object from splitLocation()
     */
    function submitChangeLocation(address) {
        storeLocation(address);

        // Create a form which sends the chosen location to server via HTTP POST
        var form = $("<form>");
        form.attr("method", "POST");
        form.attr("action", "/places_shareable_lookup/");
        form.attr("style", "display: none");
        var csrf = $("<input type='hidden' name='csrfmiddlewaretoken'>");
        form.append(csrf);
        var csrfValue = $("#change-location-wrapper input[name='csrfmiddlewaretoken']").attr("value");
        if(!csrfValue) {
            debugger;
            throw new Exception("CSRF token missing");
        }
        csrf.attr("value", csrfValue);
        $(document.body).append(form);
        form.submit();
    }

    /**
     * Google Place location box on front page, buy bitcoins and sell bitcoins pages.
     *
     * 1) Setup Google Places JavaScript auto-completion n the widget
     * 2) Setup Boostrap tooltip hint on the widget
     * 3) Handle custom location form gotos via window.customLocationGotoHandler
     */
    function setupLocationAutocomplete() {

        if (!window.google) {
            // Google Places API not loaded
            return;
        }

        var form = $("#change-location-form");

        var input = form.find('[name="place"]');
        if (!input.length) {
            return;
        }
        var autocomplete = createPlaceAutocompleteSelectFirst(input[0]);

        input.tooltip();

        input.keypress(function(event) {
            return event.keyCode !== 13;
        });

        // When user selects an entry in the list kick in the magic
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            var address = window.splitLocation(place);

            // Pure country code lookup, quick jump to country listing
            if (address.countryCode && !address.city && !address.streetAddress) {

                // Store the location for the server-side processing
                storeLocation(address);
                window.location = "/country/" + address.countryCode.toUpperCase();
                return;
            }

            if (window.customLocationGoToHandler) {
                window.customLocationGoToHandler(place, address);
                return;
            }

            submitChangeLocation(address);
        });
    }


    /**
     * In listing rows, make <tr>s clickable
     *
     */
    function makeListingRowsClickable() {
        $("tr.clickable").click(function(e) {

            // Only do the magic on left mouse button
            if (e.which !== 1) {
                return;
            }

            var href = $(this).find('.megabutton, .primary-link').attr("href");

            if (href) {
                window.location = href;
            }
        });
    }

    /**
     * IE8 and FF3.6 do not correctly handle navbar positioning.
     *
     * Fixed navbar overlays logged in nav bar because of CSS bugs.
     *
     */
    function fixIE8NavBar() {

        function checkNavBarHidden() {
            var bottom = $(".navbar-fixed-top").offset().top + $(".navbar-fixed-top").height();

            var top = $("#logged_in_bar").offset();

            if (top) {
                // No idea why happens.
                top = top.top;
            } else {
                top = 999;
            }

            if (top < bottom) {
                $("#logged_in_bar").css("padding-top", "50px");
            }
        }

        if (window.OLD_IE) {
            $(window).resize(function() {
                checkNavBarHidden();
            });

            // Fix on load
            checkNavBarHidden();
        }

    }

    /**
     * Toggle on/off arrow for Twitter Bootstrap collapsibles.
     *
     * Multi-collapsible-friendly; supports several collapsibles in the same group, on the same page.
     */
    function animateCollapsibles() {

        $('.collapse').on('show', function() {
            var $t = $(this);
            var header = $("a[href='#" + $t.attr("id") + "']");
            header.find(".fa-chevron-right").removeClass("fa-chevron-right").addClass("fa-chevron-down");
        }).on('hide', function() {
            var $t = $(this);
            var header = $("a[href='#" + $t.attr("id") + "']");
            header.find(".fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-right");
        });
    }

    function handleOnlineStatus() {
        $(".online-status").tooltip();
    }

    // Set special marker class to hide the front page form on IE8
    function handleFrontPageFormIE8() {
        if (window.OLD_IE) {
            $("body").addClass("msie8");
        }
    }

    var sendEventFromPreviousRequest = function() {
        if(window.analyticsData && !jQuery.isEmptyObject(window.analyticsData.event)){
            var e = analyticsData.event;
            if(e.value){
                sendAnalyticsEvent(e.category, e.action, e.label, e.value);
            }else{
                sendAnalyticsEvent(e.category, e.action, e.label);
            }
        }
    };

    /**
     * Handle notifications cross-tab friendly manner with visual and audiable feedback.
     */
    function handleNotifications() {

        if (!$(".notifications_dropdown0").size()) {
            // Not logged in
            return;
        }

        // Set menu initial mute state
        handleMuteState();

        // For anonymous users and users without opened trades do not do notifications
        // Also notifications may be disabled in the site settings.
        var needNotifications = $(".notification-timestamp").attr("data-need-notifications") == "true";
        if(!needNotifications) {
            return false;
        }

        // In notifications menu, show mute or unmute
        function handleMuteState() {
            if (notifications.getAudible()) {
                $(".mute-notifications").show();
                $(".unmute-notifications").hide();
            } else {
                $(".mute-notifications").hide();
                $(".unmute-notifications").show();
            }

            $(".mute-notifications").click(function() {
                notifications.setAudible(false);
                handleMuteState();
            });
            $(".unmute-notifications").click(function() {
                notifications.setAudible(true);
                handleMuteState();
            });
        }

        // Simulate that we get changes from the server on 1/3 of polls
        function fetchData(callback) {

            var url = $(".notifications_dropdown0").attr("data-notification-url");
            var timestamp = $(".notification-timestamp").attr("data-timestamp");

            var buster = Math.random() * 10000;
            $.ajax({
                url: url,
                data: {"last-timestamp": timestamp, "cache-buster": buster},
                success: function(data) {

                    // 304 not modified
                    if(!data) {
                        return;
                    }

                    data = data.trim();

                    // A valid response, but does not actually
                    // contain any notifications
                    if(!data) {
                        return;
                    }

                    var $data = $(data);
                    var silent;

                    if($data.find(".notification-menu-entry").length === 0) {
                        // We got response all notifications seen
                        silent = true;
                    } else {
                        // New notifications
                        silent = false;
                    }

                    callback(data, silent, false);
                },
                error: function(xhr) {
                    // Got error (usually 403 logged out) from the server,
                    // time to stop polling
                    callback("", false, true);
                }
            });
        }

        // Simulate changes HTML on the page with new notification list
        function updatePage(data, source, action) {
            $(".notifications_dropdown0").html(data);
            handleMuteState();
        }

        function parseAndShowNotifications(oldValue, newValue) {
            if (!window.exchange.webNotificationsEnabled) {
                return;
            }

            // Use difference between old and new values to check what messages are new ones
            var oldMessages = $(oldValue).find(".unread-msg");
            var newMessages = $(newValue).find(".unread-msg");
            var notifs = [];
            for (var i = 0; i < newMessages.length; ++ i) {
                var found = false;
                var newMessage = newMessages[i];
                for (var j = 0; j < oldMessages.length; ++ j) {
                    var oldMessage = oldMessages[j];
                    if (oldMessage.firstChild.href == newMessage.firstChild.href && oldMessage.firstChild.innerHTML == newMessage.firstChild.innerHTML) {
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    var url = newMessage.firstChild.href;
                    var msg = newMessage.firstChild.innerHTML;

                    var notification = new Notification("LocalBitcoins.com", {
                        icon: "/static/img/touch-icon-144.png",
                        tag: url + msg,
                        body: msg
                    });
                }
            }
        }

        function showExternalNotifications(oldValue, newValue) {
            if (Notification && Notification.permission === "granted") {
                parseAndShowNotifications(oldValue, newValue);
            } else if (Notification && Notification.permission !== "denied") {
                Notification.requestPermission(function (status) {
                    if (Notification.permission !== status) {
                        Notification.permission = status;
                    }
                    if (status === "granted") {
                        parseAndShowNotifications(oldValue, newValue);
                    }
                });
            }
        }

        function detectWebNotificationEnabling() {
            var checkbox = $('#id_enable_web_notifications');
            checkbox.on("click", function() {
                if($(this).is(":checked")) {
                    if (Notification && Notification.permission !== "denied") {
                        Notification.requestPermission(function (status) {
                            if (Notification.permission !== status) {
                                Notification.permission = status;
                            }
                        });
                    }
                }
            });
        }

        // Start notification poller
        notifications.init({
            interval: 45 * 1000,
            fetcher: fetchData,
            updater: updatePage,
            showExternalNotifications: showExternalNotifications,
            debug: false,
            hasChanged: function(oldValue, newValue) {

                // Assume store notification value is the DOM string dump of the notification HTML

                if (!oldValue) {
                    return false;
                }
                // reparse DOM to get rid of all extra,
                // so that we don't get alarms when the notification menu is source code changes,
                // but actual DOM matches (whitespaces, settings style attribute, etc.)
                oldValue = $(oldValue.trim()).find(".notification-menu-entry");
                newValue = $(newValue.trim()).find(".notification-menu-entry");

                // .html() gets the source code only for the first element of jQuery selection,
                // thus if we have two messages from the same person,
                // the second notification has same DOM and would not trigger change.
                // We work around this by converting all menu body notifications to joined HTML string.
                oldValue = oldValue.map(function() {
                    return this.outerHTML;
                }).get().join();
                newValue = newValue.map(function() {
                    return this.outerHTML;
                }).get().join();

                // TODO: Add a separate handler we actually have a notifications in the payload
                // and e.g. not logged out response
                return oldValue !== newValue;
            }
        });

        // Pass the latest notification data, as received with the <body>
        // to the other tabs.
        var notificationData = $(".notifications_dropdown0").html();
        notifications.trigger("notification-received", notificationData, true);

        // When the page is opened, assume we get the notification payload
        // in the HTML body and send a message that blinking should be stopped.
        notifications.trigger("notification-noticed");

        // This only matters when we are on settings page
        detectWebNotificationEnabling();

    }


    /**
     * New change location look up at the bottom of index.html, buy_bitcoins, sell_bitcoins
     */
    function handleChangeLocation() {

        // Open up Places form
        $("#change-location").click(function(e) {
            e.preventDefault();
            $("#change-location").hide();
            $("#change-location-form").slideDown();
            return false;
        });

        // Toggle HTML5 geolocation
        $("#change-location-locate").click(function(e) {
            e.preventDefault();
            $("#change-location-locate").hide();
            locateByBrowser();
            return false;
        });

        // Setup places widget
        setupLocationAutocomplete();
    }


    // Intall JavaScript logic
    $(document).ready(function() {
        oldFixes();
        popuify();
        makeListingRowsClickable();
        animateCollapsibles();
        handleOnlineStatus();
        handleFrontPageFormIE8();
        handleChangeLocation();
        sendEventFromPreviousRequest();
    });

    // Install post-page render logic
    $(window).load(function() {
        tuneFrontPage();
        tuneSplash();
        tuneAdPage();
        fixIE8NavBar();
        handleNotifications();
    });

})(jQuery);


// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};


// https://docs.djangoproject.com/en/1.7/ref/contrib/csrf/#ajax
// using jQuery
function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie != '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) == (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}
