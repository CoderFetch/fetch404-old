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
    $router->model('channel', 'App\Channel');
    $router->model('user', 'App\User');
    $router->model('report', 'App\Report');

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

            $router->get('/channel/{channel}/edit', ['as' => 'admin.forum.get.edit.channel', 'uses' => 'AdminForumsController@showEditChannel']);
            $router->post('/channel/{channel}/edit', ['as' => 'admin.forum.post.edit.channel', 'uses' => 'AdminForumsController@editChannel']);

            $router->group(['prefix' => 'permissions'], function() use ($router)
            {
                $router->get('/categories', [
                    'as' => 'admin.forum.get.permissions.categories.index',
                    'uses' => 'CategoryPermissionManagerController@index'
                ]);

                $router->get('/channels', [
                    'as' => 'admin.forum.get.permissions.channels.index',
                    'uses' => 'ChannelPermissionManagerController@index'
                ]);

                $router->get('/channels/{channel}/edit', [
                    'as' => 'admin.forum.get.permissions.channels.edit',
                    'uses' => 'ChannelPermissionManagerController@edit'
                ]);

                $router->post('/channels/{channel}/edit', [
                    'as' => 'admin.forum.get.permissions.channels.edit',
                    'uses' => 'ChannelPermissionManagerController@update'
                ]);

                $router->get('/categories/{category}/edit', [
                    'as' => 'admin.forum.get.permissions.category.edit',
                    'uses' => 'CategoryPermissionManagerController@edit'
                ]);

                $router->post('/categories/{category}/edit', [
                    'as' => 'admin.forum.post.permissions.category.edit',
                    'uses' => 'CategoryPermissionManagerController@update'
                ]);

                $router->post('/channel/{channel}/delete', ['as' => 'admin.forum.post.delete.channel', 'uses' => 'AdminForumsController@deleteChannel']);
                $router->post('/category/{category}/delete', ['as' => 'admin.forum.post.delete.category', 'uses' => 'AdminForumsController@deleteCategory']);
            });
        });

        # Reports
        $router->group(['prefix' => 'reports'], function () use ($router)
        {
            $router->get('/', [
                'as' => 'reports.index',
                'uses' => 'AdminReportsController@index'
            ]);

            $router->get('/{report}', [
                'as' => 'reports.view',
                'uses' => 'AdminReportsController@show'
            ]);
        });

        Entrust::routeNeedsPermission('admin*', 'accessAdminPanel');
    });
});