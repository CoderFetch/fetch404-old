@extends('core.partials.layouts.master')
@section('title', 'Error')
{{-- Content here --}}
@section('content')
    <h1 class="page-header">Banned</h1>
    <p>We are sorry, but you ({{{ $user->name }}}) have been banned from {{{ $site_title }}}.</p>
    @if ($banned_until != null)
    <p>Your ban will expire in {{{ $banned_until->diffForHumans(Carbon::now(), true) }}}.</p>
    @endif
@endsection