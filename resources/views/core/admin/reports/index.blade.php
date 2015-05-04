@extends('core.admin.layouts.default')

@section('title', 'Reports')
@section('content')
    <h1>
        Reports
    </h1>
    @if($reports->count() > 0)
        @foreach($reports as $i => $report)
            <span>
			    <img src="{{{ $report->owner->getAvatarURL(50) }}}" width="50" height="50" data-type="tooltip" data-original-title="Started by {{{ $report->owner->name }}}"/>
			    &nbsp;
			    {!! link_to_route('reports.view', 'Report #' . $report->id, $report->id) !!}

                @unless(!$report->isClosed())
                <span class="pull-right">
                    <i class="fa fa-lock fa-lg"></i>
                </span>
                @endunless
		    </span>
            @if ($i != sizeof($reports) - 1)
            <hr>
            @endif
        @endforeach
        <hr>
    @else
        <p>There are no open reports.</p>
    @endif
@stop