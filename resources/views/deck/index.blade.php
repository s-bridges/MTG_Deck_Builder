@extends('layouts.app')


@section('content')
    <div class="panel panel-primary">
            <div class="panel-heading">My Decks</div>
            <div class="panel-body">
                    <ul class="list-group">
                    @foreach($deck as $deck)
                    <li class="list-group-item">{{ $deck->name }}</li>                        
                    @endforeach
                    </ul>
            </div>
    </div>
@endsection