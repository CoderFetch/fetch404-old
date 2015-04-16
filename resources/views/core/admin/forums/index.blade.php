@extends('core.admin.layouts.default')

@section('title', 'Admin Panel')

@section('content')
	<div class="page-header">
		<h3>
			{{{ $title }}}

			<div class="pull-right">
				<a href="{{{ URL::to('admin/users/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create</a>
			</div>
		</h3>
	</div>
	@if ($categories->count() < 1)
	<p>No categories have been defined.</p>
	@else
	@foreach($categories as $category)
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{{{ $category->name }}}</h3>
		</div>
		<div class="panel-body">
			@if (!$category->channels->isEmpty())
			<ul class="list-group">
			@foreach($category->channels as $i => $channel)
			<span>
				<div class="pull-right">
					<button class="btn btn-info btn-sm">Edit</button>
					<button class="btn btn-danger btn-sm">Delete</button>
				</div>
				<h4 style="margin-top: 5px;">{{{ $channel->title }}}</h4>
			</span>
			<hr>
			@endforeach
			</ul>
			@endif
		</div>
	</div>
	@endforeach
	@endif
@stop