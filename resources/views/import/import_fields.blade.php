@extends('layouts.app')

@section('content')
<div class="row">
    @if ($errors)
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    @endif
    <form method="POST" action="{{ route('process') }}">
        {{ csrf_field() }}
        <input type="hidden" name="csv_data_file_id" value="{{ $data->id }}" />
        <input type="hidden" name="type" value="{{ $data->object_type }}" />
        <table class="table">
            @foreach ($csv_data as $row)
            <tr>
                @foreach ($row as $key => $value)
                <td>{{ $value }}</td>
                @endforeach
            </tr>
            @endforeach
            <tr>
                @foreach ($csv_data[0] as $key => $value)
                <td>
                    <label>Field Name</label>
                    <select class="browser-default" name="fields[{{ $key }}]">
                        @foreach ($importable_fields as $db_field)
                            <option value="{{ $db_field }}"
                                @if ($key === $db_field) selected @endif>{{ $db_field }}</option>
                        @endforeach
                    </select>
                </td>
                @endforeach
            </tr>
        </table>

        <button type="submit" class="btn btn-primary">
            Import Data
        </button>
    </form>
</div>
@endsection