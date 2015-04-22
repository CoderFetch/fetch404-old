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

$router->group(['middleware' => ['installed', 'csrf']], function() use ($router) {

    # Forum routes
    $router->group(['prefix' => 'forum'], function () use ($router) {
        $router->group(['prefix' => 'posts'], function () use ($router) {
            $router->post('/like', ['as' => 'forum.posts.like', 'uses' => 'Forums\ForumPostController@likePost']);
            $router->post('/dislike', ['as' => 'forum.posts.dislike', 'uses' => 'Forums\ForumPostController@dislikePost']);
        });
    });
});