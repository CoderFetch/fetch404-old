@extends('core.partials.layouts.master')

@section('title', 'Error')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<h3>Access denied</h3>
			<hr>
			<h5>You do not have permission to access this page or perform this action.</h5>

			<a href="/" class="btn btn-info"><i class="fa fa-home fa-fw"></i> Go home</a>
		</div>
	</div>
@stop