@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Blog</div>

                <div class="card-body">
                    <img style="max-width:250px; height: auto;float:left;padding-right: 1em;" src="/images/{{$image_url}}" />
                    <div style="float:left;">
                        <p>{{$title}} by: {{$user['name']}}</p> 
                        {!!$content!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
