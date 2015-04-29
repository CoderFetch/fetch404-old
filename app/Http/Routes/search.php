<?php

# Search
$router->group(['prefix' => 'search'], function() use ($router)
{
    $router->get('/', ['as' => 'search', 'uses' => 'Searching\SearchController@showIndex']);

    $router->post('/', ['as' => 'search.send', 'uses' => 'Searching\SearchController@search']);
});