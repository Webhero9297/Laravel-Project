/**
 * Created by administrator on 7/26/17.
 */
function CStandard() {
    this.coin_type = 'eth';
    this.action = 'deposit';
    $('#pin_code').focusin();

}
CStandard.prototype = {
    init : function() {
        this.initEvent();
    },
    initEvent : function () {
        $('a.a-wallet').click(cObj.doOnClickWalletAction);
        $('#btn_deposit').click(cObj.doOnWalletDeposit);
        $('#btn_withdraw').click(cObj.doOnWalletWithdraw);
        $('#btn_authorize').click(cObj.doOnAuthorize);
        $('#pin_code').keypress(function(event){
            if ( event.keyCode == 13 )
                cObj.doOnAuthorize();
        });
    },
    doOnClickWalletAction : function () {
        cObj.coin_type = $(this).attr('cointype');
        cObj.action = $(this).attr('prop');

        $('#coin_string').html(cObj.coin_type);
        if ( cObj.action == "deposit" ){
            $('#modal_title').html("To deposit "+cObj.coin_type+", please send "+cObj.coin_type+" to this address:");
            $('#label_address').html($(this).attr('deposit_address'));
            var _token = $('meta[name=csrf-token]').attr('content');
            $.post('generateqrcode', {address : $(this).attr('deposit_address'), _token: _token }, function(resp){
                $('#img_qrcode').attr('src',resp);
            });
        }
        // console.log(cObj.coin_type, cObj.action);
    },
    doOnWalletDeposit : function() {
        var src_address = $('#src_address').val();
        var deposit_amount = $('#deposit_amount').val();
        var private_key = $('#private_key').val();
        var _token = $('meta[name=csrf-token]').attr('content');
        var post_param = { src_address: src_address, deposit_amount: deposit_amount, private_key: private_key, coin_type: cObj.coin_type,  _token: _token };
        $.post('deposit', post_param, function(resp) {

        });
    },
    doOnWalletWithdraw : function() {
        var address = $('#dest_address').val();
        var coin_amount = $('#coin_amount').val();
        var _token = $('meta[name=csrf-token]').attr('content');
        $.post('coinwithdraw', { address: address, coin_amount: coin_amount,  coin_type: cObj.coin_type,  _token: _token }, function(resp){

        });
    },
    doOnAuthorize : function() {
        if($('#pin_code').val()=='') return;
        $.get('check2fa?code='+$('#pin_code').val(), function(resp){
            if (resp=='ok') {
                $('#btn_withdraw').removeClass('hidden');
                $('#dest_address').removeAttr('readonly');
                $('#coin_amount').removeAttr('readonly');
                $('#invalid_title').addClass('hidden');
            }
            else {
                $('#btn_withdraw').addClass('hidden');
                $('#dest_address').addClass('readonly');
                $('#coin_amount').addClass('readonly');
                $('#valid_title').removeClass('hidden');
            }
        });
    }
}
$(document).ready(function(){
    cObj = new CStandard();
    cObj.init();
});