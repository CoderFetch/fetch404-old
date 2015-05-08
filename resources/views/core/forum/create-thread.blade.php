@extends('core.partials.layouts.master')

@section('title', 'Create thread')
@section('content')
	<h1>Creating thread in "{{{ $channel->name }}}"</h1>
	<hr>
	{!! Form::open(['route' => array('forum.post.channel.create.thread', $channel->id)]) !!}
	<div class="row">
		<div class="col-md-7">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			<!-- Subject Form Input -->
			<div class="form-group">
				{!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Thread title...']) !!}
			</div>

			<!-- Message Form Input -->
			<div class="form-group">
				{!! Form::textarea('body', null, ['class' => 'form-control', 'data-type' => 'summernote']) !!}
			</div>
	
			<!-- Submit Form Input -->
			<div class="form-group">
				{!! Form::submit('Create thread', ['class' => 'btn btn-primary']) !!}
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@stop