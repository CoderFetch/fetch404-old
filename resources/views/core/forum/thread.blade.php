@extends('core.partials.layouts.master')

@section('title', $thread->title)

@section('content')
<!-- 
	@foreach($thread->posts as $post)
	@eval(print($post->user->name))
	@endforeach
 -->
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
	<li><a href="{{{ $thread->channel->Route }}}">{{{ $thread->channel->title }}}</a></li>
	<li class="active"><a href="{{{ $thread->Route }}}">{{{ $thread->title }}}</a></li>
</ol>
<div class="page-header">
	@if($thread->canReply)
  	<div class='btn-toolbar pull-right'>
    	<div class='btn-group'>
			<a class="btn btn-info pull-right" href="{{{ $thread->showReplyRoute }}}">Full Reply</a>
			&nbsp;
			<a class="btn btn-success pull-right" href="#quickReply">Quick Reply</a>
    	</div>
  	</div>
	@endif
	<h1 style="font-size: 19pt;">
		{{{ $thread->title }}}
	</h1>
	<small class="text-muted">Discussion in '{{{ $thread->channel->title }}}' started by {{{ $thread->user->name }}}, {{{ date('l \a\t g:h A', strtotime($thread->created_at)) }}}</small>
</div>
@foreach($thread->postsPaginated as $i => $post)
	<div class="panel panel-default" id="post-{{{ $post->id }}}">
		<div class="panel-heading">
		  <a href="{{{ $thread->Route }}}">@if ($post->getArrayIndex() > 0)RE: @endif{{{ $thread->title }}}</a>
		</div>
		<div class="panel-body" id="post-1">
		  <div class="row">
			<div class="col-md-3">
				<center>
					<img class="img-rounded" src="{{{ Auth::user()->getAvatarURL(true) }}}" height="50" width="50" />
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
			  <span data-type="tooltip" data-trigger="hover" data-original-title="{{{ date('l \a\t g:h A', strtotime($thread->created_at)) }}}">{{{ $post->created_at->diffForHumans() }}}</span>
			  <hr>
			  {!! Mentions::parse(Purifier::clean($post->content)) !!}
			  <br />
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