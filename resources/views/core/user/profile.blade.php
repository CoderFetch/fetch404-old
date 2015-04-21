@extends('core.partials.layouts.master')

@section('title', $user->name . '\'s profile')

@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/members">Members</a></li>
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
					<label>Joined:</label> {{{ $user->created_at->format('M j, Y') }}}
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
								Coming soon
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