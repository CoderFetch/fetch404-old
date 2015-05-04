@extends('core.partials.layouts.master')
@section('title', 'Search')
{{-- Content here --}}
@section('content')
    <h1>Search</h1>
    {!! Form::open(['route' => 'search.send']) !!}
    <div class="input-group">
        <input type="text" class="form-control" name="query" placeholder="Search for something..." value="{{{ isset($searchQuery) ? $searchQuery : '' }}}"/>
        <span class="input-group-btn">
            {!! Form::submit('Search', ['class' => 'btn btn-default']) !!}
        </span>
    </div>
    {!! Form::close() !!}
    <hr>
    <div class="well">
        @if (!isset($results))
            <p>To start searching, just type something into the text box and press enter!</p>
        @else
            @if (empty($results))
                <p>No results were found.</p>
            @else
                <h1>Your search returned {{{ $results->count() }}} {{{ Pluralizer::plural('result', $results->count()) }}}</h1>
                <ul class="list-group">
                @foreach($results as $item)
                  <li class="list-group-item">
                      @if ($item instanceof App\User)
                        <i class="fa fa-user fa-fw"></i>
                        {!! link_to($item->profileURL, str_limit(strip_tags($item->name), 15)) !!}
                        <span class="pull-right">
                            <span class="text-muted">
                                Joined {{{ $item->created_at->diffForHumans() }}}
                            </span>
                        </span>
                      @elseif ($item instanceof App\Post)
                        <i class="fa fa-comment fa-fw"></i>
                        {!! link_to($item->Route, str_limit(strip_tags($item->content), 50)) !!}
                        <span class="pull-right">
                            <span class="text-muted">
                                {{{ $item->created_at->diffForHumans() }}}
                            </span>
                        </span>
                      @elseif ($item instanceof App\Topic)
                        <i class="fa fa-comments-o fa-fw"></i>
                        {!! link_to($item->Route, str_limit(strip_tags($item->title), 50)) !!}
                        <span class="pull-right">
                            <span class="text-muted">
                                {{{ $item->created_at->diffForHumans() }}}
                            </span>
                        </span>
                      @elseif ($item instanceof App\Report)
                        <i class="fa fa-exclamation-circle fa-fw"></i>
                        {!! link_to_route('reports.view', "Report by " . $item->owner->name, [$item]) !!}
                        <span class="pull-right">
                            @unless(!$item->isClosed())
                            <i class="fa fa-lock fa-fw" data-type="tooltip" data-original-title="Closed"></i>
                            @endunless
                            <span class="text-muted">
                                Reported {{{ $item->created_at->diffForHumans() }}}
                            </span>
                        </span>
                      @elseif ($item instanceof Cmgmyr\Messenger\Models\Thread)
                        <i class="fa fa-envelope fa-fw"></i>
                        {!! link_to_route('conversations.show', str_limit($item->subject, 25), [$item->id]) !!}
                        <span class="pull-right">
                            <span class="text-muted">
                                {{{ $item->created_at->diffForHumans() }}}
                            </span>
                        </span>
                      @endif
                  </li>
                @endforeach
                </ul>
            @endif
        @endif
    </div>
@endsection