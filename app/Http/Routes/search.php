<?php

# Search
$router->group(['prefix' => 'search', 'middleware' => ['bancheck', 'update_last_activity']], function() use ($router)
{
    $router->get('/', ['as' => 'search.index', 'uses' => 'Searching\SearchController@showIndex']);
    $router->post('/', ['as' => 'search.send', 'uses' => 'Searching\SearchController@search']);
});