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
					This user is following nobody.
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Followers
				</div>
				<div class="panel-body">
					Nobody is following this user.
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">
						{{{ $user->name }}}
						@foreach($user->roles as $role)
						<span class="label label-{{{ $role->is_superuser == 1 ? 'danger' : 'success' }}}">
							{{{ $role->name }}}
						</span>
						@endforeach
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
								<div class="status-post">
									<!-- Status Form Input -->
									<div class="form-group">
										{!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => "What's on your mind?"]) !!}
									</div>

									<div class="form-group status-post-submit">
										{!! Form::submit('Post Status', ['class' => 'btn btn-primary btn-xs']) !!}
									</div>
								</div>
								<article class="media status-media">
									<div class="pull-left">
										<a href="{{{ $user->profileURL }}}">
											<img class="media-object" src="https://cravatar.eu/avatar/{{{ $user->slug }}}/30" alt="{{ $user->name }}">
										</a>
										{{--@include ('users.partials.avatar', ['user' => $status->user, 'class' => 'status-media-object'])--}}
									</div>

									<div class="media-body status-media-body">
										<h4 class="media-heading status-media-heading">{{{ $user->name }}}</h4>
										<p><small class="status-media-time">1 minute ago</small></p>

										Hi there!
									</div>
								</article>
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