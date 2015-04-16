@extends('core.admin.layouts.default')

{{-- Content --}}
@section('content')
	<!-- Tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
			<li><a href="#tab-permissions" data-toggle="tab">Permissions</a></li>
		</ul>
	<!-- ./ tabs -->

	{{-- Edit Role Form --}}
	<form class="form-horizontal" method="post" action="" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

		<!-- Tabs Content -->
		<div class="tab-content">
			<!-- General tab -->
			<div class="tab-pane active" id="tab-general">
				<!-- Name -->
				<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="name">Name</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $role->name) }}}" />
						{!! $errors->first('name', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- Display Name -->
				<div class="form-group {{{ $errors->has('display_name') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="name">Display Name</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="display_name" id="name" value="{{{ Input::old('name', isset($role->display_name) ? $role->display_name : $role->name) }}}" />
						{!! $errors->first('display_name', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- Protected -->
				<div class="form-group">
					<label class="col-md-2 control-label" for="protected">Protected <i class="fa fa-question-circle" data-type="tooltip" data-original-title="<h5>When a role is protected, it can not be deleted.</h5>"></i></label>
                    <div class="col-md-10">
    					<div class="checkbox">
    						<input type="checkbox" name="is_protected" id="is_protected" value="1"{{{ isset($role['is_protected']) && $role['is_protected'] == 1 ? ' checked="checked"' : ''}}}/>
    					</div>
                    </div>
				</div>
				<!-- Is superuser -->
				<div class="form-group">
					<label class="col-md-2 control-label" for="is_superuser">Is superuser <i class="fa fa-question-circle" data-type="tooltip" data-original-title="<h5>Superuser roles have every permission.</h5>"></i></label>
                    <div class="col-md-10">
    					<div class="checkbox">
    						<input type="checkbox" name="is_superuser" id="is_superuser" value="1"{{{ isset($role['is_superuser']) && $role['is_superuser'] == 1 ? ' checked="checked"' : ''}}} />
    					</div>
                    </div>
				</div>
				<!-- ./ name -->
			</div>
			<!-- ./ general tab -->

			<!-- Permissions tab -->
			<div class="tab-pane" id="tab-permissions">
				<div class="form-group">
					@foreach ($permissions as $permission)
					<label>
						<input type="hidden" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="0" />
						<input type="checkbox" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="1"{{{ (isset($permission['checked']) && $permission['checked'] == true ? ' checked="checked"' : '')}}} />
						{{{ $permission['display_name'] }}}
					</label>
					@endforeach
				</div>
			</div>
			<!-- ./ permissions tab -->
		</div>
		<!-- ./ tabs content -->

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="submit" class="btn btn-success">Update Role</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop