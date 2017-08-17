@extends('layouts.app')

@section('content')
<style>
 .btn-action  {
     width:100%;
     height:100%;
 }
</style>
<link rel="stylesheet" href="{{ asset('./css/cryptocoins.css') }}">
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
                                    <i class="fa fa-gift"></i>Website Wallet
                                </div>
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
                                        <div class="row">
                                            <div class="col-md-6 yellow btn-outline">
                                                <div class="form-group form-md-line-input has-success">
                                                    <div class="input-icon">
                                                        <input type="text" class="form-control" placeholder="Left icon" value="{{ $btc_info->amount }}" readonly>
                                                        <label for="form_control_1">Total BTC</label>
                                                        <span class="help-block">Some help goes here...</span>
                                                        <i class="cc BTC"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 dark btn-outline">
                                                <div class="form-group form-md-line-input has-success">
                                                    <div class="input-icon">
                                                        <input type="text" class="form-control" placeholder="Left icon" value="{{ $eth_info->amount }}" readonly>
                                                        <label for="form_control_1">Total ETH</label>
                                                        <span class="help-block">Some help goes here...</span>
                                                        <i class="cc ETH"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a class="btn green btn-outline btn-action" >Deposit BTC</a>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn red btn-outline btn-action">Withdraw BTC</a>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn green btn-outline btn-action" >Deposit ETH</a>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn red btn-outline btn-action">Withdraw ETH</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="caption" style="font-size:16px;">
                                                    <i class="fa fa-send"></i>
                                                    <span class="caption-subject font-dark sbold uppercase">Pending Withdrawals</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 400px;">Address</th>
                                                            <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" style="width: 100px;">User</th>
                                                            <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" style="width: 80px;">Amount</th>
                                                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="ascending" style="width: 80px;">Currency</th>
                                                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="ascending" style="width: 107px;">Date/Time</th>
                                                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="ascending" style="width: 70px;">Status</th>
                                                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="ascending" style="width: 70px;">Confirmations</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id='data_area'>
                                                        @foreach( $wallet_data as $wallet )
                                                            <tr>
                                                                <td>
                                                                    {{ $wallet['sender']['wallet_info'][$wallet['coin_type']]['wallet_address'] }}<i class="fa fa-send"></i>{{ $wallet['receiver']['wallet_info'][$wallet['coin_type']]['wallet_address'] }}
                                                                </td>
                                                                <td>{{$wallet['sender']['name']}}<i class="fa fa-send"></i>{{ $wallet['receiver']['name'] }}</td>
                                                                <td>{{ $wallet['coin_amount'] }}</td>
                                                                <td>{{ strtoupper($wallet['coin_type']) }}</td>
                                                                <td>{{ date("j M, Y", strtotime($wallet['date'])) }}</td>
                                                                <td>{{ $wallet['status'] }}</td>
                                                                <td></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
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