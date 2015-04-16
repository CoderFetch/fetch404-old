@extends('core.partials.layouts.master')
@section('title', 'Top posters')
{{-- Content here --}}
@section('content')
<h1>Top Posters</h1>
<div class="panel panel-default">
    <div class="panel-body">
        @if (!$top_posters->isEmpty())
            @foreach($top_posters->all() as $i => $user)
                <span>
						<i class="fa fa-trophy fa-fw fa-2x pull-left" style="color: {{{ ($i + 1 <= 5 ? 'gold' : ($i + 1 <= 7 ? 'silver' : '#CD7F32')) }}}"></i>
                        #{{{ ($i + 1) }}}
						<a href="{{{ $user->profileURL }}}">{{{ $user->name }}}</a>
						<span class="pull-right">
							with {{{ $user->posts()->count() }}} {{{ Pluralizer::plural('post', $user->posts()->count()) }}}
						</span>
					</span>
                @if ($i != sizeof($top_posters) - 1)
                    <hr>
                @endif
            @endforeach
        @else
            <p>Nobody has posted... at all. :(</p>
        @endif
    </div>
</div>
<small class="text-muted">
    Total posts: {{{ $totalPosts or 'N/A' }}}
</small>
@endsection