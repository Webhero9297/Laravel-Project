$(document).ready(function() { 
    $('#currency').change(function(){
        $('.curr').html($('#currency').val());
    });    

    if ( json_listing != 'NULL' ) {
        $('#id').val(json_listing['id']);

        //select value
        $('#user_type').val(json_listing['user_type']);
        $('#coin_type').val(json_listing['coin_type']);
        $('#id_ad-place').val(json_listing['location']);
        $('#payment_method').val(json_listing['payment_method']);
        $('#currency').val(json_listing['currency']);
        $('.curr').html(json_listing['currency']);

        //input value
        var coin_amount = json_listing['coin_amount'];
        var rep_val = (parseFloat(coin_amount * 1 + coin_amount * 0.005)).toFixed(6);
        $('#coin_amount').val(json_listing['coin_amount']);
        $('#fee_amount').val(rep_val);
        $('#payment_name').html(json_listing['payment_name']);
        $('#terms_of_trade').html(json_listing['terms_of_trade']);
        $('#payment_details').html(json_listing['payment_details']);
        $('#min_transaction_limit').val(json_listing['min_transaction_limit']);
        $('#max_transaction_limit').val(json_listing['max_transaction_limit']); 
        $('#price_equation').val(json_listing['price_equation']);
    }
//console.log(json_listing,( json_listing == 'NULL' ));
    $( "#coin_amount" ).keyup(function() { 
         if($.isNumeric($( "#coin_amount" ).val())){
            var val = $( "#coin_amount" ).val();
            var val_arr = val.toString().split('.');
            if(val_arr[val_arr.length-1].length > 6){
                slice_str = val.slice(0, val.length-1);
                $( "#coin_amount" ).val(slice_str);
                var rep_val = (parseFloat(slice_str * 1 + slice_str * 0.005)).toFixed(6);
            }else{
                var rep_val = (parseFloat(val * 1 + val * 0.005)).toFixed(6);
            }
            $( "#fee_amount" ).val(rep_val);
         }else{
            alert("Must be Number!");
            $( "#coin_amount" ).val('');
         }
    });

    $('#id_ad-place').keypress(function(){
        var input = document.getElementById('id_ad-place');
        var autocomplete = window.createPlaceAutocompleteSelectFirst(input);

        // When user selects an entry in the list kick in the magic
        google.maps.event.addListener(autocomplete, 'place_changed', function() {

            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            var location = window.splitLocation(place);
//            alert(location.countryCode);

            // chooceCurrency(location.countryCode);

            // var form = $("#ad-form");
            // form.find("#id_ad-lat").val(place.geometry.location.lat());
            // form.find("#id_ad-lon").val(place.geometry.location.lng());
            // form.find("#id_ad-location_string").val(location.locationString);
            // form.find("#id_ad-countrycode").val(location.countryCode);
            // form.find("#id_ad-countrycode").change();
            // form.find("#id_ad-city").val(location.city);

        });    
    });
});

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


                //var suggestion_open = $(".pac-container > *").size() > 0;

                if ((event.which === 13 || event.which === 9)) {

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