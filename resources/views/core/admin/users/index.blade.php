@extends('core.admin.layouts.default')

@section('title', 'Users')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<h3>
					{{{ $title }}}

					<div class="pull-right">
						<a href="{{{ URL::to('admin/users/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create</a>
					</div>
				</h3>
			</div>
		</div>
	</div>
	<div class="row">
		@include('core.admin.partials.sidebar')
		<div class="col-md-9">
			<div class="panel panel-default">
				@if ($users->count() < 1)
				<p>
					There are no registered users.
				</p>
				@else
				<ul class="list-group">
					@foreach($users as $user)
					<li class="list-group-item">
						<a href="{{{ $user->profileURL }}}">
							{{{ $user->name }}}
						</a>
						&nbsp;
						@if ($user->isConfirmed())
							<span class="fa fa-check text-success" title="Confirmed"></span>
						@else
							<span class="fa fa-times text-danger" title="Not yet confirmed"></span>
						@endif

						<div class="pull-right">
							@if ($user->isBanned())
							{!! Form::open(['route' => array('admin.users.post.unban', $user)]) !!}
							{!! Form::submit('Unban User', ['class' => 'btn btn-success btn-xs']) !!}
							{!! Form::close() !!}
							@else
							{!! Form::open(['route' => array('admin.users.post.ban', $user)]) !!}
							{!! Form::submit('Ban User', ['class' => 'btn btn-danger btn-xs']) !!}
							{!! Form::close() !!}
							@endif
						</div>
					</li>
					@endforeach
				</ul>
				@endif
			</div>
		</div>
	</div>
	
@stop