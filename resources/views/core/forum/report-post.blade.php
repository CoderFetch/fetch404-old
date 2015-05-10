@extends('core.partials.layouts.master')
@section('title', 'Report post')
{{-- Content here --}}
@section('content')
    <h2>Report Post #{{{ $post->id }}}</h2>
    <hr>
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
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#postContent">
                        View Post Content
                    </a>
                </h4>
            </div>
            <div id="postContent" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <center><img src="{{{ $post->user->getAvatarURL(100) }}}"  alt="" class="img-rounded" height="100" width="100">
                                <br /><br />
                                <b>
                                    {!! link_to_route('profile.get.show', $post->user->name, [$post->user->slug, $post->user->id]) !!}
                                </b>
                            </center>
                            <hr>
                        </div>
                        <div class="col-md-9">
                            By {!! link_to_route('profile.get.show', $post->user->name, [$post->user->slug, $post->user->id]) !!} &raquo; {{{ $post->created_at->diffForHumans() }}}
                            <hr>
                            {!! Purifier::clean($post->content) !!}
                            <hr>
                            @if ($post->user->getSetting("show_signature", true) == true && $post->user->getSetting("post_signature") != null)
                                {!! Purifier::clean($post->user->getSetting("post_signature")) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Report Reason
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => array('forum.post.posts.report', $post)]) !!}
                <textarea name="reason" class="form-control" rows="3"></textarea>
                <span class="help-block">
                    Our staff will review your report and take appropriate action.
                </span>
                <br />
                {!! Form::submit('Submit', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </div>
    </div>

@endsection