@extends('core.partials.layouts.master')
@section('title', 'Privacy settings')
{{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Privacy Settings
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        @include('core.user.partials.settings.sidebar')
        <div class="col-md-9">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::open(['route' => 'account.post.update.settings.privacy', 'class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('show_when_im_online', 'Show when I\'m online?') !!}
                <input type="checkbox" name="show_when_im_online" data-bswitch{{{ Auth::user()->getSetting("show_when_im_online", 0) == '1' ? ' checked' : '' }}} />
            </div>
            <div class="form-group">
                {!! Form::label('allow_bots_to_index_me', 'Allow my profile to be indexed?') !!}
                <input type="checkbox" name="allow_bots_to_index_me" data-bswitch{{{ Auth::user()->getSetting("allow_bots_to_index_me", 0) == '1' ? ' checked' : '' }}} />
            </div>
            <div class="form-group">
                {!! Form::submit('Save changes', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection