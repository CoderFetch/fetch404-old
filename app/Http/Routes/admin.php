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

$router->group(['middleware' => ['installed', 'csrf']], function() use ($router)
{
    $router->model('category', 'App\Category');
    $router->model('user', 'App\User');

    # Administration routes
    $router->group(['prefix' => 'admin', 'namespace' => 'Admin'], function () use ($router)
    {
        $router->get('/', ['uses' => 'AdminPageController@showIndex', 'as' => 'admin.get.index']);
        $router->get('/general', ['uses' => 'AdminPageController@showGeneral', 'as' => 'admin.get.general']);

        # User Management
        $router->group(['prefix' => 'users'], function () use ($router)
        {
            $router->get('/', ['uses' => 'AdminUsersController@getIndex', 'as' => 'admin.users.get.index']);
            $router->post('/{user}/ban', ['uses' => 'AdminUsersController@banUser', 'as' => 'admin.users.post.ban']);
            $router->post('/{user}/unban', ['uses' => 'AdminUsersController@unbanUser', 'as' => 'admin.users.post.unban']);
        });

        # Role Management
        $router->group(['prefix' => 'roles'], function () use ($router)
        {
            $router->get('/{role}/edit', 'AdminRolesController@getEdit');
            $router->post('/{role}/edit', 'AdminRolesController@postEdit');
            $router->get('/{role}/delete', 'AdminRolesController@getDelete');
            $router->post('/{role}/delete', 'AdminRolesController@postDelete');
            $router->controller('/', 'AdminRolesController');
        });

        # Settings Management
        $router->group(['prefix' => 'settings'], function () use ($router) {
            $router->post('/general/save', ['as' => 'admin.settings.post.general.save', 'uses' => 'AdminSettingsController@saveGeneral']);
        });

        # Forum Management
        $router->group(['prefix' => 'forum'], function () use ($router) {
            $router->get('/', ['as' => 'admin.forum.get.index', 'uses' => 'AdminForumsController@index']);
            $router->get('/create', ['as' => 'admin.forum.get.create.category', 'uses' => 'AdminForumsController@showCreateCategory']);
            $router->post('/create', ['as' => 'admin.forum.post.create.category', 'uses' => 'AdminForumsController@storeCategory']);

            $router->get('/{category}/edit', ['as' => 'admin.forum.get.edit.category', 'uses' => 'AdminForumsController@showEditCategory']);
            $router->post('/{category}/edit', ['as' => 'admin.forum.post.edit.category', 'uses' => 'AdminForumsController@editCategory']);
        });

        Entrust::routeNeedsPermission('admin*', 'accessAdminPanel');
    });
});