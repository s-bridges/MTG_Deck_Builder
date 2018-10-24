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
                    <p>This site is in extreme beta right now. Sets will be uploaded as quickly as possible.</p>
                    <p>Current features are you can view a set, and search for a card within that set</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
