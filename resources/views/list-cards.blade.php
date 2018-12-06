@extends('layouts.app')

@section('content')
<list-cards :data="{{$data}}"></list-cards>
@endsection