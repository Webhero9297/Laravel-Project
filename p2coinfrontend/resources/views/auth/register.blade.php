@extends('layouts.app')

@section('content')
<style>
.title{
    font-family: inherit;
    font-weight: bold;
}

</style>
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h2 class="h-title">Register a new account</h2>
            <h5>Sign up for a user account to start buying or selling bitcoins.</h5>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Username</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control input" name="name" value="{{ old('name') }}" placeholder="Username" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"placeholder="Useremail" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_ip" class="col-md-4 control-label">Your current IP address</label>

                    <div class="col-md-6">
                        <input type="text" name="user_ip" id="user_ip" class="form-control" value="" />
                    </div>
                </div>
                
                <div calss="form-group">
                    <label>Please verify you are a human.</label>
                    <script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
                    <div class="g-recaptcha" data-sitekey="6LfFjSUUAAAAALp9ylv1joI6kLG0dTLLiKnwAXmK" style="text-align:center;">
                        <!--<div style="width: 304px; height: 78px;"><div>
                        <iframe src="https://www.google.com/recaptcha/api2/anchor?k=6LfFjSUUAAAAALp9ylv1joI6kLG0dTLLiKnwAXmK&amp;co=aHR0cHM6Ly9sb2NhbGJpdGNvaW5zLmNvbTo0NDM.&amp;hl=en&amp;v=r20170613131236&amp;size=normal&amp;cb=bstga1yasa0k" title="recaptcha widget" width="304" height="78" frameborder="0" scrolling="no" sandbox="allow-forms allow-modals allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation"></iframe>
                        </div>
                        <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; "></textarea>-->
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-success btn-green">
                            Register
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <span>Already have an account?</span><span><a href="{{ route('login') }}" >Log In</a></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$.getJSON('//freegeoip.net/json/?callback=?', function(data){
    console.log(data);
    $('#user_ip').val(data.ip);
});
</script>
@endsection
