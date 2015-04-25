@extends('core.admin.layouts.default')
@section('title', 'Editing category')
    {{-- Content here --}}
@section('content')
    <h1>Editing category</h1>
    <hr>
    {!! Form::open(['route' => array('admin.forum.post.edit.category', $category)]) !!}
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
                    {!! Form::text('name', $category->name, ['class' => 'form-control', 'placeholder' => 'Category title...']) !!}
                </div>

                <!-- Description Form Input -->
                <div class="form-group">
                    {!! Form::text('weight', $category->weight, ['class' => 'form-control', 'placeholder' => 'Category weight']) !!}
                     <span class="help-block">
                        * This determines what order categories display in.
                    </span>
                </div>

                <!-- Description Form Input -->
                <div class="form-group">
                    {!! Form::textarea('description', $category->description, ['class' => 'form-control', 'placeholder' => 'Category description...']) !!}
                    <span class="help-block">
                        * A description is not required.
                    </span>
                </div>

                <div class="form-group">
                    {!! Form::label('allowed_groups', 'Allowed groups', ['class' => 'control-label']) !!}
                    <select name="allowed_groups[]" id="groups_list" class="form-control" multiple="multiple">
                        @foreach(Role::get() as $role)
                        <option value="{{{ $role->id }}}"{{{ $permissions->contains($role->id) ? 'selected=selected' : '' }}}>
                            {{{ $role->name }}}
                        </option>
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
            placeholder: 'Who should be able to access this forum?'
        });
    </script>
@stop