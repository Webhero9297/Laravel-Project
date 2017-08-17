$(document).ready(function(){
    $('.viewmessage').click(function(){
        user_id = $(this).attr('user_id');
        contract_id = $(this).attr('contract_id');
       _token = $('meta[name=csrf-token]').attr('content');
        $.post('viewmessages', { _token: _token, contract_id: contract_id, user_id: user_id }, function(resp){
            $('#msg_list').html(resp);
            $('#view_message').modal('show');
        });
    });

    $('.status').change(function(){

        chk_btn_id = $(this).attr('id');
        user_id = $(this).attr('uid');
        type = $(this).attr('prop');

        var status = 0;
        ($('#'+chk_btn_id).is(':checked')) ? status = 1 : status = 0;
        _token = $('meta[name=csrf-token]').attr('content');
        $.get('changestatus', { _token:_token, user_id: user_id, status: status, type:type }, function(resp){
            if (resp === 'ok') {
                
            }
        });
    });
});
