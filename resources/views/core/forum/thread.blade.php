@extends('core.partials.layouts.master')

@section('title', $thread->title)

@section('content')
@if (count($errors) > 0)
<div class="alert alert-danger">
	<strong>Whoops!</strong> There were some problems with your input.<br><br>
	<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
<br />
@endif
<ol class="breadcrumb">
	<li><a href="/">Home</a></li>
	<li><a href="/forum">Forum</a></li>
	<li><a href="{{{ $thread->channel->category->Route }}}">{{{ $thread->channel->category->name }}}</a></li>
	<li><a href="{{{ $thread->channel->Route }}}">{{{ $thread->channel->name }}}</a></li>
	<li class="active"><a href="{{{ $thread->Route }}}">{{{ $thread->title }}}</a></li>
</ol>
<div class="page-header">
	@if($thread->canReply)
  	<div class='pull-right'>
		<a class="btn btn-info" href="{{{ $thread->showReplyRoute }}}">Full Reply</a>
		&nbsp;
		<a class="btn btn-success" href="#quickReply">Quick Reply</a>
  	</div>
	@endif
	<h1 style="font-size: 19pt;">
		{{{ $thread->title }}}
	</h1>
	<small class="text-muted">Discussion in '{{{ $thread->channel->name }}}' started by {{{ $thread->user->name }}}, {{{ date('l \a\t g:h A', strtotime($thread->created_at)) }}}</small>
</div>
@foreach($thread->postsPaginated as $i => $post)
	<div class="panel panel-default" id="post-{{{ $post->id }}}">
		<div class="panel-heading">
		  <a href="{{{ $thread->Route }}}">@if ($post->getArrayIndex() > 0)RE: @endif{{{ $thread->title }}}</a>
		</div>
		<div class="panel-body">
		  <div class="row">
			<div class="col-md-3">
				<center>
					<img class="img-rounded" src="{{{ $post->user->getAvatarURL(true) }}}" height="80" width="80" />
					<br /><br />

					<strong><a href="{{{ $post->user->profileURL }}}">{{{ $post->user->name }}}</a></strong>
					<hr>
					{{{ $post->user->posts()->count() }}} {{{ Pluralizer::plural('post', $post->user->posts()->count()) }}}
					<br /><br />
				</center>
			</div>

			<div class="col-md-9">
			  	By <a href="{{{ $post->user->profileURL }}}">{{{ $post->user->name }}}</a>
				&raquo;
			  	<span data-type="tooltip" data-trigger="hover" data-original-title="{{{ date('l \a\t g:h A', strtotime($post->created_at)) }}}">{{{ $post->created_at->diffForHumans() }}}</span>
				<span class="pull-right">
					{!! Form::open(['route' => 'forum.post.posts.like', 'style' => 'display: inline;']) !!}
						{!! Form::button('<span class="glyphicon glyphicon-exclamation-sign"></span>', array('class' => 'btn btn-warning btn-xs', 'type' => 'submit')) !!}
					{!! Form::close() !!}
					{{--<a rel="tooltip" title="Report post" href="/forum/report_post/?pid=21&amp;tid=3" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-exclamation-sign"></span></a>--}}

					{{--<a rel="tooltip" title="Quote post" href="/forum/create_post/?tid=3&amp;qid=21&amp;fid=2" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-share"></span></a>--}}
  	      	  	</span>
				<hr>
			  	{!! Mentions::parse(Purifier::clean($post->content)) !!}
			  	<br />
				@unless(Auth::check() && Auth::id() == $post->user->id)
				<span class="pull-right">
					@unless($post->isLikedBy(Auth::user()))
					{!! Form::open(['route' => array('forum.post.posts.like', $post), 'style' => 'display: inline;']) !!}
						<button data-type="tooltip" title="Give reputation" type="submit" class="btn btn-success btn-sm give-rep"><span class="glyphicon glyphicon-thumbs-up"></span></button>
					{!! Form::close() !!}
					@endunless
					@unless(!$post->isLikedBy(Auth::user()))
					{!! Form::open(['route' => array('forum.post.posts.dislike', $post), 'style' => 'display: inline;']) !!}
						<button data-type="tooltip" title="Remove reputation" type="submit" class="btn btn-danger btn-sm give-rep"><span class="glyphicon glyphicon-thumbs-down"></span></button>
					{!! Form::close() !!}
					@endunless
					<button class="btn btn-default btn-sm count-rep"><strong>{{{ $post->likes()->count() }}}</strong></button>
  	      	  	</span>
				<br />
				@endunless
			  	<hr>
			</div>
		  </div>
		</div>
	</div>
@endforeach
{!! $thread->pageLinks !!}
@if ($thread->canReply)
{!! Form::open(['route' => array('forum.post.quick-reply.thread', $thread->slug, $thread->id), 'id' => 'quickReply']) !!}
<textarea id="body" name="body" data-type="summernote" data-mentions="true"></textarea>
<br>
{!! Form::submit('Reply', ['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
@else
@if (!Auth::check())
<p>Please log in to post on the forums.</p>
@elseif (!Auth::user()->isConfirmed())
<p>Please confirm your account to post on the forums.</p>
@endif
@endif
@endsection

@section('scripts')
@endsection