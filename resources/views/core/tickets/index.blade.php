@extends('core.partials.layouts.master')

@section('title', 'My tickets')
@section('content')
	<h1>
		Tickets
		<a class="btn btn-info btn-md pull-right" href="/tickets/create">Create ticket</a>
	</h1>
    @if($tickets->count() > 0)
	@foreach($tickets->all() as $i => $ticket)
		<span>
			<img src="//cravatar.eu/avatar/{{{ str_slug($ticket->participants()->first()->user->name, '') }}}/50" data-type="tooltip" data-original-title="Started by {{{ $ticket->participants()->first()->user->name }}}"/>
			&nbsp;
			{!! link_to('tickets/' . $ticket->id, $ticket->title) !!}
		</span>
		@if ($i != sizeof($tickets) - 1)
		<hr>
		@endif
	@endforeach
	<hr>
	{!! $ticketLinks !!}
    @else
        <p>You have no open tickets.</p>
    @endif
@stop