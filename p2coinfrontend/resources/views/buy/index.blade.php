@extends('layouts.app')

@section('content')
<script> var price_rate = {{ $price_rate }};</script>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Contract ID : {{ $contract_id }}</div>
                <div class="panel-body">
                    <h3 class="text-center">Payment Terms</h3>
                    <div>
                        {{ $listing['terms_of_trade'] }}
                    </div>
                </div>
            </div>
            <div class="panel panel-default text-center">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ route('createcontract') }}" method="post">
                        {{ csrf_field() }}
                        <label>Count Amount: {{ $listing['coin_amount'] }}</label><label>{{ strtoupper($listing['coin_type']) }}</label><br/>
                        <label>BTC: ${{ $price_data['btc'] }}</label>/<label>ETH: ${{ $price_data['eth'] }}</label>
                        <input type="text" value="{{ $listing_id }}" id="listing_id" name="listing_id" style="display:none;"/>
                        <input type="text" value="{{ $contract_id }}" id="contract_id" name="contract_id" style="display:none;"/>
                        <input type="text" value="{{ $receiver_id }}" id="receiver_id" name="receiver_id" style="display:none;"/>
                        <input type="text" value="{{ $coin_type }}" id="coin_type" name="coin_type" style="display:none;"/>
                        <div class="panel-body text-center">
                            <div class="col-sm-5"> 
                                <div class="input-group">
                                    <input class="numberinput form-control" id="coin_amount" name="coin_amount" placeholder="{{ $listing['coin_amount'] }}">
                                    <span class="input-group-addon">{{ strtoupper($coin_type) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-2"> 
                                <div class="input-group">
                                </div>
                            </div>
                            <div class="col-sm-5"> 
                                <div class="input-group">
                                    <input class="numberinput form-control" id="price" name="price" placeholder="0.00"> 
                                    <span class="input-group-addon">USD</span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body text-center">
                            <button type="submit" id="buy_btn" class="btn btn-success" style="width: 30%;">BUY</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{URL::asset('./js/buy/index.js')}}" ></script>