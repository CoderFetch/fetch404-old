@extends('core.admin.layouts.default')
@section('title', 'Create category')
{{-- Content here --}}
@section('content')
    <h1>Creating new category</h1>
    <hr>
    {!! Form::open(['route' => 'admin.forum.post.create.category']) !!}
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
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Category title...']) !!}
                </div>

                <!-- Title Form Input -->
                <div class="form-group">
                    {!! Form::text('weight', null, ['class' => 'form-control', 'placeholder' => 'Category weight']) !!}
                     <span class="help-block">
                        * This determines what order categories display in.
                    </span>
                </div>

                <!-- Description Form Input -->
                <div class="form-group">
                    {!! Form::textarea('description', '', ['class' => 'form-control', 'placeholder' => 'Category description...']) !!}
                    <span class="help-block">
                        * A description is not required.
                    </span>
                </div>

                <!-- Submit Form Input -->
                <div class="form-group">
                    {!! Form::submit('Create category', ['class' => 'btn btn-primary']) !!}
                </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop