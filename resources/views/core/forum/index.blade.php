@extends('core.partials.layouts.master')

@section('title', 'Forums')
@section('content')
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li class="active"><a href="/forum">Forum</a></li>
	</ol>
	<br />
	@if (!$categories->isEmpty())
	@foreach($categories->all() as $category)
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="{{{ $category->Route }}}">{{{ $category->name }}}</a></h3>
		</div>
		<div class="panel-body">
			@if (!$category->channels->isEmpty())
			@foreach($category->channels->all() as $i => $channel)
			<span>
				<i class="fa fa-comment fa-fw fa-2x pull-left"></i>
				<h3 style="margin-top: 5px;"><a href="{{{ $channel->Route }}}">{{{ $channel->title }}}</a></h3>
				<small>
					<label>Discussions:</label> 
					{{{ $channel->topics()->count() }}} 
					<label>Messages:</label>
					{{{ $channel->posts()->count() }}}
				</small>
			</span>
			@if ($i != sizeof($category->channels) - 1)
			<hr>
			@endif
			@endforeach
			@endif
		</div>
	</div>
	@endforeach
	@else
	<p>No categories or channels have been defined! Uh-oh.</p>
	@endif
@stop