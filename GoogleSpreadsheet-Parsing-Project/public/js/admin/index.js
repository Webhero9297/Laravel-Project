var hierarchicalDS;
function initPicker() {
    var picker = new FilePicker({
        apiKey: 'AIzaSyBaWXOsofarMGoX8FEJKNU09tfWtS0zzlM',
        clientId: '333002135404-0elq9u6bu5j8asvg9k1qtfopiosana3r',
        buttonEl: document.getElementById('pick'),
        onSelect: function(file) {

            if ( selected_settings_id && selected_settings_id == 'NONE' ) {
                alert( 'Please choose spreadsheet setting information.' );
                return;
            }
            if ( typeof(file) != undefined ){
                $('#selected_folder').html(file.title);
                $('#label_realtime').html(file.title);
                $('#label_realstatus').html(file.title);
                $('#label_daily').html(file.title);
                $('#label_dailystatus').html(file.title);
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
    $('.tree').treegrid({
        expanderExpandedClass: 'glyphicon glyphicon-minus',
        expanderCollapsedClass: 'glyphicon glyphicon-plus'
    });
    file = {};
		if (selected_settings_id !='NULL') {
			file['_token'] = $('meta[name=csrf-token]').attr('content');
			file['selected_settings_id'] = selected_settings_id;
			file['id'] = userInfo.item_id;
			file['title'] = title;
			loadSheetData(file);
		}
});
function loadSheetData (file) {
    var progress = new LoadingOverlayProgress();
    $.LoadingOverlay("show", {
        custom  : progress.Init()
    });
		$('.loadingoverlay_progress_text').css('display','none');
    $.ajax({ 
			url:'/admin/parse', 
			data: file, 
			type: 'POST',
			success: function(respHTML, status){
					delete progress;
					$.LoadingOverlay("hide");
					// console.log(obj);
					$('.tree').html(respHTML);
					$('.tree').treegrid({
							expanderExpandedClass: 'glyphicon glyphicon-minus',
							expanderCollapsedClass: 'glyphicon glyphicon-plus'
					});
					$('.loadingoverlay_progress_text').css('display','none');
					//window.location.reload();
			}, 
			error: function(xhr, status, error){
				delete progress;
					$.LoadingOverlay("hide");
					//console.log(xhr, status, error);
					$('.tree').html("<h3>Parsing error</h3><br>It seems it doesn't match spreadsheet and settings. <br>Please go to Spreadsheet Definition and check settings again.");
			} 
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