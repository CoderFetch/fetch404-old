@extends('core.admin.layouts.default')
@section('title', 'Editing channel')
{{-- Content here --}}
@section('content')
    <h1>
        <a class="btn btn-info btn-md pull-right" href="{{{ route('admin.forum.get.permissions.channels.edit', $channel) }}}">
            Manage permissions
        </a>
        Editing channel
    </h1>
    <hr>
    {!! Form::open(['route' => array('admin.forum.post.edit.channel', $channel)]) !!}
    <div class="row">
        <div class="col-md-7">
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
                <!-- Title Form Input -->
                <div class="form-group">
                    {!! Form::text('name', $channel->name, ['class' => 'form-control', 'placeholder' => 'Channel title...']) !!}
                </div>

                <!-- Description Form Input -->
                <div class="form-group">
                    {!! Form::text('weight', $channel->weight, ['class' => 'form-control', 'placeholder' => 'Channel weight']) !!}
                     <span class="help-block">
                        * This determines what order channels display in.
                    </span>
                </div>

                <!-- Description Form Input -->
                <div class="form-group">
                    {!! Form::textarea('description', $channel->description, ['class' => 'form-control', 'placeholder' => 'Category description...']) !!}
                    <span class="help-block">
                        * A description is not required.
                    </span>
                </div>

                <div class="form-group">
                    <select name="allowed_groups[]" id="groups_list" class="form-control" multiple>
                        @foreach($groups as $i => $g)
                        <option value="{{{ $i }}}"{{{ in_array($i, $groupIds) ? ' selected=selected' : '' }}}>{{{ $g }}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="create_threads[]" id="create_threads" class="form-control" multiple>
                        @foreach($groups as $i => $g)
                        <option value="{{{ $i }}}"{{{ in_array($i, $createThreadIds) ? ' selected=selected' : '' }}}>{{{ $g }}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Form Input -->
                <div class="form-group">
                    {!! Form::submit('Save changes', ['class' => 'btn btn-primary']) !!}
                </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <script>
        $('#groups_list').select2({
            placeholder: 'Who should be able to access this channel?'
        });

        $('#create_threads').select2({
            placeholder: 'Who should be able to create threads in this channel?'
        });
    </script>
@stop