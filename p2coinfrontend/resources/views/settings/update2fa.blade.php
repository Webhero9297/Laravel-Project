@extends('layouts.app')
@section('content')

    <div class="container">
        <h3>To change current 2FA secret key, please enter your 2FA PIN code.</h3>
        <div class="form-group">
            <div class="col-md-4 col-md-offset-4">
                <label for="group_pin_code">2FA One Time Pin:</label>
                <div class="input-group">
                    <input class="numberinput form-control" id="pin_code" name="pin_code" required>
                    <a id="btn_authorize" class="btn input-group-addon">Authorize</a>
                </div>
                    <div id="invalid_title" class="hidden" style="color: red; font-weight: 800;">INVALID</div>
            </div>
        </div>
    </div>
<script src="{{URL::asset('./js/settings/change2fa.js')}}" ></script>
@endsection