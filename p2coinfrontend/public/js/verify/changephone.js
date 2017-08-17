var request_id = undefined;
var request_code = undefined;
$('form').submit(function(){
    $('#phone_number').val($('#phone').val());
});
$(document).ready(function(){
    $('#btn_request_code').click(function(){
        if ( $('#phone').val() == '') return ;
        // alert($('.selected-dial-code').html())
        $.get('getpincode?phone_number='+$('#phone').val()+'&dial_code='+$('.selected-dial-code').html(), function(resp){
            if (resp.status == '200') {
                request_id = resp.response.id;
                $('#div_disp').removeClass('div_hidden');
            }
            
        });
    });
    $('#verify_phone').click(function(){
        request_code = $('#code').val();
        $.get('verifycode?request_id='+request_id+'&request_code='+request_code, function(resp){
            if ( resp == 'ok' ) {
                window.location.href= '/settings';
                window.reload();
            }else {

            }
        });
    });
    
    
});