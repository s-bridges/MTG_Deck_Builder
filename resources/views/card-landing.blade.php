@extends('layouts.app')

@section('content')

<div class="container">
</br>
<!-- 442928 -->
<div class="container">
    <div class="row">
        <div class="col-md-auto">
        <img style="max-width:240px; height: auto;float:left;padding-right: 1em;" src="{{asset('/images/cards/') . '/' . $card['multiverse_id'] . '.jpg'}}"/>
        </div>
        <div class="col col-9">
        <p><h4>{{$card->name}}</h4></p>
        <p>{{$card->type}}</p>
        <p>Set - {{$card->set_name}}</p>
        <p>{{$card->text}}</p>
        @if (count($card->power_levels) > 0)
        <p>
            <!-- only show this if there are power rankings-->
            <h4>Power Rankings</h4>
        </p>
        @else
        <p>Power Rankings will be updated shortly!</p>
        @endif

        <!-- here we loop through each power level on card, as level, then output in the for loop the name of- the -cadand the tranking -->
        @foreach ($card->power_levels as $level)
            <p>{{$level->name}} - {{ $level->pivot->ranking }}</p>
        @endforeach
        </div>
    </div>

</div>




</div>


@endsection