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
					<div class="pull-right">
						<a class="btn btn-success btn-xs" href="{{{ route('admin.forum.get.edit.category', ['category' => $category->id]) }}}">Edit</a>
						{!! Form::open(['route' => array('admin.forum.post.delete.category', $category), 'style' => 'display: inline;']) !!}
						{!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs', 'onclick' => 'return confirm(\'Are you sure?\nWARNING: Deleting this category will delete all child channels, topics, and posts.\')']) !!}
						{!! Form::close() !!}
						<a class="btn btn-primary btn-xs" href="{{{ route('admin.forum.get.category.create-channel', $category) }}}">
							New channel
						</a>
					</div>
					<h3 class="panel-title">{{{ $category->name }}}</h3>
				</div>
				<div class="panel-body">
					@if (!$category->channels->isEmpty())
					<ul class="list-group">
					@foreach($category->channels as $i => $channel)
					<span>
						<div class="pull-right">
							<a class="btn btn-info btn-sm" href="{{{ route('admin.forum.get.edit.channel', $channel) }}}">Edit</a>
							{!! Form::open(['route' => array('admin.forum.post.delete.channel', $channel), 'style' => 'display: inline;']) !!}
							{!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm', 'onclick' => 'return confirm(\'Are you sure?\nWARNING: Deleting this channel will delete all child topics and posts.\')']) !!}
							{!! Form::close() !!}
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