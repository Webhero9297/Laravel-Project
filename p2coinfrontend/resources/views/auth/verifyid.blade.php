@extends('layouts.app')

@section('content')
<style>
    #image_preview {
        border: solid 1px grey;
        min-height:480px;
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
            <form action="{{ route('uploadidimage') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-md-6">
                    <input type="file" class="form-control" id="images" name="images[]" onchange="preview_images();" multiple/>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-success btn-green" name='submit_image' value="Upload ID Images"/>
                </div>
            </form>
        </div>
        <div class="row" id="image_preview"></div>
        
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