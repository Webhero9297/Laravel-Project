//var contract_id;
//var receiver_id;
$(document).ready(function(){
    var contract_receiver_id = $("#user_menu li.active").attr('id');
    if (contract_receiver_id) {
        var arr = contract_receiver_id.split('-');
        eceiver_id = arr[0];
        contract_id = arr[1];
    }
    else {
        contract_receiver_id = receiver_id;
    }
    $('#message_send').click(function() {
        // var contract_receiver_id = $("#user_menu li.active").attr('id');
        // var arr = contract_receiver_id.split('-');
        // receiver_id = arr[0];
        // contract_id = arr[1];

        var message_content = $('#chat-content').val();
        var _token = $('meta[name=csrf-token]').attr('content');
        param = {contract_id: contract_id, _token: _token,
        receiver_id: receiver_id, sender_id: sender_id,
        message_content: message_content, message_state:"0" };
        getMessageContentAndDraw( param );
    });
    $('#dispute').click(function(){
        var _token = $('meta[name=csrf-token]').attr('content');
        $.post('dispute', {transaction_id: transaction_id, _token:_token}, function(resp) {
            if(resp=='ok')
                $('#disput_html').html("<div class='alert alert-danger'><strong>Disputing!</strong></div>");
        });
    });
    $('a.contract').click(function(){
        var id = $("#user_menu li.active").attr('id');
        var parentNode = $(this)[0].parentNode;
        $('#user_menu li').removeClass('active');
        $('#'+parentNode.id).addClass('active');
        var tmp = parentNode.id.split('-');
        contract_id = tmp[1];
        var _token = $('meta[name=csrf-token]').attr('content');
        param = {contract_id: contract_id, _token: _token,
        receiver_id: tmp[0], sender_id: sender_id,
        message_content: 'NULL', message_state:"0" };
        getMessageContentAndDraw( param );
    });

    $('#release_transaction').click(function(){
        var _token = $('meta[name=csrf-token]').attr('content');
        $.post('gettransactionid', {contract_id:contract_id, _token:_token}, function(resp) {
            if ( resp != 'fail' ) {
                $.post('withdraw', {transaction_id: resp, _token:_token}, function(resp){
                    // $.get()

                    $.get('getwalletamountbycoin', function(resp){
                        $('#label_btc_amount').html(resp.btc);
                        $('#label_eth_amount').html(resp.eth);
                    });
                    $('#release_transaction').hide();
                    console.log(resp);
                });
             }
        } );
    });
    $('#btn_commit').click(function(){
        var _token = $('meta[name=csrf-token]').attr('content');
        var transaction_id = $(this).attr('attr_trans_id');
        var content = $('#comment').val();
        $.post('setdispute', { _token:_token, transaction_id:transaction_id, content: content}, function(resp){
            
        })
    });

    $('#release_yes').click(function(){
        var _token = $('meta[name=csrf-token]').attr('content');
        msg = $('#ajax_message').html();
        str='<div class="alert alert-info left-content"><strong>P2Coin</strong><br><p>Please leave feedback</p></div>';
        $.post('settradestatus', {contract_id:contract_id, status: 1, _token:_token}, function(resp) {
            $('#trade_status').hide();
            $('#feedback').show();
            $('#ajax_message').html(msg+str);
        } );
    });
    $('#release_no').click(function(){
        var _token = $('meta[name=csrf-token]').attr('content');
        msg = $('#ajax_message').html();
        str='<div class="alert alert-success left-content"><strong>P2Coin</strong><br><p>Please leave feedback</p></div>';
        $.post('settradestatus', {contract_id:contract_id, status: 0, _token:_token}, function(resp) {
            $('#trade_status').hide();
            $('#feedback').show();
            $('#ajax_message').html(msg+str);
        } );
    });

    $('#feedback .submit-btn').click(function(){
        var status = $("input[name='optradio']:checked").val();
        var feedback = $('#feedback_msg').val();
        var _token = $('meta[name=csrf-token]').attr('content');
        $.post('leavefeedback', {contract_id:contract_id, outcome: status, feedback: feedback, _token:_token}, function(resp) {
            $('#feedback').hide();
        } );
    });

    if(is_success == 0){
        $('#trade_status').show();
    }else{
        if(is_feedback == 0){
            $('#feedback').show();
        }
    }
});

function getMessageContentAndDraw( param ) {
    $.post('addmessage', param, function(resp){
        //console.log(resp.fee);
        if ( resp.fee.status == 'enable' ) {
            $('#release_transaction').removeClass('disabled');
            $('#release_transaction').addClass('active');
        }
        else{
            $('#release_transaction').removeClass('active');
            $('#release_transaction').addClass('disabled');
        }
        $('#pay_amount').val(resp.fee.total);
            
        var str = "";
        for(i=0;i<resp.content.length;i++){
            var obj_msg = resp.content[i];
            str += "<div class='alert alert-" + obj_msg.user_state + "'><strong>" + obj_msg.created_at + "</strong><P>" + obj_msg.message_content + "</p></div>";
        }
        $('#chat-content').val('');
        $('#ajax_message').html(str);
    });
}