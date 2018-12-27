@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<head>
<script src='https://www.google.com/recaptcha/api.js'></script>
{!! NoCaptcha::renderJs() !!}
  </head>

    <div class="row">
        <div class="col-sm-4 offset-sm-3">
        <h1>Contact Us</h1>
        <hr>
            <form action="" method="POST">
            @csrf
            @include('partials._messages')
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control">

            <label for="email" class="mt-3">Email</label>
            <input type="email" name="email" class="form-control">

            <label for="message" class="mt-3">Message</label>
            <textarea name="message" cols="30" rows="10" class="form-control"></textarea>
            <input type="hidden" name="ip" id="hiddenField" value="<?php $ip ?>" />
            <button type="submit" class="btn btn-success btn-block my-3">Send Email</button>
            </form>
            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                <div class="col-md-6">
                    {!! app('captcha')->display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection