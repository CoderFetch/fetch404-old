@extends('core.partials.layouts.master')
@section('title', $thread->subject)

@section('content')
	<h1>
		Viewing conversation
		<div class="pull-right">
			<div class="btn-group">
				{{--@if ($thread->canManage)--}}
				{{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">--}}
					{{--<i class="fa fa-cog"></i></span>--}}
				{{--</button>--}}
				{{--<ul class="dropdown-menu" role="menu">--}}
					{{--<li><a href="#">Disable replies</a></li>--}}
					{{--<li><a href="{{{ route('conversations.delete', ['id' => $thread->id]) }}}">Delete conversation</a></li>--}}
				{{--</ul>--}}
				{{--@endif--}}
				{!! Form::open(['route' => array('conversations.leave', $thread)]) !!}
				<button type="submit" class="btn btn-default">
					Leave Conversation
				</button>
				{!! Form::close() !!}
			</div>
		</div>
	</h1>
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
	@foreach($thread->messages as $i => $message)
	  <div class="panel panel-default">
		<div class="panel-heading">
		  <a href="/conversations/{{{ $thread->id }}}">{!! Purifier::clean($thread->subject) !!}</a>
		  @if ($thread->canManage)
		  <div class="pull-right">
			  <div class="btn-group">
				  <button type="button" class="btn btn-default btn-xs pull-right dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-cog"></i></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="{{{ route('conversations.users.kick', ['conversation' => $thread->id, 'user' => $message->user->id]) }}}">Kick from conversation</a></li>
					<li><a href="#">Delete message</a></li>
				  </ul>
			  </div>
		  </div>
		  @endif 	    
		</div>
		<div class="panel-body">
		  <div class="row">
			<div class="col-md-3">
				<center>
					<img class="img-rounded" src="{{{ $message->user->getAvatarURL(100, true) }}}" height="100" width="100" />
					<br /><br />
					<strong><a href="{{{ $message->user->profileURL }}}">{{{ $message->user->name }}}</a></strong>
				</center>
			</div>

			<div class="col-md-8">
			  By <a href="{{{ $message->user->profileURL }}}">{{{ $message->user->name }}}</a> &raquo; <span data-type="tooltip" data-trigger="hover" data-original-title="{{{ date('j M y, h:i A', strtotime($message->created_at)) }}}">{{{ $message->created_at->diffForHumans() }}}</span>
			  <hr>
			  {!! nl2br(Purifier::clean($message->body)) !!}
			  <hr>
			</div>

		  </div>
		</div>
	  </div>
	@endforeach

	<h2>Add a new message</h2>
	{!! Form::open(['route' => ['conversations.update', $thread->id], 'method' => 'PUT']) !!}
	<!-- Message Form Input -->
	<div class="form-group">
		{!! Form::textarea('message', null, ['class' => 'form-control', 'data-type' => 'summernote']) !!}
	</div>

	<!-- Submit Form Input -->
	<div class="form-group">
		{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
	</div>
	{!! Form::close() !!}
@stop