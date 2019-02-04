@extends('layouts.app')

@section('content')
<catalog-view :data="{{$data}}"></catalog-view>
@endsection