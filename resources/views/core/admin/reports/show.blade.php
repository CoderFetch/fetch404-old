@extends('core.admin.layouts.default')
@section('title', 'Report #' . $report->id)

@section('content')
    <h1>
        Viewing report
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
    <h2 style="display:inline;">Report:
        <a href="{{{ $report->reported->profileURL }}}">
            {{{ $report->reported->name }}}
        </a> |
        <small>
            <a href="{{{ $report->getContentURL() }}}">View Reported Content</a>
        </small>
    </h2>
    <span class="pull-right">
        @unless($report->isClosed())
            {!! Form::open(['route' => array('forum.post.report.close', $report)]) !!}
            {!! Form::submit('Close report', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        @endunless

        @unless(!$report->isClosed())
            {!! Form::open(['route' => array('forum.post.report.open', $report)]) !!}
            {!! Form::submit('Open report', ['class' => 'btn btn-success']) !!}
            {!! Form::close() !!}
        @endunless
    </span>
    <br /><br />
    <div class="panel panel-primary">
        <div class="panel-heading">Reported by <a href="{{{ $report->owner->profileURL }}}">{{{ $report->owner->name }}}</a><span class="pull-right">{{{ $report->created_at->format('jS M Y , g:ia') }}}</span></div>
        <div class="panel-body">
            {!! Purifier::clean($report->reason) !!}
        </div>
    </div>

    <h3>Comments <small>Can only be viewed by staff</small></h3>
    @unless($report->comments()->count() == 0)
    @foreach($report->comments as $c)
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{{ $c->user->profileURL }}}">
                {{{ $c->user->name }}}
            </a>
            <span class="pull-right">
                {{{ $c->created_at->format('jS M Y , g:ia') }}}
            </span>
        </div>
        <div class="panel-body">
            {!! Purifier::clean($c->body) !!}
        </div>
    </div>
    @endforeach
    @endunless
    <div class="panel panel-default">
        <div class="panel-heading">
            New comment
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => array('forum.post.report.comment', $report)]) !!}
                {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 3]) !!}
                <br />
                {!! Form::submit('Submit', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop