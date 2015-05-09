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

$router->group(['middleware' => ['installed', 'csrf', 'bancheck', 'update_last_activity']], function() use ($router) {

    # Forum routes
    $router->group(['prefix' => 'forum'], function() use ($router)
    {
        $router->get('/', ['uses' => 'Forum\ForumPageController@showIndex', 'as' => 'forum.get.index']);

        $router->get('/top/posters', ['uses' => 'Forum\ForumPageController@showTopPosters', 'as' => 'forum.get.show.top.posters']);
        $router->group(['prefix' => 'my', 'middleware' => 'auth'], function() use ($router)
        {
            $router->get('/posts', ['uses' => 'Forum\ForumUserController@showMyPosts', 'as' => 'forum.get.my.posts']);
        });

        $router->get('/{category}', ['uses' => 'Forum\ForumPageController@showForum', 'as' => 'forum.get.show.forum']);

        $router->get('/channel/{channel}', ['uses' => 'Forum\ForumPageController@showChannel', 'as' => 'forum.get.show.channel']);

        $router->get('/channel/{channel}/create-thread', ['uses' => 'Forum\ForumPageController@showCreateThread', 'as' => 'forum.get.channel.create.thread']);
        $router->post('/channel/{channel}/create-thread', ['uses' => 'Forum\ForumController@postCreateThread', 'as' => 'forum.post.channel.create.thread']);

        $router->get('/channel/{channel}/topic/{topic}', ['uses' => 'Forum\ForumPageController@showThread', 'as' => 'forum.get.show.thread']);
        $router->post('/channel/{channel}/topic/{topic}/quick-reply', ['uses' => 'Forum\ForumController@postQuickReplyToThread', 'as' => 'forum.post.quick-reply.thread']);

        $router->get('/channel/{channel}/topic/{topic}/reply', ['uses' => 'Forum\ForumPageController@showReplyToThread', 'as' => 'forum.get.show.thread.reply']);
        $router->post('/channel/{channel}/topic/{topic}/reply', ['uses' => 'Forum\ForumController@postReplyToThread', 'as' => 'forum.post.thread.reply']);

        $router->group(['prefix' => 'topics', 'namespace' => 'Forum'], function() use ($router)
        {
            $router->get('/{topic}/lock', [
                'as' => 'forum.post.topics.lock',
                'uses' => 'ModerationController@lock',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);

            $router->get('/{topic}/unlock', [
                'as' => 'forum.post.topics.unlock',
                'uses' => 'ModerationController@unlock',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);

            $router->get('/{topic}/pin', [
                'as' => 'forum.post.topics.pin',
                'uses' => 'ModerationController@pin',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);
        });

        $router->group(['prefix' => 'posts', 'namespace' => 'Forum'], function() use ($router)
        {
            $router->post('/{post}/like', [
                'as' => 'forum.post.posts.like',
                'uses' => 'LikesController@store',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);

            $router->post('/{post}/dislike', [
                'as' => 'forum.post.posts.dislike',
                'uses' => 'LikesController@destroy',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);

            $router->get('/{post}/report', [
                'as' => 'forum.get.posts.report',
                'uses' => 'PostReportsController@show',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);

            $router->post('/{post}/report', [
                'as' => 'forum.post.posts.report',
                'uses' => 'PostReportsController@store',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);

            $router->post('/{post}/edit', [
                'as' => 'forum.get.posts.edit',
                'uses' => 'PostsController@edit',
                'middleware' => ['auth', 'confirmed', 'csrf']
            ]);
        });
    });

});