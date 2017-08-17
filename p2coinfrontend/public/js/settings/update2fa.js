$(document).ready(function(){
    $('#btn_authorize').click(function(){
        if($('#pin_code').val()=='') return;
        $.get('registkey?code='+$('#pin_code').val()+'&key='+key, function(resp){
            if (resp == 'ok' ) {
                window.location.href = "settings";
                window.reload();
            }
            else {
                alert("error");
                $('#pin_code').val('');
            }
        });
    });
    
});