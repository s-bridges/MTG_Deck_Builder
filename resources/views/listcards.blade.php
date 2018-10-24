<!-- @extends('layouts.app')

@section('content')
<list-cards></list-cards>

@endsection -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col s8">
        <h4>CSV Import</h4>
        <form class="col s12" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="file-field input-field">
                <div class="btn">
                    <span>Select File</span>
                    <input id="csv_file" name="csv_file" type="file">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                    @if ($errors->has('csv_file'))
                    <span class="help-block">
                        <strong>{{ $errors->first('csv_file') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col s4">
                    <select class="browser-default" name="type">
                        <option default>Import To</option>
                        // this can come later after the admin only import functionality
                        <option default>My Deck</option>
                        // admin only
                        <option>Card Database</option>
                    </select>
                </div>
                <div class="col s8">
                    <button type="submit" class="btn btn-primary">
                        Parse CSV
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection