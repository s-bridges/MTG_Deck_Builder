@extends('layouts.app')

@section('content')
<deck-cards :data="{{$data}}"></deck-cards>
@endsection