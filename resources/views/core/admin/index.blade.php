@extends('core.admin.layouts.default')

@section('title', 'Home')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Dashboard</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		@include('core.admin.partials.sidebar')
		<div class="col-md-9">
			<p>
				Welcome to your admin panel. Here you can manage various parts of your Fetch404 website.
			</p>
		</div>
	</div>
	<!-- /.row -->
	<div class="modal fade" id="welcomeModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Welcome!</h4>
				</div>
				<div class="modal-body">
					<p>Hi there!</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@stop