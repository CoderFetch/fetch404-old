<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$router->group(['middleware' => ['installed', 'csrf', 'bancheck']], function() use ($router) {

    $router->model('report', 'App\Report');

    $router->group(['prefix' => 'reports', 'namespace' => 'Forum'], function() use ($router)
    {
        $router->post('/{report}/open', [
            'as' => 'forum.post.report.open',
            'uses' => 'ReportsController@open',
            'middleware' => ['auth', 'confirmed', 'csrf']
        ]);

        $router->post('/{report}/close', [
            'as' => 'forum.post.report.close',
            'uses' => 'ReportsController@close',
            'middleware' => ['auth', 'confirmed', 'csrf']
        ]);

        $router->post('/{report}/comment', [
            'as' => 'forum.post.report.comment',
            'uses' => 'ReportsController@comment',
            'middleware' => ['auth', 'confirmed', 'csrf']
        ]);
    });

});