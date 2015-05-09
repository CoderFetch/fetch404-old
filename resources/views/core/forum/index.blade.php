@extends('core.partials.layouts.master')

@section('title', 'Forums')
@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li class="active"><a href="/forum">Forum</a></li>
	</ol>
	<br />
	<div class="row">
		<div class="col-md-9">
		@if (!$categories->isEmpty())
			@foreach($categories->all() as $category)
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><a href="{{{ $category->Route }}}">{{{ $category->name }}}</a></h3>
					</div>
					<div class="panel-body">
					@if (!$category->channels->isEmpty())
					@foreach($category->channels->all() as $i => $channel)
					@if ($channel->canView(Auth::user()))
						<span>
							<i class="fa fa-comment fa-fw fa-2x pull-left"></i>
							<h3 style="margin-top: 5px;"><a href="{{{ $channel->Route }}}">{{{ $channel->name }}}</a></h3>
							<small>
								<label>Discussions:</label>
								{{{ $channel->topics()->count() }}}
								<label>Messages:</label>
								{{{ $channel->posts()->count() }}}
								<span style="float: right;">
									@if ($channel->posts()->count() > 0)
									<span class="text-muted">
										Latest post by {!! link_to_route('profile.get.show', $channel->getLatestPost()->user->name, [$channel->getLatestPost()->user->slug, $channel->getLatestPost()->user->id]) !!} in
										{!! link_to_route('forum.get.show.thread', $channel->getLatestPost()->topic->title, [$channel->getLatestPost()->topic->channel->id, $channel->getLatestPost()->topic->id]) !!}
									</span>
									@endif
								</span>
							</small>
						</span>
						@if ($i != sizeof($category->channels) - 1)
						<hr>
						@endif
					@endif
					@endforeach
					@else
						<p>Either no channels have been defined, or you don't have permission to access any of them. :(</p>
					@endif
					</div>
				</div>
				@endforeach
			@else
				<p>Either no categories have been defined, or you don't have permission to access any of them. :(</p>
			@endif
		</div>
		<div class="col-md-3">
		@include('core.forum.partials.online-users')
		@include('core.forum.partials.latest-threads')
		@include('core.forum.partials.stats')
		</div>
	</div>
@stop