@extends('core.partials.layouts.master')

@section('content')
	<h2>Error</h2>
	<hr>
	<h4>We have detected that the <span data-type='tooltip' data-html='true' data-original-title='Cross-Site Request Forgery [prevention]'><b>CSRF</b></span> token for your request does not match your current one. This should not be happening.</h4>
@stop