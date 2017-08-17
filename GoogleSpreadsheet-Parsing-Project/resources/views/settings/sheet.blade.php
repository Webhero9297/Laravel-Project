@extends('layouts.default')

@section('title')
@parent
{{trans('Google Drive File Picker')}}
@stop
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 
<style>
.a-clip{
	width:39px;height:34px;float:left;
}
.choice_item{
	width: 12px;
	height: 8px;
	border-left: 4px solid black;
	border-bottom: 4px solid black;
	transform: rotate(-45deg);
	
}
.chk_block{
	display:block;
}
.chk_none{
	display:none;
}
.row_record>td{
	background:transparent!important;
}
.row_record{
	background:white;
	cursor:pointer;
}
.row_record:hover{
	background:lightblue!important;
}
.selected_record {
	background:lightskyblue!important;
}
.form-border{
	margin-top: 10px;
	margin-bottom: 10px;
	border: 1px solid lightgrey;
}
</style>
<script src="{{ URL::asset('./public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
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
						<span class="selected">	</span>
					</a>
					<ul class="sub-menu">
						<li class="active">
							<a href="{{ route('settingsheet') }}">
								<img src="{{ URL::asset('./public/assets/img/spreadsheet.png') }}" class="icon-align-right">
								Spreadsheet Definition
							</a>
						</li>
						<li>
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
						<i class="fa fa-folder"></i><span>Spreadsheet custom setting</span>

					</div>
				</div>
				<div class="portlet-body form-settings">
					<a class="btn btn-success"  data-toggle="collapse" data-target="#input_form" onclick="doOnAddNew('NULL')"><i class="fa fa-file" >&nbsp;Add Definition</i></a>
					<form id="input_form" class="form-horizontal form-border"  class="collapse" action="savesheet" method="POST">
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-4 control-label">Test Script Cell</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="script_cell" name="script_cell" placeholder="ie. A3" >
									<span class="help-block">
										Put in the cell where your first cell is located
									</span>
								</div>

							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" >Test Script Status Cell</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="status_cell" name="status_cell" placeholder="i.e. C" >
									<span class="help-block">Put the Column C if your steps execution status are in column C</span>
								</div>

							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Test Case Seperator(Default blank line)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="case_sep" name="case_sep" placeholder="i.e. %%" >
									<span class="help-block"> What delimiter are you using to separate test cases(i.e. %%)</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" >Cells</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="fb_cell" name="fb_cell" placeholder="i.e. -1,2" >
									<span class="help-block" >Cells forward/backward(ie -1,2)</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" >Status Row Start</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="status_row_start" name="status_row_start" placeholder="i.e. -1,0,2" >
									<span class="help-block" >Status Row Start Forward/Backward Index(ie -1,0,2)</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" >Ignore List</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="ignore_list" name="ignore_list" placeholder="i.e. folder1,spreadsheet1,sheet1,test scriptname1" >
									<span class="help-block">Ignore folders, spreadsheets, sheets and test cases that start with or named</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" >Additional G-Drive columns to import</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="additional_columns" name="additional_columns" placeholder="i.e. E,G,H" >
									<span class="help-block">Put in the cell extra column with comma separated without any space</span>
								</div>
							</div>
							<input type="hidden" id="_token" name="_token" value="{{ Session::token() }}" />
							<input type="hidden" id="id" name="id" value="NULL" />
							<input type="hidden" id="action_status" name="action_status" value="insert" />
						</div>

						<div class="form-actions fluid">
							<div class="col-md-offset-7 col-md-9">
								<button class="btn btn-primary" type="submit" >Save</button>
								<button class="btn btn-default" type="button" id="btn_exit" >Exit</button>
							</div>

						</div>
					</form>
					<table class="table table-striped table-hover table-bordered" id="table_sheet_setting">
						<thead>
						<tr>
							<td align="center">#</td>
							<td align="center">No</td>
							<td align="center">Test Script Cell</td>
							<td align="center">Test Sccript Status Column</td>
							<td align="center">Test Case Seperator</td>
							<td align="center">Cells</td>
							<td align="center">Status Row Start Index</td>
							<td align="center">Ignore List</td>
							<td align="center">Additional G-Drive Columns</td>
							{{--<td align="center">Created at</td>--}}
							{{--<td align="center">Updated at</td>--}}
							<td align="center">Will do?</td>
						</tr>
						</thead>
						<tbody>
						<?php $no = 1;
						?>
						@foreach($sheet_settings as $s_row)
							<?php 
								if ( $userInfo['status_id'] == $s_row->id ) {
									$select_record = 'selected_record';
									$chk_style = "chk_block";
								}
								else {
									$select_record = '';
									$chk_style = "chk_none";
								}
							?>
							<tr class="row_record {{$select_record}}" sId="{{ $s_row->id }}">
								<td align="center">
									<div class="choice_item {{$chk_style}}" id="check_{{ $s_row->id }}"></div>
								</td>
								<td align="center">{{ $no++ }}</td>
								<td>{{ $s_row->script_cell }}</td>
								<td>{{ $s_row->status_cell }}</td>
								<td>{{ $s_row->case_sep }}</td>
								<td>{{ $s_row->fb_cell }}</td>
								<td>{{ $s_row->status_row_start }}</td>
								<td>{{ $s_row->ignore_list }}</td>
								<td>{{ $s_row->additional_columns }}</td>
								{{--<td>{{ $s_row->created_at }}</td>--}}
								{{--<td>{{ $s_row->updated_at }}</td>--}}
								<td align="center" width="9%">
									<a class="btn btn-primary a-clip" onclick="doOnUpdate( '{{ $s_row->id }}', this)"><i class="fa fa-edit" ></i></a>
									<a class="btn btn-danger a-clip" data-target="#delete_dialog" data-toggle="modal" onclick="doOnDelete( '{{ $s_row->id  }}' )"><i class="fa fa-trash-o" ></i></a>
								</td>
							</tr>
						@endforeach

						</tbody>
					</table>
					
				</div>
			</div>
			
		</div>
	</div>
	<!-- END CONTENT -->
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

<script src="{{ URL::asset('./js/settings/index.js') }}"></script>
<script src="https://www.google.com/jsapi?key=AIzaSyBaWXOsofarMGoX8FEJKNU09tfWtS0zzlM"></script>
<script src="https://apis.google.com/js/client.js?onload=initPicker&origin=http://localhost:8000"></script>



@stop