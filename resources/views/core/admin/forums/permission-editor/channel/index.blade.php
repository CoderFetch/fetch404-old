@extends('core.admin.layouts.default')

@section('title', 'Channel Permission Manager')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Channel Permissions
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        @include('core.admin.partials.sidebar')
        <div class="col-md-9">
            <div class="panel panel-default">
                @if ($channels->count() > 0)
                <ul class="list-group">
                    @foreach($channels as $ch)
                    <li class="list-group-item">
                        <a href="#">
                            {{{ $ch->name }}}
                        </a>
                        <div class="pull-right">
                            <a href="#" class="btn btn-success btn-xs">Edit</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else

                @endif
            </div>
        </div>
    </div>

@stop