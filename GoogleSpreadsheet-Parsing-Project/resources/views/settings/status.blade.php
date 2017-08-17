@extends('layouts.default')

@section('title')
@parent
{{trans('Google Drive File Picker')}}
@stop
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}">
<script src="{{ URL::asset('./public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
<script> var userInfo = JSON.parse('<?php echo json_encode($userInfo); ?>'); </script>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('./public/assets/plugins/jquery-nestable/jquery.nestable.css') }}"/>

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
				<!--
				<li class="start">
					<a href="{{ route('admin') }}">
						<i class="fa fa-home"></i>
						<span class="title">
							Home
						</span>
					</a>
				</li>
				-->
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
								<img src="{{ URL::asset('./public/assets/img/spreadsheet.png') }}" class="icon-align-right">
								Spreadsheet Definition
							</a>
						</li>
						<li class="active">
							<a href="{{ route('settingstatus') }}">
								<img src="{{ URL::asset('./public/assets/img/portlet-config-icon.png') }}" class="icon-align-right">
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
						<i class="fa fa-folder"></i><span>Spreadsheet Status</span>
					</div>
				</div>
				<div class="portlet-body">
					<a class="btn btn-success" data-target="#new_dialog" data-toggle="modal" onclick="doOnUpdate('NULL')"><i class="fa fa-file" >&nbsp;Add Definition</i></a>
					<br>
					<div class="dd" id="nestable_list_1">
						<div class="dd-handle">
							<div class="col-md-2">No</div>
							<div class="col-md-3">Displayed Value</div>
							<div class="col-md-4">Cell Values</div>
							<div class="col-md-3">
								Will do?
							</div>
						</div>
						<ol class="dd-list">
                            <?php $no = 1;?>
							@foreach($sheet_status as $s_row)
								<li class="dd-item status-tr" data-id="{{ $s_row->id }}">
									<div class="dd-handle">
										<div class="col-md-2">{{ $no++ }}</div>
										<div class="col-md-3">{{ $s_row->displayed_value }}</div>
										<div class="col-md-4">{{ $s_row->cell_values }}</div>
										<div class="col-md-3">
											<a  class="status-btn" data-target="#new_dialog" data-toggle="modal" onclick="doOnUpdate( '{{ $s_row->id }}', '{{ $s_row->displayed_value }}','{{ $s_row->cell_values }}')"><i class="fa fa-edit" ></i>Edit</a>
											<a  class="status-btn" data-target="#delete_dialog" data-toggle="modal" onclick="doOnDelete( '{{ $s_row->id  }}' )"><i class="fa fa-trash-o" ></i>Delete</a>
										</div>
									</div>
								</li>
							@endforeach
						</ol>
					</div>

				</div>
			</div>
			
		</div>
	</div>
	<!-- END CONTENT -->

	<div class="modal fade" id="new_dialog" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title" id="dialog_title"></h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal">
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-4 control-label">Displayed Value</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="displayed_value" name="displayed_value" placeholder="ie. A3" >
									<span class="help-block">
										Put displyed values you want
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Cell Values</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="cell_values" name="cell_values" placeholder="ie. A3" >
									<span class="help-block">
										Put cells values with comma separated to display
									</span>
								</div>
							</div>
							<input type="hidden" name="id" value="NULL"/>
							<div class="form-group">
								<div class="col-md-offset-7 col-md-9">
									<button id='btn_save' class="btn btn-primary" type="button" >Save</button>
									<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="delete_dialog" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Warning</h4>
				</div>
				<div class="modal-body">
					Are you sure you want to delete this item?
				</div>
				<div class="modal-footer">
					<button id='delete_yes' type="button" class="btn danger">Yes</button>
					<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
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

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ URL::asset('./public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
{{--<script src="{{ URL::asset('./public/assets/plugins/jquery-nestable/jquery.nestable.js') }}"></script>--}}
<!-- END PAGE LEVEL SCRIPTS -->
{{--<script src="{{ URL::asset('./public/assets/scripts/ui-nestable.js') }}"></script>--}}
<script src="{{ URL::asset('./public/assets/scripts/app.js') }}"></script>
<script src="{{ URL::asset('./js/settings/jquery-sortable.js') }}"></script>
<script src="{{ URL::asset('./js/settings/status.js') }}"></script>

<script>
    jQuery(document).ready(function() {
        // initiate layout and plugins

    });
</script>
@stop