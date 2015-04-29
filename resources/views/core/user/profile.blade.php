@extends('core.partials.layouts.master')

@section('title', $user->name . '\'s profile')

@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="{{{ route('members.get.index') }}}">Members</a></li>
		<li class="active"><a href="{{{ $user->profileURL }}}">{{{ $user->name }}}</a></li>
	</ol>
	<div class="row">
		<div class="col-md-3" style="border-right: 1px solid lightgray;">
			<img src="{{{ $user->getAvatarURL() }}}" />
			<br>
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Stats
				</div>
				<div class="panel-body">
					<label>Joined:</label> {{{ $user->getJoinedOn() }}}
					<br>
					<label>Last Activity:</label> {{{ $user->getLastActivity() }}}
					<br>
					<label>Messages: </label> {{{ $user->posts()->count() }}}
					<br>
					<label>Likes Received: </label> 0
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Following
				</div>
				<div class="panel-body">
					@if ($user->followedUsers->isEmpty())
					<p>
						This user is following nobody.
					</p>
					@else
					@foreach($user->followedUsers as $followedUser)
					<a href="{{{ $followedUser->profileURL }}}">
						<img src="{{{ $followedUser->getAvatarURL(35) }}}" height="35" width="30" style="box-shadow: 0 0 1px 1px silver;" data-type="tooltip" data-original-title="{{{ $followedUser->getName() }}}" />
					</a>
					@endforeach
					@endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Followers
				</div>
				<div class="panel-body">
					@if ($user->followers->isEmpty())
					<p>
						Nobody is following this user.
					</p>
					@else
					@foreach($user->followers as $follower)
					<a href="{{{ $follower->profileURL }}}">
						<img src="{{{ $follower->getAvatarURL(35) }}}" height="35" width="30" style="box-shadow: 0 0 1px 1px silver;" data-type="tooltip" data-original-title="{{{ $follower->getName() }}}" />
					</a>
					@endforeach
					@endif
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">
						@if (Auth::check() && Auth::user()->isConfirmed() && Auth::id() != $user->getId())
						<div class="pull-right">
							@if (!Auth::user()->isFollowing($user))
							{!! Form::open(['route' => array('user.post.follow', $user)]) !!}
							{!! Form::submit('Follow', ['class' => 'btn btn-info btn-xs']) !!}
							{!! Form::close() !!}
							@else
							{!! Form::open(['route' => array('user.post.unfollow', $user)]) !!}
							{!! Form::submit('Unfollow', ['class' => 'btn btn-info btn-xs']) !!}
							{!! Form::close() !!}
							@endif
						</div>
						@endif
						{{{ $user->name }}}
						@foreach($user->roles as $role)
						<span class="label label-{{{ $role->is_superuser == 1 ? 'danger' : 'success' }}}">
							{{{ $role->name }}}
						</span>
						@endforeach
						@if ($user->currentStatus() != null)
						<br />
						<small>
							<em>
								{{{ $user->currentStatus()->body }}}
							</em>
							 - {{{ $user->currentStatus()->formattedCreatedAt() }}}
						</small>
						@endif
					</h2>
				</div>
				<div class="panel-body">
					<div role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#profile-posts" aria-controls="profile-posts" role="tab" data-toggle="tab">Profile posts</a></li>
							<li role="presentation"><a href="#postings" aria-controls="postings" role="tab" data-toggle="tab">Postings</a></li>
							<li role="presentation"><a href="#information" aria-controls="information" role="tab" data-toggle="tab">Information</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane fade in active" id="profile-posts">
								@if (Auth::check() && Auth::user()->isConfirmed())
								<div class="status-post">
									{!! Form::open(['route' => array('user.profile-posts.post.create', $user)]) !!}
									<!-- Status Form Input -->
									<div class="form-group">
										{!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => $user->isUser(Auth::user()) ? "What's on your mind?" : "Post something..."]) !!}
									</div>

									<div class="form-group status-post-submit">
										{!! Form::submit('Post', ['class' => 'btn btn-primary btn-sm']) !!}
									</div>

									{!! Form::close() !!}
								</div>
								@if ($errors->has("body"))
								<span class="help-block text-danger">
									{{{ $errors->first('body') }}}
								</span>
								@endif
								@endif
								@if ($user->profilePosts->isEmpty())
								<p>Nobody has written anything on this user's profile.</p>
								@else
								@foreach($user->profilePosts as $profilePost)
								<article class="media status-media">
									<div class="pull-left">
										<a href="{{{ $profilePost->user->profileURL }}}">
											<img class="media-object" src="https://cravatar.eu/avatar/{{{ $profilePost->user->slug }}}/30" alt="{{ $profilePost->user->name }}">
										</a>
									</div>

									<div class="pull-right">
										<div class="btn-group">
											@if (Auth::check() && $profilePost->user->id == Auth::id())
											{!! Form::open(['route' => array('user.profile-posts.post.delete', $user, $profilePost)]) !!}
											{!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
											{!! Form::close() !!}
											@endif
										</div>
									</div>

									<div class="media-body status-media-body">
										<h4 class="media-heading status-media-heading">{{ $profilePost->user->name }}</h4>
										<p><small class="status-media-time">{{{ $profilePost->formattedCreatedAt() }}}</small></p>
										{!! Purifier::clean($profilePost->body) !!}
									</div>
								</article>
								@endforeach
								@endif
							</div>
							<div role="tabpanel" class="tab-pane fade" id="postings">
								Coming soon
							</div>
							<div role="tabpanel" class="tab-pane fade" id="information">
								Coming soon
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop