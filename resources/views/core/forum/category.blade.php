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
		<div class="col-lg-7">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{{ $category->name }}}</h3>
				</div>
				<div class="panel-body">
					@if (!$category->channels->isEmpty())
					@foreach($category->channels as $i => $channel)
					<span>
						<i class="fa fa-comment fa-fw fa-2x pull-left"></i>
						<h3 style="margin-top: 5px;"><a href="{{{ $channel->Route }}}">{{{ $channel->title }}}</a></h3>
						<small><label>Discussions:</label> {{{ $channel->topics()->count() }}} <label>Messages:</label> {{{ $channel->posts()->count() }}}</small>
					</span>
					@if ($i != sizeof($category->channels) - 1)
					<hr>
					@endif
					@endforeach
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
					<label>Discussions:</label> {{{ sizeof($category->topics) }}}
				</div>
			</div>	
		</div>
	</div>
@stop