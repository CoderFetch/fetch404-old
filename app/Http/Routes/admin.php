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

    # Administration routes
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->get('/', ['uses' => 'Admin\AdminPageController@showIndex', 'as' => 'admin.get.index']);
        $router->get('/general', ['uses' => 'Admin\AdminPageController@showGeneral', 'as' => 'admin.get.general']);

        # User Management
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/{user}/edit', 'Admin\AdminUsersController@getEdit');
            $router->post('/{user}/edit', 'Admin\AdminUsersController@postEdit');
            $router->controller('/', 'Admin\AdminUsersController');
        });

        # Role Management
        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('/{role}/edit', 'Admin\AdminRolesController@getEdit');
            $router->post('/{role}/edit', 'Admin\AdminRolesController@postEdit');
            $router->get('/{role}/delete', 'Admin\AdminRolesController@getDelete');
            $router->post('/{role}/delete', 'Admin\AdminRolesController@postDelete');
            $router->controller('/', 'Admin\AdminRolesController');
        });

        # Settings Management
        $router->group(['prefix' => 'settings'], function () use ($router) {
            $router->post('/general/save', ['as' => 'admin.settings.post.general.save', 'uses' => 'Admin\AdminSettingsController@saveGeneral']);
        });

        # Forum Management
        $router->group(['prefix' => 'forum'], function () use ($router) {
            $router->get('/', ['as' => 'admin.forum.get.index', 'uses' => 'Admin\AdminForumsController@index']);
            $router->get('/create', ['as' => 'admin.forum.get.create.category', 'uses' => 'Admin\AdminForumsController@showCreateCategory']);
            $router->post('/create', ['as' => 'admin.forum.post.create.category', 'uses' => 'Admin\AdminForumsController@storeCategory']);
        });

        Entrust::routeNeedsPermission('admin*', 'accessAdminPanel');
    });
});