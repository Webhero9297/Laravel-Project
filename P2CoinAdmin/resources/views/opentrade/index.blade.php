@extends('layouts.app')

@section('content')
<style>
 .btn-action  {
     width:100%;
     height:100%;
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
                                    <i class="fa fa-gift"></i>Open Trades</div>
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
                                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 47px;">Lister</th>
                                                    <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" aria-label=" Username : activate to sort column ascending" style="width: 93px;">Partner</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Email : activate to sort column ascending" style="width: 148px;">Amount in Coin</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Joined : activate to sort column ascending" style="width: 67px;">Amount in Fiat</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;">Status</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;">End trade</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;">Release Escrow</th>
                                                </tr>
                                            </thead>
                                            <tbody id="form_body-container">
                                            @foreach( $trade_arr as $trade )
                                                <tr class="gradeX odd" role="row">
                                                    <td align=center>{{ $trade['seller'] }}</td>
                                                    <td align=center>{{ $trade['buyer'] }}</td>
                                                    <td align=center>{{ $trade['coin_amount'] }} {{ strtoupper($trade['coin_type']) }}</td>
                                                    <td align=center>${{ $trade['fiat_amount'] }}</td>
                                                    <td align=center>{{ $trade['payment_name'] }}</td>
                                                    @if ( $trade['is_closed'] == 1 )
                                                        <td align=center><label class="btn btn-action">-</label></td>
                                                        <td align=center><button type="button" class="btn red btn-action btn-outline sbold" id="btn_release" onclick="doOnRelease({{ json_encode($trade) }})">Release</button></td>
                                                    @else
                                                        <td align=center><label class="btn lightblue btn-action">End</label></td>
                                                        <td align=center></td>
                                                    @endif
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

});
function doOnRelease(json_trade_obj) {
    var _token = $('meta[name=csrf-token]').attr('content');
    json_trade_obj['_token'] = _token;
    $.post('releasetrade', json_trade_obj, function(resp){
window.location.reload();
    });
//    console.log(json_trade_obj);
}
</script>
@endsection