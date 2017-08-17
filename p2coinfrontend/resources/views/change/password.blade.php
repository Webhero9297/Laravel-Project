@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel-heading">
                <h3 class="h-title">Change your password</h3>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('resetpassword') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Old Password*</label>

                    <div class="col-md-6">
                        <input id="old_password" type="password" class="form-control" name="old_password" placeholder="Old Password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="new_password" class="col-md-4 control-label">New Password*</label>

                    <div class="col-md-6">
                        <input id="new_password" type="password" class="form-control" name="new_password" placeholder="New Password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password*</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-success btn-green">
                            Change
                        </button>
                    </div>
                </div>
                <p class="menu-caption">The password change resets your third party application and API authentications.</p>
            </form>
        </div>
    </div>
</div>
@endsection
