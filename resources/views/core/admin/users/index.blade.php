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
			<table id="users" class="table table-striped table-hover">
				<thead>
				<tr>
					<th class="col-md-2">Name</th>
					<th class="col-md-2">Email</th>
					<th class="col-md-2">Confirmed</th>
					<th class="col-md-2">Created at</th>
					<th class="col-md-2">Role</th>
					<th class="col-md-2">Actions</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	
@stop

@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
			oTable = $('#users').dataTable( {
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sLengthMenu": "Records per page: _MENU_",
					"sSearch": '<div class="input-group input-group-sm"><span class="input-group-addon"><span class="fa fa-search"></span></span>',
					"sSearchPlaceholder": 'Search...'
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('admin/users/data') }}"
			});
			$('div.dataTables_filter input').addClass('form-control input-sm');
			$('div.dataTables_length label select').addClass('form-control');
		});
	</script>
@stop