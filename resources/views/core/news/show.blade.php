@extends('core.partials.layouts.master')
@section('title', $news->title)
{{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-md-7">
            <h1>{{{ $news->title }}}</h1>
            <small class="text-muted">
                Created by {!! link_to_route('profile.get.show', $news->user->name, [$news->user->slug, $news->user->id]) !!}
                {{{ $news->formattedCreatedAt() }}}
            </small>
            <hr>
            <p>
                {!! Purifier::clean($news->content) !!}
            </p>
        </div>
    </div>
@endsection