@extends('core.partials.layouts.master')

@section('content')
<h1>Create a new conversation</h1>
{!! Form::open(['route' => 'conversations.store']) !!}
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
			{!! Form::label('subject', 'Subject', ['class' => 'control-label']) !!}
			{!! Form::text('subject', null, ['class' => 'form-control']) !!}
		</div>
	
		<!-- Recipient Form Input -->
		<div class="form-group">
			{!! Form::label('recipient', 'Recipients', ['class' => 'control-label']) !!}
			{!! Form::select('recipients[]', $names, null, ['id' => 'user_list', 'class' => 'form-control', 'multiple']) !!}
		</div>

		<!-- Message Form Input -->
		<div class="form-group">
			{!! Form::label('message', 'Message', ['class' => 'control-label']) !!}
			{!! Form::textarea('message', null, ['class' => 'form-control', 'data-type' => 'summernote']) !!}
		</div>
	
		<!-- Submit Form Input -->
		<div class="form-group">
			{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
		</div>
	</div>
</div>
{!! Form::close() !!}
@stop

@section('scripts')
	<script>
		$('#user_list').select2({
			placeholder: 'Who should we send the message to?'
		});
	</script>
@stop