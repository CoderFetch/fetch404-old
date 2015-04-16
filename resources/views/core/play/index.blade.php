@extends('core.partials.layouts.master')

@section('title', 'Play')

@section('content')
	@if (MCServer::where('main', '=', 1)->count() == 0)
	<strong>No default server defined. Ask Leo to fix this.</strong>
	@else
	@eval(require(base_path() . '/core/functions/minecraft/global.php'))
	<div class="row">
		<div class="col-md-3">
			<div class="well">
				<table class="table">
					<tr>
						<td>
							<b>Status:</b>
						</td>
						<td>
							@if(!empty($Info)) <i class="fa fa-check text-success"></i> Online @else <i class="fa fa-times fa-fw text-danger"></i> Offline @endif
						</td>
					</tr>
					<tr>
						<td>
							<b>Players:</b>
						</td>
						<td>
							@if ($receivedInfo)
							{{{ $Info['players']['online'] }}}/{{{ $Info['players']['max'] }}} players
							@else
							<b>0/0 players</b>
							@endif
						</td>
					</tr>
					<tr>
						<td>
							<b>Queried in:</b>
						</td>
						<td>
							{{{ $Timer }}}s
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="col-md-9">
			<div class="well">
				<h3>Players online</h3>
				@eval($servers = MCServer::where('active', '=', 1)->where('main', '=', 0)->get())
				@eval(require(base_path() . '/core/functions/minecraft/server.php'))
				@eval($serverStatus = new ServerStatus())
				@foreach($servers as $server)
					<h3>{{{ $server->name }}}</h3>
					@eval($serverStatus->serverPlay($server->ip, $server->port, $server->name))
				@endforeach
			</div>
		</div>
	</div>
	@endif
@stop