@extends('core.admin.layouts.default')
@section('title', 'Editing permissions for ' . $category->name)
    {{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Editing permissions for {{{ $category->name }}}
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
            {!! Form::open(['route' => array('admin.forum.get.permissions.category.edit', $category)]) !!}
            <div class="form-group">
                {!! Form::label('allowed_groups', 'Allowed groups', ['class' => 'control-label']) !!}
                <select name="allowed_groups[]" id="groups_list" class="form-control" multiple>
                    @foreach($groups as $i => $g)
                        <option value="{{{ $i }}}"{{{ in_array($i, $accessCategory) ? ' selected=selected' : '' }}}>{{{ $g }}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                {!! Form::label('create_threads', 'Who can create threads?', ['class' => 'control-label']) !!}
                <select name="create_threads[]" id="create_threads" class="form-control" multiple>
                    @foreach($groups as $i => $g)
                        @if ($i != 2)
                        <option value="{{{ $i }}}"{{{ in_array($i, $createThread) ? ' selected=selected' : '' }}}>{{{ $g }}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                {!! Form::label('reply_to_threads', 'Who can post?', ['class' => 'control-label']) !!}
                <select name="reply_to_threads[]" id="reply_to_threads" class="form-control" multiple>
                    @foreach($groups as $i => $g)
                        @if ($i != 2)
                        <option value="{{{ $i }}}"{{{ in_array($i, $reply) ? ' selected=selected' : '' }}}>{{{ $g }}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                {!! Form::submit('Save changes', ['class' => 'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('footer')
    <script>
        //        $('#groups_list').select2({
        //            placeholder: 'Who should be able to access this channel?'
        //        });
        //
        //        $('#create_threads').select2({
        //            placeholder: 'Who should be able to create threads?'
        //        });
    </script>
@stop