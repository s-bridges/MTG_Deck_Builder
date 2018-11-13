@extends('layouts.app')

@section('content')
<deck-of-the-week :data="{{$data}}"></deck-of-the-week>
@endsection