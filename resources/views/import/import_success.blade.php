@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12">
        dd($inserted);
            <p>CSV Imported Successfully!</p>
        </div>
    </div>
@endsection

<script type="text/javascript">
    window.setTimeout(function(){
        // Redirect to import page after 2 seconds
        window.location.href = "{{ url('/import') }}";
    }, 2000);
</script>