@extends('core.partials.layouts.master')

@section('title', 'Replying to ' . $thread->title)
@section('content')
	<h1>Replying to "{{{ $thread->title }}}"</h1>
	<hr>
	{!! Form::open(['route' => array('forum.post.thread.reply', $thread->channel->id, $thread->id)]) !!}
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
			<!-- Message Form Input -->
			<div class="form-group">
				{!! Form::textarea('body', null, ['class' => 'form-control', 'data-type' => 'summernote']) !!}
			</div>
	
			<!-- Submit Form Input -->
			<div class="form-group">
				{!! Form::submit('Reply', ['class' => 'btn btn-primary']) !!}
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@stop