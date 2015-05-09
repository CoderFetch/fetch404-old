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
		<small class="text-muted">Discussion in '{{{ $thread->channel->name }}}' started by {{{ $thread->user->name }}}, {{{ date('l \a\t g:h A', strtotime($thread->created_at)) }}}</small>
	</div>
	@foreach($thread->postsPaginated as $i => $post)
		<div class="panel panel-primary" id="post-{{{ $post->id }}}">
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
							<br />
							@foreach($post->user->roles as $role)
								<span class="label label-{{{ ($role->is_superuser == 1) ? 'danger' : ($role->id == 3 ? 'success' : ($role->id == 1 ? 'danger' : 'warning')) }}}">
								{{{ $role->name }}}
							</span>
							@endforeach
							@if ($post->user->is_online == 1)
							<br />
							<span class="label label-success">
								Online
							</span>
							@endif
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
						@if (Auth::check() && Auth::id() == $post->user->id)

						@endif
						@unless(!Auth::check())
							<a class="btn btn-warning btn-xs" href="{{{ route('forum.get.posts.report', $post) }}}">
								<span class="glyphicon glyphicon-exclamation-sign"></span>
							</a>
						@endunless
					</span>
						<hr>
						{!! Mentions::parse(Purifier::clean($post->content)) !!}
						<br />
						@if (Auth::check())
							<span class="pull-right">
						@unless(Auth::id() == $post->user->id)
									@if (!$post->isLikedBy(Auth::user()))
										{!! Form::open(['route' => array('forum.post.posts.like', $post), 'style' => 'display: inline;']) !!}
										<button data-type="tooltip" data-original-title="Give reputation" type="submit" class="btn btn-success btn-sm give-rep">
											<span class="glyphicon glyphicon-thumbs-up"></span>
										</button>
										{!! Form::close() !!}
									@endif
									@if ($post->isLikedBy(Auth::user()))
										{!! Form::open(['route' => array('forum.post.posts.dislike', $post), 'style' => 'display: inline;']) !!}
										<button data-type="tooltip" data-original-title="Remove reputation" type="submit" class="btn btn-danger btn-sm give-rep"><span class="glyphicon glyphicon-thumbs-down"></span></button>
										{!! Form::close() !!}
									@endif
								@endunless
								<button class="btn btn-default btn-sm count-rep" data-toggle="modal" data-target="#likes-{{{ $post->id }}}"><strong>{{{ $post->likes()->count() }}}</strong></button>
					</span>
							<br />
						@endif
						<hr>
					</div>
				</div>
			</div>
			@if (($post->reports()->count() == 0 && $post->likes()->count() > 0) || (Entrust::can('viewReportedPosts') && $post->likes()->count() > 0))
				<div class="panel-footer">
					{!! $post->getLikeNames() !!}
				</div>
			@endif
		</div>

		<!-- Likes modal -->
		<div class="modal fade" id="likes-{{{ $post->id }}}" tabindex="-1" role="dialog" aria-labelledby="likesLabel{{{ $post->id }}}" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="likesLabel{{{ $post->id }}}">Post Likes</h4>
					</div>
					<div class="modal-body">
						@unless($post->likes()->count() == 0)
							<ul class="list-group">
								{!! $post->genLikesModalHTML(false) !!}
							</ul>
						@endunless

						@unless($post->likes()->count() > 0)
							<p>This post has not received any likes.</p>
						@endunless
					</div>
				</div>
			</div>
		</div>
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
