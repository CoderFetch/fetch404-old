@extends('core.partials.layouts.master')

@section('title', 'Create news post')
@section('content')
    <h1>Creating news post</h1>
    <hr>
    {!! Form::open(['route' => array('news.post.create')]) !!}
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

                <!-- Hidden user ID input -->
                {!! Form::hidden('user_id', Auth::id()) !!}
                <!-- Subject Form Input -->
                <div class="form-group">
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Post title']) !!}
                </div>

                <!-- Tags Form Input -->
                <div class="form-group">
                    {!! Form::select('tags[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
                </div>

                <!-- Message Form Input -->
                <div class="form-group">
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'data-type' => 'summernote']) !!}
                </div>

                <!-- Submit Form Input -->
                <div class="form-group">
                    {!! Form::submit('Create post', ['class' => 'btn btn-primary']) !!}
                </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
@section('scripts')
    <script>
        $('#tag_list').select2({
            placeholder: 'What tags should be applied to this post?'
        });
    </script>
@stop