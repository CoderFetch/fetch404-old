<?php

# News management
$router->group(['prefix' => 'news'], function() use ($router)
{
    $router->get('/', ['as' => 'news.index', 'uses' => 'News\NewsController@showIndex']);

    $router->get('/create', ['as' => 'news.get.create', 'uses' => 'News\NewsController@showCreate']);
    $router->post('/create', ['as' => 'news.post.create', 'uses' => 'News\NewsController@store']);

    $router->get('/{news}', ['as' => 'news.show', 'uses' => 'News\NewsController@showPost']);

    Entrust::routeNeedsPermission('news/create', 'create_news_posts');
});