@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="./public/assets/phone/css/intlTelInput.css">
<link rel="stylesheet" href="./public/assets/phone/css/demo.css">
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel-heading">
                <h3 class="h-title">Change Phone Number</h3>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('changepersonphonenumber') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">Phone Number</label>
                    <div class="col-md-6">
                        {{ $phone_number }}
                    </div>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="phone" class="col-md-4 control-label">Phone Number</label>
                    <div class="col-md-6">
                        <input id="phone" type="tel">
                    </div>
                </div>   
                        <input id="phone_number" name="phone_number" type="hidden" value="asdfasdfasdfasdf"> 

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-success btn-green">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="./public/assets/phone/js/intlTelInput.js"></script>
<script src="./public/js/settings/index.js"></script>
<script src="./public/js/settings/changephone.js"></script>
@endsection