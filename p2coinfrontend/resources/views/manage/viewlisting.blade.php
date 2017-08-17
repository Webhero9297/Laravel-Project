@extends('layouts.app')

@section('content')

<style>
.row { width: 60% !important; margin: auto; font-size: 16px; }
.view-listing p { padding: 10px 0px !important; }
</style>
<div class="container text-center">
    <div class="row view-listing">
        <p class="col-md-12 text-center">
            <h3 class="h-title">View Listing</h3>
        </p>
        <p class="col-sm-12" style="margin-top: l0px;">
            Coin Type : {{ $listing->coin_type }}
        </p>
        <p class="col-sm-12"> 
            Coin Amount : {{ $listing->coin_amount}} 
        </p>
        <p class="col-sm-12">
            Location : {{ $listing->location }}
        </p>
        <p class="col-sm-12"> 
            Payment Method : {{ $listing->payment_method }}
        </p>
        <p class="col-sm-12"> 
            Payment Name  : {{ $listing->payment_name }}
        </p>
        <p class="col-sm-12"> 
            Minimum Transaction Limit : {{ $listing->min_transaction_limit }} - {{ $listing->max_transaction_limit }} {{ $listing->currency }}
        </p>
        <div class="col-sm-12"> 
            Terms of Trade : {{ $listing->terms_of_trade }}
        </p>
        <p class="col-sm-12"> 
            Payment Details : {{ $listing->payment_details }}
        </p>
        <p class="col-sm-12" style="margin-top: 40px;"> 
            <a class="btn btn-success btn-green" onclick="window.history.back();">Return</a>
        </p>
    </div>
</div>
@endsection
