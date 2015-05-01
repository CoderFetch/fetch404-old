<?php
/**
 * Created by PhpStorm.
 * User: theholyrobber
 * Date: 4/27/15
 * Time: 7:18 PM
 */

$router->group(['middleware' => ['installed', 'auth', 'csrf', 'bancheck', 'update_last_activity'], 'prefix' => 'users', 'namespace' => 'Users'], function() use ($router)
{
   $router->post('{user}/follow', array(
       'as' => 'user.post.follow',
       'uses' => 'FollowsController@follow',
   ));

    $router->post('{user}/unfollow', array(
        'as' => 'user.post.unfollow',
        'uses' => 'FollowsController@unfollow',
    ));

    $router->post('{user}/profile-posts/create', array(
        'as' => 'user.profile-posts.post.create',
        'uses' => 'ProfilePostsController@store'
    ));

    $router->post('{user}/profile-posts/{profile_post}/delete', array(
        'as' => 'user.profile-posts.post.delete',
        'uses' => 'ProfilePostsController@destroy'
    ));
});