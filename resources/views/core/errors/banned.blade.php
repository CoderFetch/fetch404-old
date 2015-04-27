@extends('core.partials.layouts.master')
@section('title', 'Error')
{{-- Content here --}}
@section('content')
    <h1 class="page-header">Banned</h1>
    <p>We are sorry, but you ({{{ $user->name }}}) have been banned from {{{ $site_title }}}.</p>
@endsection