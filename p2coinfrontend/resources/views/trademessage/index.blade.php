@extends('layouts.app')
<link href="//fonts.googleapis.com/css?family=Karla:400,700,400italic,700italic&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=Oswald:400,300,700&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
<style>
.terms { 
    min-height: 300px;
}
.pad { padding: 15px; }
.left-content { margin-right: 50px; }
.right-content { margin-left: 50px; }
.btn-red {
    background: rgba(255,255,255,0.1)!important;
    border: 2px solid #d43f3a!important;
    color: #d43f3a!important;
    font-weight: bolder!important;
}
</style>
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 
<script>var contract_id={{ $contract_id }}</script>
<script>var sender_id={{ $sender_id }}</script>
<script>var receiver_id={{ $receiver_id }}</script>
<script>var listing_id={{ $listing_id }}</script>
<script>var transaction_id={{ $transaction_id }}</script>
<script>var is_success={{ $is_success }}</script>
<script>var is_feedback={{ $is_feedback }}</script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">ChatRoom</div>


                <div class="panel-body">
                    <h3 class="text-center">
                        <b>Contract ID : {{ $contract_id }}</b>
                    </h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class = "panel panel-default terms">
                                <div class="pad">
                                    <h4 class="text-center"><b>Payment Terms</b></h4>
                                    <p class="pad">{{ $listing['terms_of_trade'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class = "panel panel-default terms">
                                <div class="pad">
                                    <h4 class="text-center"><b>payment_details</b></h4>
                                    <p class="pad">{{ $listing['payment_details'] }}</p>      
                                </div>
                            </div>
                         </div>
                    </div>
                    <div class="row" style="overflow-y: scroll; max-height: 300px;">
                        <div class="col-lg-12">
                            <div class = "panel panel-default">
                                <div class="pad" id="ajax_message">
                                    @foreach( $data as $msg_content )
                                        <div class="alert alert-{{$msg_content['user_state']}}">
                                            <strong>{{ $msg_content['name'] }} - {{ $msg_content['created_at'] }}</strong><br>
                                            <p>{{ html_entity_decode($msg_content['message_content']) }}</p>
                                        </div>
                                    @endforeach 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="chat-content"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" id="message_send" class="btn btn-success">SEND</button>
                    </div>

                    <div class = "panel panel-default" id="trade_status" style='padding: 20px 0px; display: none;'>
                        <div class='row text-center'>
                        <label class="text-center" style="font-size:24px;">Was this trade successful?</label>
                        </div>
                        <div class='row text-center'>
                            <button type="button" id="release_yes" class="btn btn-success btn-green">Yes</button>
                            <button type="button" id="release_no" class="btn btn-danger btn-red">No</button>
                        </div>
                    </div>
                    <div class="panel panel-default" id="feedback" style="display: none;">
                        <div class = "row" style="padding-top:20px;">
                            <div class='row text-center'>
                            <label class="text-center" style="font-size:24px;">Feedback</label>
                            </div>
                            <div class='row text-center' style="padding: 10px 15px;">
                                <div class='col-sm-4 text-center'><label class="radio-inline"><input type="radio" name="optradio" value='1'>Positive</label></div>
                                <div class='col-sm-4 text-center'><label class="radio-inline"><input type="radio" name="optradio" value='0' checked>Neutral</label></div>
                                <div class='col-sm-4 text-center'><label class="radio-inline"><input type="radio" name="optradio" value='-1'>Negative</label></div>
                            </div>
                        </div>
                        <div class='row' style="height: 70px;padding: 10px 15px;">
                            <div class="col-sm-11 text-left"><textarea class="form-control" id="feedback_msg"></textarea></div>
                            <div class="col-sm-1 text-center" style="margin-top: 10px;"><button type="button" class="btn btn-success submit-btn">SUBMIT</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('./js/trademessage/index.js') }}"></script>  
@endsection
                        <!-- <button type="button" id="dispute" class="btn btn-danger" style="float:right;"  data-toggle="modal" data-target="#dispute_modal">Dispute</button> -->
                        <?php
                    //     if($listing->is_closed == 1){
                    //         if(!$disput_status)
                    //             echo "<div id=\"disput_html\"><button type=\"button\" id=\"dispute\" class=\"btn btn-danger\"  data-toggle=\"modal\" data-target=\"#dispute_modal\">Dispute</button></div>";
                    //         else
                    //             echo "<div class='alert alert-danger'><strong>Disputing!</strong></div>";
                    //     }else{
                    //     if (isset($request_amount)) {
                    //         if ( $request_amount > $balance ) {
                    //             echo "<div class=\"alert alert-danger\">
                    //                       <strong>Warning!</strong> You can't withdraw $request_amount because your wallet has  $balance.
                    //                     </div>";
                    //         }
                    //         else {
                    //             echo "<div class=\"alert alert-success\">
                    //                       <strong>Alarm!</strong> You will pay  $request_amount  for  $balance  .
                    // </div><button type=\"button\" id=\"release_transaction\" class=\"btn btn-success btn-green\">Confirm Transaction</button>";
                    //         }
                    //     }
                    //     }
                        ?>
