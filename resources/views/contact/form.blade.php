@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<head>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<div class="container">
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
            <input class="cool" type="text" name="_honeyPot" id="_honeyPot" value="" />
            <input class="cool" type="text" name="_honeyPot2" id="_honeyPot2" value="" />
            </form>
            <button class="btn btn-success btn-block my-3" id="gcaptchy" onclick="recaptcha()">Send Email</button>
            <div class="g-recaptcha" data-sitekey=""></div>
        </div>
        <div class="col-sm-4 align-self-center">
        <p><h4>Or join us on Discord!</h4></p>
            <a href="https://discord.gg/nhVUUaF"><img src="/images/Discord-Logo+Wordmark-Color.png" class="img-fluid" alt="discord"></a>
        </div>
    </div>
</div>

@endsection


<script type="text/javascript">
    function recaptcha() {
        var responseToken = grecaptcha.getResponse();
        if (responseToken) {
            var formData = new FormData();
            formData.append('response', responseToken);
            formData.append('secret', '');
            var xhr = new XMLHttpRequest();   // new HttpRequest instance
            xhr.open("POST", "https://www.google.com/recaptcha/api/siteverify");
            xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            xhr.onreadystatechange = function () {
                console.log(xhr);
                // status is good and it saved
                if(xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.response);
                    console.log(response);
                }
                // if there was an error
                if (xhr.readyState === 4 && xhr.status === 422) {
                            
                }
            };
            xhr.send(formData);
        }
    }
</script>  

