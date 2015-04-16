@extends('core.partials.layouts.master')

@section('title', 'News')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active"><a href="{{{ route('news.index') }}}">News</a></li>
    </ol>
    <br />
    <div class="row">
        <div class="col-lg-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (Entrust::can('create_news_posts'))
                        <a class="btn btn-success btn-xs pull-right" href="{{{ route('news.get.create') }}}">Create post</a>
                    @endif
                    <h3 class="panel-title">
                        News
                    </h3>
                </div>
                <div class="panel-body">
                    @if (!$news->isEmpty())
                        @foreach($news as $i => $newsPost)
                            <span>
						        <i class="fa fa-comment fa-fw fa-2x pull-left"></i>
                                <a href="#">{{{ $newsPost->title }}}</a>
                                <span class="text-muted"> - by {{{ $newsPost->user->name }}}</span>
                                <p>
                                    {{{ str_limit(strip_tags($newsPost->content), 120) }}}
                                </p>
					        </span>
                            <hr>
                        @endforeach
                    @else
                        <p>There is no news.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop