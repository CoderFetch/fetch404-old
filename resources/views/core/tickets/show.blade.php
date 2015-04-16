@extends('core.partials.layouts.master')
{{-- Content here --}}
@section('title', $ticket->title)

@section('content')
    <h1>
        Viewing ticket
    </h1>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @foreach($ticket->messages as $i => $message)
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{{{ route('tickets.show', ['id' => $ticket->id]) }}}">{!! Purifier::clean($ticket->title) !!}</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <center>
                            <img class="img-rounded" src="https://cravatar.eu/avatar/{{{ str_slug($message->user->name, '') }}}/100.png" />
                            <br /><br />

                            <strong><a href="{{{ $message->user->profileURL }}}">{{{ $message->user->name }}}</a></strong>
                        </center>
                    </div>

                    <div class="col-md-8">
                        By <a href="{{{ $message->user->profileURL }}}">{{{ $message->user->name }}}</a> &raquo; <span data-type="tooltip" data-trigger="hover" data-original-title="{{{ date('j M y, h:i A', strtotime($message->created_at)) }}}">{{{ $message->created_at->diffForHumans() }}}</span>
                        <hr>
                        {!! Purifier::clean($message->content) !!}
                        <hr>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
    {!! Form::close() !!}
@endsection
