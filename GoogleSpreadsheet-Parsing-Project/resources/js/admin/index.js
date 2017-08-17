// var selected_settings_id = '5';
var hierarchicalDS;
function initPicker() {
    var picker = new FilePicker({
        apiKey: 'AIzaSyC2kolECUFUwdxusxpkN_45U5nONXibg1M',
        clientId: '567336865084-hh90eksn0e5d63kfjd4hos61agkpapqc',
        buttonEl: document.getElementById('pick'),
        onSelect: function(file) {
            if ( selected_settings_id == 'NONE' ) {
                alert( 'Please choose spreadsheet setting information.' );
                return;
            }
            if ( typeof(file) != undefined ){
                $('#selected_folder').html(file.title);
            }
 //console.log(file);           

            file['_token'] = $('meta[name=csrf-token]').attr('content');
            file['selected_settings_id'] = selected_settings_id;
            loadSheetData(file);
        }
    });
}
var myTreeGrid;
$(document).ready(function(){
    $('tr.settings-row').bind('click', doOnSelectedSettings);
    $('#button_anal').click(doOnAnalyzeTreeItem);
    // $('.tree').html(respHTML);
    $('.tree').treegrid({
        expanderExpandedClass: 'glyphicon glyphicon-minus',
        expanderCollapsedClass: 'glyphicon glyphicon-plus'
    });
    file = {};
    file['_token'] = $('meta[name=csrf-token]').attr('content');
    file['selected_settings_id'] = selected_settings_id;
    file['id'] = userInfo.item_id;
    alert(123);
    console.log(file, userInfo);
    loadSheetData(file);
});
function loadSheetData (file) {
    var progress = new LoadingOverlayProgress();
    $.LoadingOverlay("show", {
        custom  : progress.Init()
    });
    $.post('/admin/parse', file, function(respHTML){
        delete progress;
        $.LoadingOverlay("hide");
        // console.log(obj);
        $('.tree').html(respHTML);
        $('.tree').treegrid({
            expanderExpandedClass: 'glyphicon glyphicon-minus',
            expanderCollapsedClass: 'glyphicon glyphicon-plus'
        });
    });    
}
function doOnSelectedSettings() {
    $('tr.settings-row').removeClass('focus');
    $(this).addClass('focus');
    selected_settings_id = $(this).attr('id');
}
function doOnAnalyzeTreeItem() {
    var url = $(this).attr('href');
    param = {'_token' : $('meta[name=csrf-token]').attr('content') };
    $.post(url, param, function(respHTML){

    });
}