@extends('layouts.app')
<link href="//fonts.googleapis.com/css?family=Karla:400,700,400italic,700italic&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=Oswald:400,300,700&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
<style>
.terms { 
    min-height: 300px;
}
.pad { padding: 15px; }
.user_list { height: 520px; overflow-y:scroll; }
.message_content { height: 400px;}
.left-content { margin-right: 50px; }
.right-content { margin-left: 50px; }
</style>
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 

<?php
$current_user = \Auth::user();
?>
<script>var sender_id={{ $current_user->id }}</script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <!-- <div class="panel-heading">MessageBox</div> -->
                <div class="panel-body">
                    <h3 class="text-center">
                        <b>Messsages</b>
                    </h3>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class = "panel panel-default terms">
                                <div class="pad user_list">
                                <ul class="nav nav-pills nav-stacked" id="user_menu">
                                <?php $i = 0; ?>
                                @foreach($user_list as $user)
                                    <li @if(!$i)class="active"@endif id="{{ $user->id }}-{{ $user->contract_id }}"><a href="#" class="contract">{{ $user->name }}</a></li>
                                    <?php $i++; ?>
                                @endforeach
                                </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class = "panel panel-default terms">
                                <div class="pad message_content" id="ajax_message">
                                    @foreach( $msg_content as $msg )
                                        <div class="alert alert-{{$msg['user_state']}}">
                                            <strong>{{ $msg['created_at'] }}</strong><br>
                                            <p>{{ $msg['message_content'] }}</p>
                                        </div>
                                    @endforeach 
                                </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="chat-content"></textarea>
                                </div>
                                <div class="form-group text-right">
                                    <button type="button" id="message_send" class="btn btn-success">SEND</button>
                                </div>
                                <div class="form-group">
                                    <button data-toggle="collapse" data-target="#release_div" class="btn btn-success btn-green">Confirm Transaction</button>
                                    <div id="release_div" class="collapse">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form class="form-inline">
                                                    <div class="form-group">
                                                        <label for="pay_amount">You will pay</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pay_amount">amount</label>
                                                        <input type="number" class="form-control" id="pay_amount">
                                                    </div>
                                                    <button type="button" id="release_transaction" class="btn btn-success btn-green">Release</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('./assets/jquery-1.10.2.min.js') }}"></script>
<script src="{{ asset('./js/trademessage/index.js') }}"></script>
@endsection
