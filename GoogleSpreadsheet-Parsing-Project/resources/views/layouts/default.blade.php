<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<title>@yield('title')</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="MobileOptimized" content="320">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{ URL::asset('./public/assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ URL::asset('./public/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/plugins/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet"/>
<!-- BEGIN:File Upload Plugin CSS files-->
<link href="{{ URL::asset('./public/assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css') }}" rel="stylesheet" type="text/css">
<!-- END:File Upload Plugin CSS files-->
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="{{ URL::asset('./public/assets/css/style-metronic.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/css/style-responsive.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/css/themes/default.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
<link href="{{ URL::asset('./public/assets/css/pages/inbox.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('./public/assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- BEGIN BODY -->
<body class="login">
@yield('content')
<script src="{{ URL::asset('./public/assets/plugins/respond.min.js') }}"></script>
<script src="{{ URL::asset('./public/assets/plugins/excanvas.min.js') }}"></script>
<!-- [endif]-->

<!-- END CORE PLUGINS -->
<!-- BEGIN: Page level plugins -->
<script src="{{ URL::asset('./public/assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('./public/assets/plugins/fuelux/js/tree.min.js') }}"></script>

<script src="{{ URL::asset('./public/assets/plugins/fancybox/source/jquery.fancybox.pack.js') }}" type="text/javascript"></script>

<script src="{{ URL::asset('./public/assets/plugins/jquery-nestable/jquery.nestable.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script src="{{ URL::asset('./public/assets/scripts/ui-nestable.js') }}"></script>
<script src="{{ URL::asset('./js/settings/jquery-sortable.js') }}"></script>
<script src="{{ URL::asset('./public/assets/scripts/app.js') }}"></script>


<link rel="stylesheet" href="{{ URL::asset('./js/admin/jquery-treegrid/css/jquery.treegrid.css') }}">

<script type="text/javascript" src="{{ URL::asset('./js/admin/jquery-treegrid/js/jquery.treegrid.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('./js/admin/jquery-treegrid/js/jquery.treegrid.bootstrap3.js') }}"></script>


<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay_progress.min.js" type="text/javascript"></script>


</body>
<!-- END BODY -->
</html>
<script>
jQuery(document).ready(function() {
   // initiate layout and plugins
   App.init();
});
</script>