@extends('layouts.app')

@section('content')
<style>
 .email-input  {
     width:400px;
     min-width:180px;
 }
</style>
<div class="panel panel-default">
{{ csrf_field() }}
  <div class="panel-body">
    <div class="portlet-body">
        <div class="table-toolbar">
            <form class="form-inline" >
                <div class="form-body" >
                    <div class="form-group">
                        <label for="user_name">UserName</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="user_name" placeholder="User Name"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_email">UserEmail</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="text" class="form-control email-input" id="user_email" placeholder="Email Address"> 
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button id='btn-search' class='btn btn-success' type="button" >Search</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
            <thead>
                <tr role="row">
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 47px;"></th>
                    <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" aria-label=" Username : activate to sort column ascending" style="width: 93px;"> Username </th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Email : activate to sort column ascending" style="width: 148px;"> Email </th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Joined : activate to sort column ascending" style="width: 67px;"> Joined </th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;"> Actions </th>
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
                        <button class="view_user btn blue btn-outline sbold" type="button" user_id="{{ $user->id }}" onclick="doOnViewDetail(this)">View Detail
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
</div>
<script src="{{ asset('./assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
    $('#btn-search').click(function(){
        var user_name = $('#user_name').val();
        var user_email = $('#user_email').val();
        var _token = $('meta[name=csrf-token]').attr('content');
        $.post('getuserbysearch', { user_name: user_name, user_email:user_email, _token:_token }, function(resp){
            $('#data_area').empty();
            data = JSON.parse(resp);
            tbodyStr = '';
            for(i=0;i<data.length;i++) {
                tmp = data[i];
                tbodyStr += '<tr class="gradeX odd" role="row"><td>'+tmp.id+'</td><td class="sorting_1">'+tmp.name+'</td><td><a href="mailto:userwow@gmail.com">'+tmp.email+'</a></td><td class="center">'+ tmp.created_at + '</td><td align=center><button class="view_user btn btn-success" type="button" user_id="'+tmp.id+'" onclick="doOnViewDetail(this)">View Detail</button></td></tr>';
            }
            $('#data_area').html(tbodyStr);
        });
    });
});
function doOnViewDetail(thisObj) {
    var userId = $(thisObj).attr('user_id');
    window.location.href = '/userdetail/'+userId;
    window.reload();
}
</script>
@endsection