@extends('layouts.app')

@section('content')
<style>
 .btn-action  {
     width:100%;
     height:100%;
 }
</style>
<div class="panel panel-default">
{{ csrf_field() }}
  <div class="panel-body">
    <div class="col-md-12">
        <div class="portlet-body form"> 
            @foreach( $image_list as $image )
                <div class='col-md-3'><img class='img-responsive' src='{{ $image }}'></div>
            @endforeach
        </div>
    </div>
  </div>
</div>

@endsection