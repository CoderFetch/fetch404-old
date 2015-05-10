@extends('core.admin.layouts.default')

@section('title', 'Reports')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3>
                    Reports
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        @include('core.admin.partials.sidebar')
        <div class="col-md-9">
        @if($reports->count() > 0)
            @foreach($reports as $i => $report)
                <span>
                    <a href="{{{ $report->owner->profileURL }}}" data-type="tooltip" data-original-title="Started by {{{ $report->owner->name }}}">
                        <img src="{{{ $report->owner->getAvatarURL(50) }}}" width="50" height="50"/>
                    </a>
                    &nbsp;
                    {!! link_to_route('reports.view', 'Report #' . $report->id, $report->id) !!}
                    <span class="pull-right">
                        <span class="text-muted">
                            Started by {!! link_to_route('profile.get.show', $report->owner->name, [$report->owner->slug, $report->owner->id]) !!}
                            &nbsp;
                        </span>
                        @unless(!$report->isClosed())
                        <i class="fa fa-lock fa-lg"></i>
                        @endunless
                    </span>
                </span>
                @if ($i != sizeof($reports) - 1)
                <hr>
                @endif
            @endforeach
            <hr>
        @else
            <p>There are no open reports.</p>
        @endif
        </div>
    </div>
@stop