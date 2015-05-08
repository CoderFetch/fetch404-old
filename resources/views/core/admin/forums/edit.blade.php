@extends('core.admin.layouts.default')
@section('title', 'Editing category')
    {{-- Content here --}}
@section('content')
    <h1>
        <a class="btn btn-info btn-md pull-right" href="{{{ route('admin.forum.get.permissions.category.edit', $category) }}}">
            <i class="fa fa-user-plus"></i> Manage permissions
        </a>
        Editing category
    </h1>
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

                <!-- Submit Form Input -->
                <div class="form-group">
                    {!! Form::submit('Save changes', ['class' => 'btn btn-primary']) !!}
                </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop