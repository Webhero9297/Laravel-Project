@extends('layouts.app')

@section('content')
<style>
 .btn-action  {
     width:100%;
     height:100%;
 }
.switch {
  position: relative;
  display: inline-block;
  width: 47px;
  height: 26px;
}

.switch input {display:none;}

.slider-box {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f34242;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider-box:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider-box {
  background-color: green;
}

input:focus + .slider-box {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider-box:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}

/* Rounded slider-boxs */
.slider-box.round {
  border-radius: 24px!important;
}

.slider-box.round:before {
  border-radius: 50%;
}

</style>
<div class="panel panel-default">
{{ csrf_field() }}
  <div class="panel-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12" >
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_0">
                        <div class="portlet box blue-ebonyclay">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-gift"></i>Change Verified ID</div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                    <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                    <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <form action="#" class="form-horizontal">
                                    <!-- <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="button" id = "view_all_listings" class="btn btn-circle green">View All Listings</button>
                                                <button type="button" id="view_reported_listings" class="btn btn-circle green">View Reported Listings</button>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="form-body">
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 47px;">ID</th>
                                                    <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" aria-label=" Username : activate to sort column ascending" style="width: 93px;"> Username </th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Email : activate to sort column ascending" style="width: 148px;"> Email </th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Joined : activate to sort column ascending" style="width: 67px;"> Joined </th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;">Phone verification</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;">ID verification</th>
                                                </tr>
                                            </thead>
                                            <tbody id='data_area'>
                                                @foreach( $userlist as $user )
                                                <tr class="gradeX odd" role="row">
                                                    <td>{{ $user->id }}</td>
                                                    <td class="sorting_1">{{ $user->name }}</td>
                                                    <td>
                                                        <a href="mailto:userwow@gmail.com"> {{ $user->email }} </a>
                                                    </td>
                                                    <td class="center"> {{ $user->created_at }} </td>
                                                    <td align=center>
                                                        <label class='switch'>
                                                        @if ( $user->phone_verify == 0 )
                                                            <input type='checkbox' class='status' id="sms_{{ $user->id }}" prop = "sms" uid = "{{ $user->id }}" name='status'>
                                                        @else 
                                                            <input type='checkbox' class='status' id="sms_{{ $user->id }}" prop = "sms" uid = "{{ $user->id }}" name='status' checked>
                                                        @endif
                                                            <span class='slider-box round'></span>
                                                        </label>
                                                    </td>
                                                    <td align=center>
                                                        <label class='switch'>
                                                        @if ( $user->id_verify == 0 )
                                                            <input type='checkbox' class='status' id="id_{{ $user->id }}" prop = "id" uid = "{{ $user->id }}" name='status'>
                                                        @else
                                                            <input type='checkbox' class='status' id="id_{{ $user->id }}" prop = "id" uid = "{{ $user->id }}" name='status' checked>
                                                        @endif
                                                            <span class='slider-box round'></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
  </div>
</div>
<script src="{{ asset('./assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
    $('.status').change(function(){

        chk_btn_id = $(this).attr('id');
        user_id = $(this).attr('uid');
        type = $(this).attr('prop');

        var status = 0;
        ($('#'+chk_btn_id).is(':checked')) ? status = 1 : status = 0;
        _token = $('meta[name=csrf-token]').attr('content');
        $.get('changestatus', { _token:_token, user_id: user_id, status: status, type:type }, function(resp){
            if (resp === 'ok') {
                
            }
        });
    });
});
</script>
@endsection