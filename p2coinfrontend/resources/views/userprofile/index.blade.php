@extends('layouts.app')
@section('content')

    <div class="container">
        <h3>Edit your profile</h3>
        <div class="container">
            <div class="list-group">
                <a href="#div_change_password" class="list-group-item menu-caption">Change Password</a>
                <a href="#div_change_email_address" class="list-group-item menu-caption">Change Email Address</a>
                <a href="#div_verification" class="list-group-item menu-caption">Verification</a>
            </div>
        </div>
        <div class="row">
            @if ( Auth::user()->phone_verify == 0 )
            <div class="col-md-12">
                <div class="alert alert-info">
                    <span>
                        <i class="fa fa-exclamation"></i>&nbsp;
                        
                            You have not added a verified phone number to your account yet.
                            <a class="btn btn-default profile-verified-info" href="">Verify phone number</a>
                        
                    </span>
                </div>
            </div>
            @endif
            @if ( Auth::user()->id_verify == 0 )
            <div class="col-md-12">
                <div class="alert alert-success">
                    <span>
                        <i class="fa fa-exclamation"></i>&nbsp;
                        
                            You have not added a verified ID to your account yet.
                            <a class="btn btn-default profile-verified-info" href="">Verify ID</a>
                        
                    </span>
                </div>
            </div>
            @endif
            <div class="col-md-12">
                <a name="password"></a>
                <span id="toc1"></span><h3 id="div_change_password">Change password</h3>
                <p>
                    <a href="{{ route('changepassword') }}"><i class="fa fa-arrow-right"></i> Change password</a>
                </p>
                <span id="toc2"></span>
                <h3 id="div_change_email_address">Change email address</h3>
                
                <span id="toc3"></span>
                <h3 id="div_verification">Verification</h3>
                <p>
                    E-mail verified:
                    <strong class="security-level-strong">yes</strong>
                </p>
                <p>
                    Phone number verified:
                    @if ( Auth::user()->phone_verify == 0 )
                        <strong class="security-level-weak">no</strong>
                    @else
                        <strong class="security-level-strong">yes</strong>
                    @endif
                </p>
                <p>
                    @if( Auth::user()->phone_verify == 0 )
                    <a href="{{ route('verifyphone') }}"><i class="fa fa-arrow-right"></i> Verify phone number</a>
                    @endif
                </p>
                <p>
                    Identity verified:
                    @if( Auth::user()->id_verify == 0 )
                        <strong class="security-level-weak">no</strong>
                    @else
                        <strong class="security-level-strong">yes</strong>
                    @endif
                </p>
                <p>
                    @if( Auth::user()->id_verify == 0 )
                    <a href="{{ route('verifyid') }}"><i class="fa fa-arrow-right"></i> Verify identity</a>
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection
<script src="{{URL::asset('./js/user/userprofile.js')}}" ></script>