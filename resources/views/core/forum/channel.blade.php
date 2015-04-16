@extends('core.partials.layouts.master')

@section('title', $channel->title)
@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/forum">Forum</a></li>
		<li><a href="{{{ $channel->category->Route }}}">{{{ $channel->category->name }}}</a></li>
		<li class="active"><a href="{{{ $channel->Route }}}">{{{ $channel->title }}}</a></li>
	</ol>
	<br />
	<div class="row">
		<div class="col-lg-7">
			<div class="panel panel-default">
				<div class="panel-heading">
					@if ($channel->canCreateThread)
					<a class="btn btn-success btn-xs pull-right" href="{{{ route('forum.get.channel.create.thread', ['slug' => $channel->slug]) }}}">Create thread</a>
					@endif
					<h3 class="panel-title">
						{{{ $channel->title }}}
					</h3>
				</div>
				<div class="panel-body">
					@if (!$channel->topics->isEmpty())
					@foreach($channel->topics as $i => $thread)
					<span>
						<i class="fa fa-comment fa-fw fa-2x pull-left"></i>
						<a href="{{{ $thread->Route }}}">{{{ $thread->title }}}</a>
						<span class="text-muted"> - by {{{ $thread->user->name }}}</span>
						<span class="pull-right">
							{{{ $thread->replyCount }}} replies
						</span>
					</span>
					@if ($i != sizeof($channel->topics) - 1)
					<hr>
					@endif
					@endforeach
					@else
					<p>Nobody has created a topic.</p>
					@endif
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-list-alt fa-fw"></i> Stats</h3>
				</div>
				<div class="panel-body">
					<label>Discussions:</label> {{{ sizeof($channel->topics) }}}		
					<br>
					<label>Posts:</label> {{{ sizeof($channel->posts) }}}		
				</div>
			</div>	
		</div>
	</div>
@stop