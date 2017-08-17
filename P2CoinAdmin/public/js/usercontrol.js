$(document).ready(function(){
    $('#btn_block_account').click(doOnBlockAccount);
    $('#btn_block_ip').change(doOnBlockIP);
    $('#btn_send_message').click(doOnSendMessage);
    $('#send_msg').click(sendMessage);
    $('#btn_change_password').click(doOnChangeUserPassword);
    $('#btn_back').click(doOnBack);
});
function doOnBack() {
    window.location.href = '/usercontrol';
    window.reload();
}
function doOnBlockAccount(user_id) {
    var ststus = 0;
    ($('#btn_block_account').is(':checked')) ? status = 1 : status = 0;
    _token = $('meta[name=csrf-token]').attr('content');
    $.post('/blockuser', { user_id: user_id, _token:_token, status: status, type: 'account' }, function(resp){

    });
}
function doOnBlockIP(user_id) {
    var ststus = 0;
    ($('#btn_block_account').is(':checked')) ? status = 1 : status = 0;
    _token = $('meta[name=csrf-token]').attr('content');
    $.post('/blockuser', { user_id: user_id, _token:_token, status: status, type: 'ip' }, function(resp){

    });
}
function doOnSendMessage() {
    $('#msg_content').val('');
    var user_id = $('#btn_send_message').attr('userid');
}

function sendMessage() { 
    var user_id = $('#btn_send_message').attr('userid');
    _token = $('meta[name=csrf-token]').attr('content');
    msg_content = $('#msg_content').val();
    $.post('/sendnotification', {user_id: user_id, msg_content: msg_content, _token: _token}, function(resp){
        $('#sendmsg_dialog').modal('toggle');
    });
}
function doOnChangeUserPassword() {
    
}