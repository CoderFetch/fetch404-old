@extends('core.partials.layouts.master')

@section('title', 'Error')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<h3>Page not found</h3>
				<hr>
				<h4>Are you sure you're not looking for something else?</h5>
	
				<a href="/" class="btn btn-info btn-lg"><i class="fa fa-home fa-fw"></i> Go home</a>
				<a href="javascript: history.back()" class="btn btn-info btn-lg"><i class="fa fa-arrow-left fa-fw"></i> Go to the previous page</a>
			</div>
		</div>
	</div>
@stop