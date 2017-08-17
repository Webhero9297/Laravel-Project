var seemore_flag = -1;
function JObject() {
}
JObject.prototype = {
    init : function() {
        $('#id_ad-place').val(real_location);
        this.initEventListen();
        this.loadListingData(1);
    },
    initEventListen : function () {
        $('#search-btn').click(j_obj.doOnClickSearchButtonClick);
        $('button.see-more').click(j_obj.doOnSetSeeMoreFlag);
        $('button.buy').click(j_obj.doCreateContractAndGoTransaction);
        $('button.view').click(j_obj.doViewMessages);
        $('.menu-currency').click(j_obj.dochangecoin);
    },
    doOnClickSearchButtonClick : function (seemore_flag) {
        j_obj.loadListingData(0,seemore_flag);
         $('#search_form').collapse('hide');
    },
    doOnSetSeeMoreFlag : function() {
        seemore_flag = $(this).attr('prop'); 
        j_obj.loadListingData(0);
    },
    doCreateContractAndGoTransaction : function (param) {
        var _token = $('meta[name=csrf-token]').attr('content');
        var form = document.createElement("form");
        var element1 = document.createElement("input"); 
        var element2 = document.createElement("input"); 
    
        form.method = "POST";
        form.action = "buy";   

        element1.value=param;
        element1.type = "hidden";
        element1.name="param";
        form.appendChild(element1);  

        element2.value=_token;
        element2.type = "hidden";
        element2.name="_token";
        form.appendChild(element2);

        document.body.appendChild(form);

        form.submit();
    },
    doViewMessages : function (param) {
        var _token = $('meta[name=csrf-token]').attr('content');
        var form = document.createElement("form");
        var element1 = document.createElement("input"); 
        var element2 = document.createElement("input"); 
    
        form.method = "POST";
        form.action = "trademessage";   

        element1.value=param;
        element1.type = "hidden";
        element1.name="param";
        form.appendChild(element1);  

        element2.value=_token;
        element2.type = "hidden";
        element2.name="_token";
        form.appendChild(element2);

        document.body.appendChild(form);

        form.submit();
    },
    dochangecoin : function() {
        var coin = $(this).attr('id');
        $('#coin_type').val(coin);
        j_obj.loadListingData(0);
    },
    loadListingData : function (flag) {
        var _token = $('meta[name=csrf-token]').attr('content');
        var coin_amount = $('#coin_amount').val();
        var coin_type = $('#coin_type').val();
        if(coin_type == "btc"){
            $('.tb-title').removeClass('eth-color');
            $('.tb-title').addClass('btc-color');
        }else{
            $('.tb-title').removeClass('btc-color');
            $('.tb-title').addClass('eth-color');
        }
        var location = $('#id_ad-place').val();
        var payment_method = $('#payment_method').val();
        $.post('getlistingdata', {coin_amount:coin_amount, coin_type:coin_type, location:location, payment_method:payment_method, _token:_token, flag: flag }, function(resp) {
            var arr = resp.split('@@@');
            $('#buy_list').empty();
            $('#sell_list').empty();
            if ( seemore_flag == -1 ) {
                $('#title1').html(arr[0]);
                $('#title2').html(arr[0]);
                $('#buy_list').html(arr[1]);
                $('#sell_list').html(arr[2]);
            }
            else if ( seemore_flag == 1 ) {                
                $('#title1').html(arr[0]);
                $('#buy_list').html(arr[1]);
            }
            else {
                $('#title2').html(arr[0]);
                $('#sell_list').html(arr[2]); 
            }
        } );
    }
}  

$(document).ready(function(){
    j_obj = new JObject();
    j_obj.init();

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

    $('.report-btn').click(function(){
        var _token = $('meta[name=csrf-token]').attr('content');
        var listing_id = $('#report_listing_id').val();
        var report_reason = $('#report_reason').val();
        $.post('reportlisting', {listing_id: listing_id, report_reason: report_reason, _token: _token}, function(resp){
            $('#report_reason').val('');
            $('#report_modal').modal('hide');
        });
    });
    
    $("#coin_amount").keyup(function(){
        if($('#coin_type').val() == 'btc')
            cur = btc_currency;
        else
            cur = eth_currency;
        if($.isNumeric($( "#coin_amount" ).val())){
            var val = $( "#coin_amount" ).val();
            var val_arr = val.toString().split('.');
            if(val_arr[val_arr.length-1].length > 6){
                slice_str = val.slice(0, val.length-1);
                $( "#coin_amount" ).val(slice_str);
                var rep_val = (parseFloat($( "#coin_amount" ).val() * cur)).toFixed(2);
            }else{
                var rep_val = (parseFloat($( "#coin_amount" ).val() * cur)).toFixed(2);
            }
            $( "#usd_val" ).html($('#coin_amount').val() + $('#coin_type').val().toUpperCase() + ' = $' + rep_val + 'USD');
         }else{
            alert("Must be Number!");
            $( "#coin_amount" ).val('');
         }
    });

    $("#coin_type").change(function(){
        if($('#coin_type').val() == 'btc')
            cur = btc_currency;
        else
            cur = eth_currency;
        
        if($('#coin_amount') != ''){
            var rep_val = (parseFloat($( "#coin_amount" ).val() * cur)).toFixed(2);
            $( "#usd_val" ).html($('#coin_amount').val() + $('#coin_type').val().toUpperCase() + ' = $' + rep_val + 'USD');
        }
    });
});

function report_user(obj1){
    var _token = $('meta[name=csrf-token]').attr('content');
    var id = $(obj1).attr('id');
    var arr = id.split('-');
    var user_id = arr[0];
    var listing_id = arr[1];
    if ($(obj1).prop('checked')==true){ 
        $('#report_user_id').val(user_id);
        $('#report_listing_id').val(listing_id);
        $('#report_modal').modal('show');
   }else{
        $.post('deletereport', {listing_id: listing_id, _token: _token}, function(resp){
            
        });
    }
}

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

//                if ((event.which === 13 || event.which === 9) && !suggestion_selected && suggestion_open) {
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

//        console.log(locationBit);

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