@extends('core.partials.layouts.master')

@section('title', 'Home')

@section('content')
	<ol class="breadcrumb">
		<li class="active"><a href="/">Home</a></li>
	</ol>
	<div class="row">
		<div class="col-md-9">
			<h2>
				News
				@if (Entrust::can('create_news_posts'))
					<a class="btn btn-success pull-right" href="{{{ route('news.get.create') }}}">Create post</a>
				@endif
			</h2>
			<hr>
			@if ($news->count() > 0)
			@foreach($news->get() as $newsPost)
				<div class="panel panel-primary">
					<div class="panel-heading">
						<a style="color: white;" href="{{{ route('news.show', ['id' => $newsPost->id]) }}}">{{{ $newsPost->title }}}</a>
		      			<span class="pull-right">
		        			<a style="color: white;" href="{{{ route('profile.get.show', ['slug' => $newsPost->user->slug, 'id' => $newsPost->user->id]) }}}">{{{ $newsPost->user->name }}}</a>
							&nbsp;
							<img src="{{{ $newsPost->user->getAvatarURL(25) }}}" height="25" width="25" />
						</span>
					</div>
					<div class="panel-body">
						{!! Mentions::parse(Purifier::clean($newsPost->content)) !!}
						<br />
						<span class="label label-info">
							{{{ $newsPost->created_at->format('l \a\t g:h A') }}}
						</span>
						<span class="pull-right">
							@if ($newsPost->tags->count() > 0)
							<span class="label label-danger">
								{{{ $newsPost->tagNames() }}}
							</span>
							@endif
						</span>
					</div>
				</div>
			@endforeach
			@else
			<p>There is no news.</p>
			@endif
		</div>

		<div class="col-md-3">
			@include('core.forum.partials.online-users')
		</div>
	</div>
@stop