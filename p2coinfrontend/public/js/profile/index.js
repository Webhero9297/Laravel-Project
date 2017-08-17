$(document).ready(function(){
    $('.menu-currency').click(function() {
        var coin = $(this).attr('id');
        // $('#span_title').html(coin.toUpperCase());
        $('#div_trades').html('loading...');
        $('#div_trade_volume').html('loading...');
        $('#lbl_sell').html(coin.toUpperCase());
        $('#lbl_buy').html(coin.toUpperCase());
        $.get('gettrade?coin='+coin, function(resp){
            $('#div_trades').html(resp.trades);
            $('#div_trade_volume').html(resp.volumes);
            $('#div_trades').css('background','');
            $('#div_trade_volume').css('background','');
        });
    });
    $('#btn_send_email').click(function(){
        $.post('reportuser', {content: $('#report_user_content').val(), _token: $('meta[name=csrf-token]').attr('content')}, function(resp){
            // alert("");
        });
    });
});