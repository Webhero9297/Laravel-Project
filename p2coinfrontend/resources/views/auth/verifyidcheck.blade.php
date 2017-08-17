@extends('layouts.app')

@section('content')
<style>
    #image_preview {
        border: solid 1px grey;
        min-height:480px;
    }
    #myCarousel, .item {
        max-height: 480px!important;
        min-height: 360px!important;
        height: 480px!important;
        width: 100%!important;
    }
</style>
<div class="container">
    <div class="row" style="padding:10px;">
        <div class="col-md-12">
            <div class="panel-heading">
                <h3 class="h-title">ID information</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <?php $s = 0; $activeStr = '';?>
                        @foreach( $img_arr as $img )
                            @if ( $s == 0 ) 
                                <div class="item active"> 
                            @else 
                                <div class="item">
                            @endif
                            <!-- <div class="item {{$activeStr}}"> -->
                                <img src="{{ $img['path'] }}" alt="{{ $img['name'] }}" width="100%" height="100%" />
                            </div>
                            <?php $s++; ?>
                        @endforeach
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
function preview_images() 
{
 var total_file=document.getElementById("images").files.length;
 for(var i=0;i<total_file;i++)
 {
  $('#image_preview').append("<div class='col-md-3'><img class='img-responsive' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
 }
}
</script>
@endsection