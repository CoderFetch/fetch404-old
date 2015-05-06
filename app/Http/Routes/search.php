<?php

# Search
$router->group(['prefix' => 'search', 'middleware' => ['bancheck', 'update_last_activity']], function() use ($router)
{
    $router->get('/', ['as' => 'search.send', 'uses' => 'Searching\SearchController@search']);
});