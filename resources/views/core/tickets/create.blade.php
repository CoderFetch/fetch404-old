@extends('core.partials.layouts.master')
@section('title', 'Create a ticket')
@section('content')
    <h1>Create a new support ticket</h1>
    {!! Form::open(['route' => 'tickets.store']) !!}
    @if ($errors->count() > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-7">
            <!-- Subject Form Input -->
            <div class="form-group">
                {!! Form::label('subject', 'Subject', ['class' => 'control-label']) !!}
                {!! Form::text('subject', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Message Form Input -->
            <div class="form-group">
                {!! Form::label('message', 'Message', ['class' => 'control-label']) !!}
                {!! Form::textarea('message', null, ['class' => 'form-control', 'data-type' => 'summernote']) !!}
            </div>

            <!-- Submit Form Input -->
            <div class="form-group">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection