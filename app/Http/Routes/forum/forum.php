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
    $router->group(['prefix' => 'forum'], function() use ($router)
    {
        $router->get('/', ['uses' => 'Forum\ForumPageController@showIndex', 'as' => 'forum.get.index']);

        $router->get('/top/posters', ['uses' => 'Forum\ForumPageController@showTopPosters', 'as' => 'forum.get.show.top.posters']);
        $router->group(['prefix' => 'my', 'middleware' => 'auth'], function() use ($router)
        {
            $router->get('/posts', ['uses' => 'Forum\ForumUserController@showMyPosts', 'as' => 'forum.get.my.posts']);
        });

        $router->get('/{slug}', ['uses' => 'Forum\ForumPageController@showForum', 'as' => 'forum.get.show.forum']);
        $router->get('/{id}', ['uses' => 'Forum\ForumPageController@showForumById', 'as' => 'forum.get.showbyid.forum']);

        $router->get('/channel/{slug}', ['uses' => 'Forum\ForumPageController@showChannel', 'as' => 'forum.get.show.channel']);

        $router->get('/channel/{slug}/create-thread', ['uses' => 'Forum\ForumPageController@showCreateThread', 'as' => 'forum.get.channel.create.thread']);
        $router->post('/channel/{slug}/create-thread', ['uses' => 'Forum\ForumController@postCreateThread', 'as' => 'forum.post.channel.create.thread']);

        $router->get('/topic/{slug}.{id}', ['uses' => 'Forum\ForumPageController@showThread', 'as' => 'forum.get.show.thread']);
        $router->post('/topic/{slug}.{id}/quick-reply', ['uses' => 'Forum\ForumController@postQuickReplyToThread', 'as' => 'forum.post.quick-reply.thread']);

        $router->get('/topic/{slug}.{id}/reply', ['uses' => 'Forum\ForumPageController@showReplyToThread', 'as' => 'forum.get.show.thread.reply']);
        $router->post('/topic/{slug}.{id}/reply', ['uses' => 'Forum\ForumController@postReplyToThread', 'as' => 'forum.post.thread.reply']);

    });
});