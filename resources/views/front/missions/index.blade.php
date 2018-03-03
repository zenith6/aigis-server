@extends('front._layouts.layouts')

@section('content')
  <div class="list-group">
    @foreach ($missions as $_mission)
      <a href="#" class="list-group-item">{{ $_mission->name }}</a>
    @endforeach
  </div>
@endsection
