@extends('layouts.app')

@section('content')
<style>
    .td-wrap{
        overflow: hidden;
        text-overflow: ellipsis;
        flex-wrap: nowrap;
        display:block;
        /*width:35%;*/
    }
    .left-content { margin-right: 50px; }
    .right-content { margin-left: 50px; }
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
                                    <i class="fa fa-gift"></i>Disputes
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
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 47px;">Listing</th>
                                                    <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" aria-label=" Username : activate to sort column ascending" style="width: 80px;">Buyer</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Email : activate to sort column ascending" style="width: 80px;">Seller</th>
                                                    <th class="sorting" tabindex="0" class="td-wrap" width="40%">Dispute Reason</th>
                                                    <th class="sorting" tabindex="0" style="width: 70px;">Message</th>
                                                    <th class="sorting" tabindex="0" style="width: 70px;">End Contract</th>
                                                    <th class="sorting" tabindex="0" style="width: 70px;">Release Escrow</th>
                                                </tr>
                                            </thead>
                                            <tbody id='data_area'>
                                                @foreach($dispute_list as $dispute)
                                                    <tr>
                                                        <td>{{ $dispute['listing_id'] }}</td>
                                                        <td>{{ $dispute['receiver'] }}</td>
                                                        <td>{{ $dispute['sender'] }}</td>
                                                        <td class="td-wrap">{{ $dispute['dispute_reason'] }}</td>
                                                        <td><a href="#" class="btn blue btn-outline viewmessage" user_id = "{{ $dispute['user_id'] }}" contract_id="{{ $dispute['contract_id'] }}" id="viewmessage">View</a></td>
                                                        <td><a href="" class="btn blue btn-outline" >End</a></td>
                                                        <td><a href="" class="btn blue btn-outline" >Release</a></td>
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

<div id="view_message" class="modal container fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">View Messages</h4>
    </div>
    <div class="modal-body" id='msg_list'>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Close</button>
    </div>
</div>

<script src="{{ asset('./assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./js/dispute.js') }}" type="text/javascript"></script>
@endsection