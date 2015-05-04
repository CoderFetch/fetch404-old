@extends('core.admin.layouts.default')

@section('title', 'Category Permission Manager')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Category Permissions
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        @include('core.admin.partials.sidebar')
        <div class="col-md-9">
            <div class="panel panel-default">
                @if ($categories->count() > 0)
                    <div class="list-group">
                        @foreach($categories as $c)
                            <a class="list-group-item" href="#">{{{ $c->name }}}</a>
                        @endforeach
                    </div>
                @else
                <p>No categories have been defined.</p>
                @endif
            </div>
        </div>
    </div>

@stop