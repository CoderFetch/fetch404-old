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
                <h1>Your search returned {{{ $resultCount }}} {{{ Pluralizer::plural('result', $resultCount) }}}</h1>
                <ul class="list-group">
                @foreach($results as $key => $resultArray)
                    @foreach($resultArray as $result)
                        <li class="list-group-item">
                            @if ($key == 'users')
                                <i class="fa fa-user fa-fw"></i> {!! link_to($result->profileURL, str_limit(strip_tags($result->name), 15)) !!}
                            @elseif ($key == 'posts')
                                <i class="fa fa-comment fa-fw"></i>
                                {!! link_to($result->Route, str_limit(strip_tags($result->content), 50)) !!}
                            @elseif ($key == 'topics')
                                <i class="fa fa-comments-o fa-fw"></i> {!! str_highlight(str_limit($result->title, 50), $searchQuery) !!}
                            @endif
                        </li>
                    @endforeach
                @endforeach
                </ul>
            @endif
        @endif
    </div>
@endsection