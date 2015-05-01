<?php

# Private messaging
$router->group(['prefix' => 'conversations', 'middleware' => ['auth', 'csrf', 'bancheck', 'update_last_activity'], 'namespace' => 'Messaging'], function () use ($router)
{
    $router->get('/', ['as' => 'conversations', 'uses' => 'MessagesController@index']);
    $router->get('create', ['as' => 'conversations.create', 'uses' => 'MessagesController@create']);
    $router->post('/', ['as' => 'conversations.store', 'uses' => 'MessagesController@store']);
    $router->get('{id}', ['as' => 'conversations.show', 'uses' => 'MessagesController@show']);
    $router->put('{id}', ['as' => 'conversations.update', 'uses' => 'MessagesController@update']);

    $router->get('/{id}/delete', ['as' => 'conversations.delete', 'uses' => 'MessagesManagingController@deleteConversation']);
    $router->post('/{id}/leave', ['as' => 'conversations.leave', 'uses' => 'MessagesManagingController@leaveConversation']);
    
    $router->group(['prefix' => '{conversation}/users', 'namespace' => 'Messaging'], function($conversation) use ($router)
    {
        $router->get('json', 'MessagesManagingController@toJSON');
        $router->get('{user}/kick', ['as' => 'conversations.users.kick', 'uses' => 'MessagesUserManagingController@kickUser']);
    });
});