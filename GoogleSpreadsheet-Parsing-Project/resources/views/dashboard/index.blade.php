@extends('layouts.default')

@section('title')
    @parent
    {{trans('Google Drive File Picker')}}
@stop
@section('content')
    <meta name="csrf-token" content="{{ Session::token() }}">
    <script> var realtime_chart_data = JSON.parse('<?php echo json_encode($realtime_chart_data); ?>'); </script>
    <script> var daily_chart_data = JSON.parse('<?php echo json_encode($daily_chart_data); ?>'); </script>
    <script> var userInfo = JSON.parse('<?php echo json_encode($userInfo); ?>'); </script>
		<script> var selected_settings_id = '<?php echo $status_id; ?>'; </script>
		<script> var title = '<?php echo $item_title; ?>'; </script>
    <link href="{{ URL::asset('./css/admin/index.css') }}" rel="stylesheet" type="text/css"/>
		<style>
			.label {
				font-size:18px;
			}
		</style>
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="header-inner">
            <!-- BEGIN LOGO -->
            <a class="navbar-brand" href="admin" style="padding:10px;">
                <label>Google Drive</label>
            </a>
            <!-- END LOGO -->
            

            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <img src="assets/img/menu-toggler.png" alt=""/>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" src="{{ $userInfo['userAvatar'] }}" height="30px" style="border-radius:42px!important;"/>
                        <span class="username">
					{{ $userInfo['name'] }}
				</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-user"></i> {{ $userInfo['email'] }}&nbsp;</a>
                        </li>
                        <li>
                            <a href="/"><i class="fa fa-key"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container" style="margin-top:42px;">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <ul class="page-sidebar-menu">
                    <li class="sidebar-toggler-wrapper">
                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                        <div class="sidebar-toggler hidden-phone">
                        </div>
                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    </li>
                    <li class="sidebar-search-wrapper">
                    </li>
                    <li class="start active ">
                        <div style="height:32px;"></div>
                    </li>
                    <li class="start active">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span class="title">
							Dashboard
						</span>
                        <span class="selected"></span>
                        </a>
                    </li>
										<!--
                    <li class="start ">
                        <a href="{{ route('admin') }}">
                            <i class="fa fa-home"></i>
                            <span class="title">
							Home
						</span>

                        </a>
                    </li> 
										-->
                    <li class="">
                        <a href="">
                            <i class="fa fa-cogs"></i>
                            <span class="title">
						Settings
					</span>
                            <span class="arrow ">
					</span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('settingsheet') }}">
                                    <img src="{{ asset('public/assets/img/spreadsheet.png') }}" class="icon-align-right">
                                    Spreadsheet Definition
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('settingstatus') }}">
                                    <img src="{{ asset('public/assets/img/portlet-config-icon.png') }}" class="icon-align-right">
                                    Status Definition
                                </a>
                            </li>
                        </ul>

                    </li>
                    <li class="">
                        <a href="/">
                            <i class="fa fa-key"></i>
                            <span class="title">
						Logout
					</span>
                        </a>
                    </li>
                </ul>
                <!-- END SIDEBAR MENU -->
            </div>
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content" >
                <div class="portlet box grey">
                    <div class="portlet-title">
                        <div class="caption" style="font-size:22px;">
                            <i class="fa fa-cogs"></i><span>Dashboard</span>
                        </div>
												<div class="tools">
														<a href = "" onclick="window.location.reload();" class="reload">
														</a>
												</div>
                    </div>
                    <div class="portlet-body">
												<div class="portlet box grey">
													<div class="portlet-title">
														<div class="caption">
															<i class="fa fa-folder"></i><span>Selected Folder:</span><label class="label" id="selected_folder" ><?php echo $item_title; ?></label>
														</div>
														<div class="tools">
															<a href="javascript:;" class="collapse"></a>
															<a href = "" onclick="window.location.reload();" class="reload"></a>
														</div>
													</div>
													<div class="portlet-body form">
														<div class="panel panel-default">
															<div class="panel-heading">
																<a type="button" class="btn btn-default" id="pick" ><i class="fa fa-folder-open"></i>Pick File</a>
																<button id='collapse' class='btn btn-default btn-left' onclick='jQuery(".tree").treegrid("expandAll"); return false;' type='button' >Expand All</button>
																<button id='collapse' class="btn btn-default btn-left" onclick='jQuery(".tree").treegrid("collapseAll"); return false;' type="button" >Collapse All</button>
																{{--<div class='input-group custom-search-form' style="float:right;display: inline-flex;">--}}
																	{{--<input type='text' class='form-control' placeholder='Search...'>--}}
																	{{--<span class='input-group-btn'>--}}
																		{{--<button class='btn btn-default' type='button' style='height:34px;'>--}}
																			{{--<i class='fa fa-search'></i>--}}
																		{{--</button>--}}
																	{{--</span>--}}
																{{--</div>--}}
															</div>
															<div class="panel-body">
																<div class="col-md-12 main-analyze-div">
																	<table class="tree col-md-12"><?php echo html_entity_decode($treegrid_html); ?></table>
																</div>
															</div>
														</div>
														<!-- END FORM-->
													</div>
												</div>
			
                        <div class="clearfix">
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet box red">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-reorder"></i>Real Time Status:<label id="label_realtime" class="label"> {{ $item_title }} </label>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse">
                                            </a>
                                            <a href="javascript:;" class="reload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="realtime_chart" class="chart">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet box yellow">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-reorder"></i>Daily Status:<label id="label_daily" class="label"> {{ $item_title }} </label>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse">
                                            </a>
                                            <!--<a href="#portlet-config" data-toggle="modal" class="config">
                                            </a>-->
                                            <a href="javascript:;" class="reload">
                                            </a>
                                            <!--<a href="javascript:;" class="remove">
                                            </a>-->
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="daily_chart" class="chart">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN SAMPLE TABLE PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-comments"></i>Real Time Status:<label id="label_realstatus" class="label"> {{ $item_title }} </label>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Test</th>
                                                <th>Total</th>
                                                <th>Executed</th>
                                                @foreach( $status_data as $status=>$val )
                                                    <th>{{$status}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach( $realtimestatus_data as $real_status_data )
                                                    <tr class="active">
                                                        <td><?php echo html_entity_decode(str_replace('->','&nbsp;<i class="fa fa-play" aria-hidden="true"></i>&nbsp;', $real_status_data['tests'])); ?></td>
                                                        <td>{{$real_status_data['total']}}</td>
                                                        <td>{{$real_status_data['executed']}}</td>
                                                        @foreach( $status_data as $status=>$val )
                                                            <td>{{$real_status_data[$status]}}</td>
                                                        @endforeach
                                                    </tr>    
                                                @endforeach
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE TABLE PORTLET-->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN SAMPLE TABLE PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-comments"></i>Daily Status:<label id="label_dailystatus" class="label"> {{ $item_title }} </label>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Executed</th>
                                                @foreach( $status_data as $status=>$val )
                                                    <th>{{$status}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach( $dailystatus_data as $real_status_data )
                                                    <tr class="active">
                                                        <td>{{$real_status_data['tests']}}</td>
                                                        <td>{{$real_status_data['executed']}}</td>
                                                        @foreach( $status_data as $status=>$val )
                                                            <td>{{$real_status_data[$status]}}</td>
                                                        @endforeach
                                                    </tr>    
                                                @endforeach
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE TABLE PORTLET-->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN SAMPLE TABLE PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-comments"></i>User Access Log
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <?php $no=1; ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="numeric">#</th>
                                                    <th>User Name</th>
                                                    <th>Email</th>
                                                    <th>Selected Folder Name</th>
                                                    <th>Logged Time</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach( $user_login_datas as $user_data )
                                                        <tr class="active">
                                                            <td class="number">{{ $no++ }}</td>
                                                            <td>{{ $user_data['firstname'] }}{{ $user_data['lastname'] }}</td>
                                                            <td>{{ $user_data['email'] }}</td>
                                                            <td>{{ $user_data['item_title'] }}</td>
                                                            <td>{{ $user_data['login_at'] }}</td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>                                            
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE TABLE PORTLET-->
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                
            </div>
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            2017 &copy; Metronic by Tef Thachenkary.
        </div>
        <div class="footer-tools">
		
        </div>
    </div>
    <!-- END FOOTER -->


<script src="{{ asset('public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('public/assets/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('public/assets/plugins/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('public/assets/plugins/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('public/assets/plugins/flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('public/assets/plugins/flot/jquery.flot.crosshair.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('public/assets/scripts/app.js') }}"></script>
<script src="{{ asset('public/js/dashboard/canvasjs.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay_progress.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="{{ URL::asset('./js/admin/filepicker.js') }}"></script>
<script src="{{ URL::asset('./js/admin/index.js') }}"></script>
<script src="https://www.google.com/jsapi?key=AIzaSyBaWXOsofarMGoX8FEJKNU09tfWtS0zzlM"></script>
<script src="https://apis.google.com/js/client.js?onload=initPicker&origin=http://localhost:8000"></script>
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
//    App.init();
console.log(realtime_chart_data);
  var realchart = new CanvasJS.Chart("realtime_chart",
	{
		theme: "theme2",
		title:{
			text: "Real Time Status",
			fontSize: 24
		},
        animationEnabled: true,
        legend : {
            verticalAlign: "center",
            horizontalAlign: "right"
        },
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} %",
			legendText: "{indexLabel}-{y}",
			dataPoints: realtime_chart_data
		}
		]
	});
	realchart.render();
    $('.canvasjs-chart-credit').css('display', 'none');

    for(i=0;i<daily_chart_data.length;i++) {
        var tmp = daily_chart_data[i];
        for(j=0;j<tmp.dataPoints.length;j++){
            var dateStr = tmp.dataPoints[j].x.split(',');
            
            tmp.dataPoints[j].x = new Date(dateStr[0],dateStr[1],dateStr[2]);
        } 
    }
console.log(daily_chart_data);		
   var dailychart = new CanvasJS.Chart("daily_chart",
	{
		title: {
            text: "Daily Status",
            fontSize: 24
        },
        animationEnabled: true,
        axisX: {
            gridColor: "Silver",
            tickColor: "silver",
            valueFormatString: "MM/DD/YYYY"
        },
        toolTip: {
            shared: true
        },
        theme: "theme2",
        axisY: {
            gridColor: "Silver",
            tickColor: "silver"
        },
        legend: {
            verticalAlign: "center",
            horizontalAlign: "right"
        },
        data: daily_chart_data,
        // legend: {
        //     cursor: "pointer",
        //     itemclick: function (e) {
        //         if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        //             e.dataSeries.visible = false;
        //         }
        //         else {
        //             e.dataSeries.visible = true;
        //         }
        //         chart.render();
        //     }
        // }
	});
	dailychart.render();
    $('.canvasjs-chart-credit').css('display', 'none');
});
</script>

@stop