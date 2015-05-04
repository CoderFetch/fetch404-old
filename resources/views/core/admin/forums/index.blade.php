@extends('core.admin.layouts.default')

@section('title', 'Admin Panel')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<h3>
					{{{ $title }}}

					<div class="pull-right">
						<a href="{{{ route('admin.forum.get.create.category') }}}" class="btn btn-small btn-info"><span class="glyphicon glyphicon-plus-sign"></span> Create</a>
					</div>
				</h3>
			</div>
		</div>
	</div>
	<div class="row">
		@include('core.admin.partials.sidebar')
		<div class="col-md-9">
			@if ($categories->count() < 1)
			<p>No categories have been defined.</p>
			@else
			@foreach($categories as $category)
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-success btn-xs pull-right" href="{{{ route('admin.forum.get.edit.category', ['category' => $category->id]) }}}">Edit</a>
					<h3 class="panel-title">{{{ $category->name }}}</h3>
				</div>
				<div class="panel-body">
					@if (!$category->channels->isEmpty())
					<ul class="list-group">
					@foreach($category->channels as $i => $channel)
					<span>
						<div class="pull-right">
							<a class="btn btn-info btn-sm" href="{{{ route('admin.forum.get.edit.channel', $channel) }}}">Edit</a>
							<button class="btn btn-danger btn-sm">Delete</button>
						</div>
						<h4 style="margin-top: 5px;">{{{ $channel->name }}}</h4>
					</span>
					@if ($i != $category->channels->count() - 1)
					<hr>
					@endif
					@endforeach
					</ul>
					@else
					<p>No channels have been defined for this category.</p>
					@endif
				</div>
			</div>
		@endforeach
		</div>
	</div>
	@endif
@stop