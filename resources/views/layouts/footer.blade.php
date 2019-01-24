<div>
@push('scripts')
    <script type="text/javascript">

            // do something
            function recaptcha() {
                var responseToken = grecaptcha.getResponse();
                var recaptchaCheck = $.post( 'https://www.google.com/recaptcha/api/siteverify', {response: responseToken, secret: '6Lf7cYMUAAAAAPDgtemDMFdCKpyIloZjl4XBKUWS' });
                recaptchaCheck.done(function( data ) {
                    console.log(data);
                }).fail(function(xhr, status, error) {
                
                }
            };  
    </script>
@endpush
</div>