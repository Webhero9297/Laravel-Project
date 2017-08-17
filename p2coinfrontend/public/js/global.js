/**
 * Created by administrator on 7/30/17.
 */

function doRealTime(){
    window.setInterval(function(){
        doOnLoadWindowData();
    },30000);
}

function doOnLoadWindowData() {
    var _token = $('meta[name=csrf-token]').attr('content');
    $.post(rootURL+'/getlastmessagelist', {_token: _token}, function(resp) {
        $('#msg_list').html(resp);
    } );

    $.get('/getwalletamountbycoin', function(price_data){
        repjson = JSON.parse(price_data);
        // $('#label_btc_amount').html(repjson.btc);
        // $('#label_eth_amount').html(repjson.eth);
        $('#label_btc_amount').html('btc'.toUpperCase());
        $('#label_eth_amount').html('eth'.toUpperCase());
    });
}

function doViewMessages(param) {
    var _token = $('meta[name=csrf-token]').attr('content');
    var form = document.createElement("form");
    var element1 = document.createElement("input"); 
    var element2 = document.createElement("input"); 

    form.method = "POST";
    form.action = rootURL+"/trademessage";   

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
}

$(function(){
    $(".menu-currency").click(function(){
        if ($(this).attr('id') == 'btc') {
            $('#div_btc').css('display', '');
            $('#div_eth').css('display', 'none');
        }
        if ($(this).attr('id') == 'eth') {
            $('#div_eth').css('display', '');
            $('#div_btc').css('display', 'none');
        }
    });
    doOnLoadWindowData();
    doRealTime();
});

