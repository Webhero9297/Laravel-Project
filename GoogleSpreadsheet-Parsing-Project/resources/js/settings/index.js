/**
 * Created by administrator on 5/28/17.
 */

$(document).ready(function() {
    $('#delete_yes').click(doOnDeleteStatus);
    $('#input_form').collapse('hide');
});
function doOnUpdate( id, btn_obj ){
    var trTag = $(btn_obj).parent().parent();
    $('#script_cell').val(trTag[0].cells[1].textContent);
    $('#status_cell').val(trTag[0].cells[2].textContent);
    $('#case_sep').val(trTag[0].cells[3].textContent);
    $('#fb_cell').val(trTag[0].cells[4].textContent);
    $('#ignore_list').val(trTag[0].cells[5].textContent);
    $('#additional_columns').val(trTag[0].cells[6].textContent);
    $('#id').val(id);

    $('#action_status').val('update');
    $('#input_form').collapse('show');
}
function doOnAddNew(id) {
    $('#script_cell').val('');
    $('#status_cell').val('');
    $('#case_sep').val('');
    $('#fb_cell').val('');
    $('#ignore_list').val('');
    $('#additional_columns').val('');
    $('#id').val(id);
    $('#action_status').val('insert');
}
function doOnDelete(id) {
    $('#id').val(id);
    $('#action_status').val('delete');
}
function doOnDeleteStatus() {
    post_param = $('#input_form').serialize();
    $.post('savesheet?'+post_param,  function(resp){
        if ( resp == 'SUCCESS' ) {
            window.location.reload();
        }
    });
}

