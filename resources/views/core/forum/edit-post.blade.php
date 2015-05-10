@extends('core.partials.layouts.master')
@section('title', 'Edit post')
    {{-- Content here --}}
@section('content')
    <h2>Editing post in "{{{ $post->topic->title }}}"</h2>
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
    @include('core.partials.forum.post', array('post' => $post))
    <div class="panel panel-default">
        <div class="panel-heading">
            New Post
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => array('forum.post.posts.edit', $post)]) !!}
            {!! Form::textarea('body', $post->content, ['class' => 'form-control', 'rows' => 3, 'data-type' => 'summernote']) !!}
            <br />
            {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection