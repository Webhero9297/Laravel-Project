@extends('layouts.app')

@section('content')
<style>
    .email-input  {
        width:400px;
        min-width:180px;
    }
</style>
<script src="{{ asset('./assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
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
                                        <i class="fa fa-gift"></i>Transaction History</div>
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
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-4 col-md-9">
                                                    <div class="md-radio-inline">
                                                        <div class="md-radio">
                                                            <input type="radio" id="view_all_trades" name="radio2" class="md-radiobtn" checked="">
                                                            <label for="view_all_trades">
                                                                <span class="inc"></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span>View All Trades</label>
                                                        </div>
                                                        <div class="md-radio">
                                                            <input type="radio" id="view_successful_trades" name="radio2" class="md-radiobtn">
                                                            <label for="view_successful_trades">
                                                                <span class="inc"></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span>View Successful Trades</label>
                                                        </div>
                                                    </div>
                                                    {{--<button type="button" id = "view_all_listings" class="btn dark btn-outline sbold">View All trades</button>--}}
                                                    {{--<button type="button" id="view_reported_listings" class="btn dark btn-outline sbold">View successful trades</button>--}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-body">
                                            <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                                <thead>
                                                <tr role="row">
                                                    <th class="sorting_disabled" style="width: 47px;">Time</th>
                                                    <th class="sorting_desc" style="width: 93px;">Buyer</th>
                                                    <th class="sorting" style="width: 148px;">Seller</th>
                                                    <th class="sorting" style="width: 67px;">Coin Amount</th>
                                                    <th class="sorting" style="width: 70px;">Coin Currency</th>
                                                    <th class="sorting" style="width: 70px;">Fiat amount</th>
                                                    <th class="sorting" style="width: 70px;">Fiat currency</th>
                                                    <th class="sorting" style="width: 70px;">Success</th>
                                                </tr>
                                                </thead>
                                                <tbody id="form_body-container">

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
<script>
var status = 'all';
$(document).ready(function(){
    $('#form_body-container').html('Loading...');
    $.get('displaytrades?status='+status, function(respHTML){
        $('#form_body-container').html(respHTML);
    });
    $('#view_all_trades').click(function(){
        status = 'all';
        $('#form_body-container').html('Loading...');
        $.get('displaytrades?status='+status, function(respHTML){
            $('#form_body-container').html(respHTML);
        });
    });
    $('#view_successful_trades').click(function(){
        status = 'successful';
        $('#form_body-container').html('Loading...');
        $.get('displaytrades?status='+status, function(respHTML){
            $('#form_body-container').html(respHTML);
        });
    });
});
</script>
@endsection