@extends('layouts.default')

@section('title')
@parent
{{trans('Google Drive File Picker')}}
@stop
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 

<script> var tokenInfo = '<?php echo $userInfo['token']; ?>'; </script>
<script> var userInfo = JSON.parse('<?php echo json_encode($userInfo); ?>'); </script>
<link href="{{ URL::asset('./css/admin/index.css') }}" rel="stylesheet" type="text/css"/>
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
						<a href="/settings"><i class="fa fa-user"></i> {{ $userInfo['email'] }}&nbsp;</a>
					</li>
					<li>
						<a href="#"><i class="fa fa-key"></i> Log Out</a>
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
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
					<!--<form class="sidebar-search" action="extra_search.html" method="POST">
						<div class="form-container">
							<div class="input-box">
								<a href="javascript:;" class="remove"></a>
								<input type="text" placeholder="Search..."/>
								<input type="button" class="submit" value=" "/>
							</div>
						</div>
					</form>-->
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
				<li class="start active ">
					<div style="height:32px;"></div>
				</li>
				<li class="start">
					<a href="{{ route('admin') }}">
						<i class="fa fa-home"></i>
						<span class="title">
							Home
						</span>
					</a>
				</li>
				<li class="active">
					<a href="">
						<i class="fa fa-cogs"></i>
						<span class="title">
							Settings
						</span>
						<span class="selected">
						</span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="{{ route('settingsheet') }}">
								<img src="{{ URL::asset('./assets/img/spreadsheet.png') }}" class="icon-align-right">
								Spreadsheet
							</a>
						</li>
						<li>
							<a href="{{ route('settingstatus') }}">
								<img src="{{ URL::asset('./assets/img/portlet-config-icon.png') }}" class="icon-align-right">
								Status
							</a>
						</li>
					</ul>
				</li>
				<li class="">
					<a href="">
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
						<i class="fa fa-folder"></i><span>Settings:</span><span id="selected_folder" >none</span>
					</div>
					<div class="tools" style="margin-top: -3px;">
						<button type="button" class="btn dark picker-btn"  id="pick"><i class="fa fa-folder-open"></i>Pick File</button>
					</div>
				</div>
				<div class="portlet-body">
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
		<span class="go-top">
			<i class="fa fa-angle-up"></i>
		</span>
	</div>
</div>
<!-- END FOOTER -->    


<script src="{{ URL::asset('./js/admin/filepicker.js') }}"></script>
<script src="{{ URL::asset('./js/admin/index.js') }}"></script>
<script src="https://www.google.com/jsapi?key=AIzaSyBaWXOsofarMGoX8FEJKNU09tfWtS0zzlM"></script>
<script src="https://apis.google.com/js/client.js?onload=initPicker&origin=http://localhost:8000"></script>



@stop