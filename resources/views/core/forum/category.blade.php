@extends('core.partials.layouts.master')

@section('title', $category->title)
@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/forum">Forum</a></li>
		<li class="active"><a href="{{{ $category->Route }}}">{{{ $category->name }}}</a></li>
	</ol>
	<br />
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{{ $category->name }}}</h3>
				</div>
				<div class="panel-body">
					@if (!$category->channels->isEmpty())
					@foreach($category->channels as $i => $channel)
					@if ($channel->canView(Auth::user()))
					<span>
						<i class="fa fa-comment fa-fw fa-2x pull-left"></i>
						<h3 style="margin-top: 5px;"><a href="{{{ $channel->Route }}}"@if ($channel->description != null)data-type="tooltip" data-original-title="{{{ $channel->description }}}" @endif>{{{ $channel->name }}}</a></h3>
						<small><label>Discussions:</label> {{{ $channel->topics()->count() }}} <label>Messages:</label> {{{ $channel->posts()->count() }}}</small>
					</span>
					@if ($i != sizeof($category->channels) - 1)
					<hr>
					@endif
					@endif
					@endforeach
					@else
					<p>Either no channels have been defined, or you don't have permission to access any of them. :(</p>
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
					<label>Posts:</label> {{{ sizeof($category->getPosts()) }}}
					<br>
					<label>Discussions:</label> {{{ sizeof($category->getTopics()) }}}
				</div>
			</div>
		</div>
	</div>
@stop