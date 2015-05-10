@extends('core.partials.layouts.master')
@section('title', 'Profile settings')
    {{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Profile Settings
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

            {!! Form::open(['route' => 'account.post.update.settings.profile', 'class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('signature', 'Post signature') !!}
                {!! Form::textarea('signature', Auth::user()->getSetting('post_signature', null), ['class' => 'form-control', 'rows' => 3, 'id' => 'signature']) !!}
            </div>
            <div class="form-group">
                {!! Form::submit('Save changes', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $('#signature').summernote({
        height: 100,
        minHeight: null,
        maxHeight: null,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['style', ['picture', 'link', 'table', 'hr']],
            ['misc', ['codeview']],
        ]
    });
</script>
@endsection