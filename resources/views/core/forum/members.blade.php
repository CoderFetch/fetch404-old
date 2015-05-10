@extends('core.partials.layouts.master')
@section('title', 'Members')
{{-- Content here --}}
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{{ route('home.show') }}}">Home</a></li>
        <li class="active">Members</li>
    </ol>
    <h1 class="page-header">Members</h1>
    @unless($users->isEmpty())
    <div class="row users">
    @foreach($users as $u)
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="{{{ $u->profileURL }}}">
                        <img class="media-object" src="{{ $u->getAvatarURL(40) }}" alt="{{ $u->name }}">
                    </a>
                    <h4>
                        {!! link_to($u->profileURL, $u->name) !!}
                    </h4>
                    <i class="fa fa-circle text-{{{ $u->is_online == 1 ? 'success' : 'danger' }}}"></i>

                    <span class="text-{{{ $u->is_online == 1 ? 'success' : 'danger' }}}">{{{ $u->is_online == 1 ? 'Online' : 'Offline' }}}</span>
                    <br>
                    <span class="text-muted">
                        Joined on <strong>{{{ $u->getJoinedOn() }}}</strong>
                    </span>
                    <br>
                    <span class="text-muted">
                        Last active <strong>{{{ $u->getLastActivity() }}}</strong>
                    </span>
                </div>
            </div>
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