@extends('core.admin.layouts.default')
@section('title', 'General settings')
{{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    General Settings
                </h3>
            </div>
        </div>
    </div>

    <div class="row">
        @include('core.admin.partials.sidebar')
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
            {!! Form::open(['route' => 'admin.settings.post.general.save']) !!}
            <div class="form-group">
                <label for="InputSiteName">Site Name</label>
                <input type="text" name="sitename" class="form-control" id="InputSiteName" placeholder="Site Name" value="{{{ $site_title }}}">
            </div>
            <br />
            <label>Recaptcha Site Key</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <input name="enable_recaptcha" value="{{{ $recaptcha_enabled == 'true' ? '1' : '0' }}}"{{{ $recaptcha_enabled == 'true' ? 'checked=checked ' : '' }}}type="checkbox">
                </span>
                <input type="text" name="recaptcha" class="form-control" placeholder="Recaptcha Key"{{{ $recaptcha_key != '' ? 'value=' . $recaptcha_key : '' }}}>
            </div>
            <br />
            <div class="form-group">
                <label for="InputBootstrap">Bootstrap Style</label>
                <select class="form-control" name="bootstrap_style">
                    <option value="1"{{{ $theme_id == "1" ? ' selected=selected' : '' }}}>Default Bootstrap</option>
                    <option value="2"{{{ $theme_id == "2" ? ' selected=selected' : '' }}}>Cerulean</option>
                    <option value="3"{{{ $theme_id == "3" ? ' selected=selected' : '' }}}>Cosmo</option>
                    <option value="4"{{{ $theme_id == "4" ? ' selected=selected' : '' }}}>Cyborg</option>
                    <option value="5"{{{ $theme_id == "5" ? ' selected=selected' : '' }}}>Darkly</option>
                    <option value="6"{{{ $theme_id == "6" ? ' selected=selected' : '' }}}>Flatly</option>
                    <option value="7"{{{ $theme_id == "7" ? ' selected=selected' : '' }}}>Journal</option>
                    <option value="8"{{{ $theme_id == "8" ? ' selected=selected' : '' }}}>Lumen</option>
                    <option value="9"{{{ $theme_id == "9" ? ' selected=selected' : '' }}}>Paper</option>
                    <option value="10"{{{ $theme_id == "10" ? ' selected=selected' : '' }}}>Readable</option>
                    <option value="11"{{{ $theme_id == "11" ? ' selected=selected' : '' }}}>Sandstone</option>
                    <option value="12"{{{ $theme_id == "12" ? ' selected=selected' : '' }}}>Simplex</option>
                    <option value="13"{{{ $theme_id == "13" ? ' selected=selected' : '' }}}>Slate</option>
                    <option value="14"{{{ $theme_id == "14" ? ' selected=selected' : '' }}}>Spacelab</option>
                    <option value="15"{{{ $theme_id == "15" ? ' selected=selected' : '' }}}>Superhero</option>
                    <option value="16"{{{ $theme_id == "16" ? ' selected=selected' : '' }}}>United</option>
                    <option value="17"{{{ $theme_id == "17" ? ' selected=selected' : '' }}}>Yeti</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <label for="InputNavbar">Navbar Theme</label>
                <select class="form-control" name="navbar_theme">
                    <option value="0"{{{ $navbar_style == 0 ? ' selected=selected' : '' }}}>Default</option>
                    <option value="1"{{{ $navbar_style == 1 ? ' selected=selected' : '' }}}>Inverse</option>
                </select>
            </div>
            {!! Form::submit('Save changes', ['class' => 'btn btn-default']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection