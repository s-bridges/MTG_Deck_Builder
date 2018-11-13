@extends('layouts.app')

@section('content')
<html>
<title>Admin Panel</title>
<body>
    
<div class="jumbotron">
  <h1 class="display-4"> Admin Panel </h1>
  <p class="lead">Hello, {{ Auth::user()->name }}</p>
  <hr class="my-4">
  <p>Users Registered: {{ $users->count() }}</p>
  <p>Total Decks Built: {{ $decks->count() }}</p>
  <p>Total Cards in Sets: {{ $cards->count() }}</p>
  <p class="lead">
  </p>
</div>
</body>
</html>
@endsection