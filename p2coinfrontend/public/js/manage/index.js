var seemore_flag = -1;
function JObject() {
}
JObject.prototype = {
    init : function() { 
        this.initEventListen();
        this.loadListingData(1);
    },
    initEventListen : function () {
        $('button.see-more').click(j_obj.doOnSetSeeMoreFlag);
        $('input.status').click(j_obj.updateStatus)
    },
    doOnSetSeeMoreFlag : function() {
        seemore_flag = $(this).attr('prop');
        j_obj.loadListingData(0);
    },
    updateStatus : function(listing_id) {
        var status = 0;
        ($('#'+listing_id).is(':checked')) ? status = 1 : status = 0;
        _token = $('meta[name=csrf-token]').attr('content');
        $.post('changestatus', { _token:_token, listing_id: listing_id, status: status }, function(resp){
            if (resp === 'ok') {
                
            }
        } );
    },  
    loadListingData : function (flag) {
        var _token = $('meta[name=csrf-token]').attr('content');
        $.post('getlistingdatabyuser', { flag: flag, _token: _token }, function(resp) {
            var arr = resp.split('@@@');
            if ( seemore_flag == -1 ) {
                $('#btc_list').html(arr[0]);
                $('#eth_list').html(arr[1]);
            }
            else if ( seemore_flag == 'btc' ) {                
                $('#btc_list').html(arr[0]);
            }
            else {
                $('#eth_list').html(arr[1]);
            }
        } );
    }
}  

$(document).ready(function() { 
    j_obj = new JObject();
    j_obj.init();
});