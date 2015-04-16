@extends('core.admin.layouts.default')

{{-- Content --}}
@section('content')

    <!-- Tabs -->
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
	</ul>
    <!-- ./ tabs -->
    {{-- Delete Post Form --}}
    <form id="deleteForm" class="form-horizontal" method="post" action="@if (isset($role)){{ URL::to('admin/roles/' . $role->id . '/delete') }}@endif" autocomplete="off">
        
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <input type="hidden" name="id" value="{{ $role->id }}" />
        <!-- <input type="hidden" name="_method" value="DELETE" /> -->
        <!-- ./ csrf token -->
        <br>
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@stop