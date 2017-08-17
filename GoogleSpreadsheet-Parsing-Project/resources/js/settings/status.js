/**
 * Created by administrator on 5/28/17.
 */
var status = 'no_action';
var tbl_id='NULL';
var group;
$(document).ready(function(){
    $('#btn_save').click(doOnSaveStatus);
    $('#delete_yes').click(doOnDeleteStatus);
    // App.init();
    // UINestable.init();

    group = $("ol.dd-list").sortable({
        group: 'dd-list',
        isValidTarget: function  ($item, container) {
            if($item.is(".highlight"))
                return true;
            else
                return $item.parent("ol")[0] == container.el[0];
        },
        onDrop: function ($item, container, _super) {
            var data = group.sortable("serialize").get();
            var elem = $('.'+$item[0].parentNode.className)[0].children;
            var post_param = [];
            for(i=0;i<elem.length;i++) {
                post_param.push({id: $(elem[i]).attr('data-id'), ordering: (i+1)});
                // console.log(i,$(elem[i]).attr('data-id'));
            }
            $.get('reorderstatus?param='+JSON.stringify(post_param), function( resp ) {
                window.location.reload();
            });
            _super($item, container);
        },
        serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.text();
        },
        tolerance: 6,
        distance: 10
    });

});
function doOnUpdate(id, displyed_value, cell_values) {
    console.log(group);
    if ( id == "NULL" ) {
        $('#dialog_title').html('Add More Field');
        status = 'insert';
    }
    else {
        $('#dialog_title').html('Update current Field');
        $('#displayed_value').val(displyed_value);
        $('#cell_values').val(cell_values);
        status = 'update';
    }
    tbl_id = id;
}
function doOnDelete(id) {
    $('#result_dialog').attr('aria-hidden', 'false');
    tbl_id = id;
}
function doOnDeleteStatus() {
    status = 'delete';
    // alert(tbl_id);
    doOnSaveStatus();
}
function doOnSaveStatus() {
    var displayed_value = $('#displayed_value').val();
    var cell_values = $('#cell_values').val();
    var post_data = {id:tbl_id,displayed_value:displayed_value, cell_values:cell_values, action_status: status, _token: $('meta[name=csrf-token]').attr('content')};
    $.post('savestatus',post_data,  function(resp){
        if ( resp == 'SUCCESS' ) {
            window.location.reload();
            // $('#new_dialog').removeClass('out');
            $('#new_dialog').removeClass('in');
// alert(resp);
        }
        else{
            alert('error');
        }
    });
}