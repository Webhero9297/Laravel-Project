<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Login</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="MobileOptimized" content="320">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{ asset('./public/assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('./public/assets/plugins/select2/select2_metro.css') }}"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="{{ asset('./public/assets/css/style-metronic.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/css/style-responsive.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/css/themes/default.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
<link href="{{ asset('./public/assets/css/pages/login-soft.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('./public/assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
<style>
.title{
	font-weight:bolder!important;
}
.g-icon{
	width:24px;
	height:24px;
	border:white 2px solid;
	border-radius:30px!important;
	float:left;
}
.login-label{
	padding-top:2px!important;
}
.login-panel {
	//background-image:url('/public/assets/img/login-background.jpg')!important;
	//background-size:100% 100%!important;
	//opacity:0.3;
	background:rgba(255,255,255,0.3)!important;
	border-radius:10px!important;
}
.google {
	display: inline-block;
	text-align: center;
	vertical-align: middle;
	width: 140px;
	height:50px;
	padding-top: 5px;
	border: 0px solid #178bb6;
	border-radius: 8px!important;
	background: #d34836;
	font-family: 'Open Sans', sans-serif;
	font-weight:500;
	color: #ffffff;
	font-size:24px;
	text-decoration: none;
}
.google:hover,
.google:focus {
	background: #c74534;
	color: #ffffff;
	text-decoration: none;
}
.google:active {
	background: #1ca4d6;
}
.google:before{
	content:  "\0000a0";
	display: inline-block;
	height: 50px;
	width: 45px;
	border-right:2px solid #ccc;
	line-height: 24px;
	margin: 0 4px -6px -4px;
	position: relative;
	padding-left: 10px;
	top: -10px;
	left: -37px;
	background: url("https://i.imgur.com/GxFF74f.png") no-repeat center center #c74534;
	background-size: 24px 24px;
	-webkit-border-radius: 8px 0px 0px 8px;
	-moz-border-radius: 8px 0px 0px 8px;
	-o-border-radius: 8px 0px 0px 8px;
	-ms-border-radius: 8px 0px 0px 8px;
	border-radius: 8px 0px 0px 8px;
}
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<div class="logo">
	<h1 class="title">Google Login</h1>
</div>
<div class="content">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
	<form class="login-form" style="border-raius:10px;" action="chkuser" method="post" novalidate="novalidate">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		<h5 class="form-title">Please click login button to your account</h5>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
				 Enter any username and password.
			</span>
		</div>
		<div class="form-actions">
      <a class="google pull-right" href="{{ $client->createAuthUrl() }}">Login</a>            
		</div>
	</form>
</div>
<script src="{{ asset('./public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('./public/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/plugins/backstretch/jquery.backstretch.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('./public/assets/plugins/select2/select2.min.js') }}"></script>

<script src="{{ asset('./public/assets/scripts/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('./public/assets/scripts/login-soft.js') }}" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   App.init();
   Login.init();
});
</script>
</body>
<!-- END BODY -->
</html>