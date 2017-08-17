$(document).ready(function(){
    $('#btn_authorize').click(function(){
        if($('#pin_code').val()=='') return;
        $.get('check2fa?code='+$('#pin_code').val(), function(resp){
            if (resp == 'ok' ) {
                window.location.href = "change2fa";
                window.reload();
            }
            else {
                alert("error");
                $('#pin_code').val('');
            }
        });
    });
    
});