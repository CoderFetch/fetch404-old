@extends('core.partials.layouts.master')

@section('title', 'Conversations')
@section('content')
	<h1>
		Conversations
		<a class="btn btn-info btn-md pull-right" href="/conversations/create">Start conversation</a>
	</h1>
    @if($threads->count() > 0)
	@foreach($threads as $i => $thread)
		<span{{{ $thread->isUnread(Auth::id()) ? ' style="background-color: #3498db"' : ''}}}>
			<img src="{{{ $thread->user()->getAvatarURL(45) }}}" data-type="tooltip" data-original-title="Started by {{{ $thread->user()->name }}}" height="45" width="45" />
			&nbsp;
			{!! link_to('conversations/' . $thread->id, $thread->subject) !!}
			<span class="text-muted"> -  Participants: <b>{{{ $thread->participantsString(null, ['name']) }}}</b></span>
		</span>
		@if ($i != sizeof($threads) - 1)
		<hr>
		@endif
	@endforeach
	<hr>
	{!! $threadLinks !!}
    @else
        <p>You have no conversations.</p>
    @endif
@stop