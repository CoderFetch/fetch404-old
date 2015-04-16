@extends('core.partials.layouts.master')

@section('title', $user->name . '\'s profile')

@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/members">Members</a></li>
		<li class="active"><a href="{{{ $user->profileURL }}}">{{{ $user->name }}}</a></li>
	</ol>
	<div class="row">
		<div class="col-md-3">
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
		<div class="col-md-offset-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">
						{{{ $user->name }}}
					</h2>
				</div>
				<div class="panel-body">

				</div>
			</div>
		</div>
	</div>
@stop