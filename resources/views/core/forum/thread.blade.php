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
		<div class='pull-right'>
			@if($thread->canReply)
				<a class="btn btn-info" href="{{{ $thread->showReplyRoute }}}">Full Reply</a>
				<a class="btn btn-success" href="#quickReply">Quick Reply</a>
			@endif
			@if($thread->isLocked() && !Entrust::can('replyToAllThreads'))
				<button class="btn btn-info disabled" href="{{{ $thread->showReplyRoute }}}" disabled>
					Locked
				</button>
			@endif
			@unless(!Entrust::can('moderateThreads'))
				<div class="btn-group">
					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-wrench fa-fw"></i> Mod Actions <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						@if ($thread->locked == 0)
							@unless(!Entrust::can('lockThreads'))
								<li>
									<a href="{{{ route('forum.post.topics.lock', $thread) }}}">
										<i class="fa fa-lock"></i> Lock Thread
									</a>
								</li>
							@endunless
						@endif

						@if ($thread->locked == 1)
							@unless(!Entrust::can('unlockThreads'))
								<li>
									<a href="{{{ route('forum.post.topics.unlock', $thread) }}}">
										<i class="fa fa-key"></i> Unlock Thread
									</a>
								</li>
							@endunless
						@endif

						@if (!$thread->isPinned())
							@unless(!Entrust::can('pinThreads'))
								<li>
									<a href="{{{ route('forum.post.topics.pin', $thread) }}}">
										<i class="fa fa-thumb-tack"></i> Pin Thread
									</a>
								</li>
							@endunless
						@endif
					</ul>
				</div>
			@endunless
		</div>
		<h1 style="font-size: 19pt;">
			{{{ $thread->title }}}
		</h1>
		<small class="text-muted">
			Discussion in '{!! link_to($thread->channel->Route, $thread->channel->name) !!}'
			started by {!! link_to_route('profile.get.show', $thread->user->name, [$thread->user->slug, $thread->user->id]) !!},
			{{{ $thread->created_at->diffForHumans() }}}
		</small>
	</div>
	@foreach($thread->postsPaginated as $i => $post)
		@include('core.partials.forum.post', array('post' => $post))
	@endforeach
	{!! $thread->pageLinks !!}
	@if ($thread->canReply)
		{!! Form::open(['route' => array('forum.post.quick-reply.thread', $thread->channel->id, $thread->id), 'id' => 'quickReply']) !!}
		<textarea id="body" name="body" data-type="summernote" data-mentions="true"></textarea>
		<br>
		{!! Form::submit('Reply', ['class' => 'btn btn-primary']) !!}
		{!! Form::close() !!}
	@else
		<span class="text-right" style="float: right;">
			@if (!Auth::check())
			<p>Please log in to post on the forums.</p>
			@elseif (!Auth::user()->isConfirmed())
			<p>Please confirm your account to post on the forums.</p>
			@elseif ($thread->isLocked())
			<p>This thread is locked.</p>
			@elseif (!$thread->channel->can(6, Auth::user()))
			You do not have permission to post here.
			@endif
		</span>
	@endif
@endsection
