@extends('core.partials.layouts.master')
@section('title', $news->title)
{{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-md-7">
            <h1>{{{ $news->title }}}</h1>
            <small class="text-muted">Created by {{{ $news->user->name }}}, {{{ date('l \a\t g:h A', strtotime($news->created_at)) }}}</small>
            <hr>
            <p>
                {!! Purifier::clean($news->content) !!}
            </p>
        </div>
    </div>
@endsection