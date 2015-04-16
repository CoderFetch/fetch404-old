@extends('core.partials.layouts.master')
@section('title', 'My posts')
{{-- Content here --}}
@section('content')
    <h1>My posts</h1>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (!$posts->isEmpty())
                @foreach($posts as $i => $post)
                    <span>
						<i class="fa fa-comment fa-fw fa-2x pull-left"></i>
						Thread: <a href="{{{ $post->topic->Route }}}" data-type="tooltip" data-original-title="<h6>{{{ str_limit($post->content, 120) }}}">{{{ $post->topic->title }}}</a>
						<span class="pull-right">
							<span class="text-muted" data-type="tooltip" data-original-title="{{{ date('l \a\t g:h A', strtotime($post->created_at)) }}}">
                                {{{ $post->created_at->diffForHumans() }}}
                            </span>
						</span>
					</span>
                    @if ($i != sizeof($posts) - 1)
                        <hr>
                    @endif
                @endforeach
            @else
                <p>You have not written any posts!</p>
            @endif
        </div>
    </div>
@endsection