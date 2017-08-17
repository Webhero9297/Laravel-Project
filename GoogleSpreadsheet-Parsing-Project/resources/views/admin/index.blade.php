@extends('layouts.default')

@section('title')
@parent
{{trans('Google Drive File Picker')}}
@stop
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 
<script> var userInfo = JSON.parse('<?php echo json_encode($userInfo); ?>'); </script>
<script> var selected_settings_id = '<?php echo $status_id; ?>'; </script>
<script> var title = '<?php echo $item_title; ?>'; </script>
<style>
.choice_item{
	width: 12px;
	height: 8px;
	border-left: 4px solid black;
	border-bottom: 4px solid black;
	transform: rotate(-45deg);
}
</style>
<link href="{{ URL::asset('./css/admin/index.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{ URL::asset('./public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>

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
			<div class="sidebar-toggler hidden-phone"></div>
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
				<li class="start">
					<a href="{{ route('dashboard') }}">
						<i class="fa fa-dashboard"></i>
						<span class="title">
							Dashboard
						</span>
					</a>
				</li>
				<li class="start active ">
					<a href="{{ route('admin') }}">
						<i class="fa fa-home"></i>
						<span class="title">
							Home
						</span>
						<span class="selected">
						</span>
					</a>
				</li>
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
								<img src="{{ URL::asset('./public/assets/img/spreadsheet.png') }}" class="icon-align-right">
								Spreadsheet
							</a>
						</li>
						<li>
							<a href="{{ route('settingstatus') }}">
								<img src="{{ URL::asset('./public/assets/img/portlet-config-icon.png') }}" class="icon-align-right">
								Status
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
					<div class="caption">
						<i class="fa fa-folder"></i><span>Selected Folder:</span><span id="selected_folder" ><?php echo $item_title; ?></span>
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
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay_progress.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="{{ URL::asset('./js/admin/filepicker.js') }}"></script>
<script src="{{ URL::asset('./js/admin/index.js') }}"></script>
<script src="https://www.google.com/jsapi?key=AIzaSyBaWXOsofarMGoX8FEJKNU09tfWtS0zzlM"></script>
<script src="https://apis.google.com/js/client.js?onload=initPicker&origin=http://localhost:8000"></script>



@stop