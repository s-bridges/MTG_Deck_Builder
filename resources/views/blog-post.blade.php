@extends('layouts.app')

@section('content')
<div class="container">
<!-- TCG ad -->
<div class="row justify-content-center">
    <span v-on:click="viewAd()"><img src="https://tcgplayer-marketing.s3.amazonaws.com/content/magic/buy_all_singles_kb_seven_percent_affiliate_leaderboard_728x90_01252019.jpg" class="img-fluid" alt="Responsive image"></span>
    </div>
    </br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    <img style="max-width:250px; height: auto;float:left;padding-right: 1em;" src="/images/{{$image_url}}" />
                    <div style="float:left;">
                        <p>Author: {{$user['username']}}</p> 
                        <p>Created at: {{ \Carbon\Carbon::parse($created_at)->format('M d, Y')}}</p>
                        <form action="{{route('post_delete',[$id])}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit">Delete</button>               
                        </form>
                        {!!$content!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
