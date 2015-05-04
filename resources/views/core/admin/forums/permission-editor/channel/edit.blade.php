@extends('core.admin.layouts.default')
@section('title', 'Editing permissions for ' . $channel->name)
{{-- Content here --}}
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Editing permissions for {{{ $channel->name }}}
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        @include('core.admin.partials.sidebar')
        <div class="col-md-9">
            {!! Form::open(['route' => array('admin.forum.get.permissions.channels.edit', $channel)]) !!}
            <div class="form-group">
                {!! Form::label('allowed_groups', 'Allowed groups', ['class' => 'control-label']) !!}
                <select name="allowed_groups[]" id="groups_list" class="form-control" multiple>
                    @foreach($groups as $i => $g)
                    <option value="{{{ $i }}}"{{{ in_array($i, $accessChannel) ? ' selected=selected' : '' }}}>{{{ $g }}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                {!! Form::label('create_threads', 'Who can create threads?', ['class' => 'control-label']) !!}
                {!! Form::select('create_threads[]', $groups, $createThread, ['id' => 'create_threads', 'class' => 'form-control', 'multiple']) !!}
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