@extends('layouts.app')


@section('content')
    <div class="panel panel-primary offset-md-1 col-lg-3">
            <div class="panel-heading">My Decks</div>
            <div class="panel-body">
                    <ul class="list-group">
                    @foreach($deck as $deck)
                    <li class="list-group-item"><a href="/deck/{{ $deck->id }}">{{ $deck->name }}</a></li>                        
                    @endforeach
                    </ul>
            </div>
    </div>
@endsection