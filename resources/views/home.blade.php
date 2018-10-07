@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <br>
                    <br>
                    <p>To Do:
                    <ul>
                    <li>Deck -> Build a Deck</li>
                    <li>Deck -> View Decks You've Built</li>
                    <li>Cards -> View Cards</li>
                    <li>Cards -> Search for Specific Cards</li>
                    </ul>
                    <p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
