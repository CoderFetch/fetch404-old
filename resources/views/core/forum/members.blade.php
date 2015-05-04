@extends('core.partials.layouts.master')
@section('title', 'Members')
{{-- Content here --}}
@section('content')
    <h1 class="page-header">Members</h1>
    @unless($users->isEmpty())
    <div class="row users">
    @foreach($users as $u)
        <div class="col-md-3">
            <a href="{{{ $u->profileURL }}}">
                <img class="media-object" src="{{ $u->getAvatarURL(40) }}" alt="{{ $u->name }}">
            </a>
            <h4>
                {!! link_to($u->profileURL, $u->name) !!}
            </h4>
        </div>
    @endforeach
    </div>
    @endunless

    @unless(!$users->isEmpty())
    <p>
        There are no users.
    </p>
    @endunless
@endsection