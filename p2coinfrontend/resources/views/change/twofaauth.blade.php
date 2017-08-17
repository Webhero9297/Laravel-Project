@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <div class="panel panel-default">
                    <div class="panel-heading">2FA Secret Key</div>

                    <div class="panel-body">
                        Open up your 2FA mobile app and scan the following QR barcode:
                        <br />
                        <img alt="Image of QR barcode" src="{{ $inlineUrl }}" />

                        <br />
                        If your 2FA mobile app does not support QR barcodes,
                        enter in the following number: <code>{{ $key }}</code>
                        <br /><br />
                        <input class="numberinput form-control" id="pin_code" name="pin_code" required>
                        <br/>
                        <a id="btn_authorize" class="btn btn-success">Confirm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>var key = '{{ $key }}';</script>
<script src="{{URL::asset('./js/settings/update2fa.js')}}" ></script>
@endsection