<?php

# Account management routes
$router->group(['middleware' => ['auth', 'csrf', 'bancheck', 'update_last_activity']], function() use ($router)
{
    $router->get('/account/settings', ['as' => 'account.get.show.settings', function()
    {
        return view('core.user.settings.index');
    }]);

    $router->get('/account/privacy', ['as' => 'account.get.show.settings.privacy', function()
    {
        return view('core.user.settings.privacy');
    }]);

    $router->post('/account/settings', ['uses' => 'Auth\AccountController@updateSettings', 'as' => 'account.post.update.settings']);
    $router->post('/account/privacy', ['uses' => 'Auth\AccountController@updatePrivacy', 'as' => 'account.post.update.settings.privacy']);

    $router->get('/settings/notifications/view', function() {
        return response()->json(array(
            'notifications' => Auth::user()->notifications()->take(5),
            'count' => Auth::user()->notifications()->count(),
            'html' => Auth::user()->genNotificationHTML()
        ));
    });

    $router->post('/settings/notifications/markAsRead', function() {
        $user = Auth::user();

        $user->notifications()->where('is_read', '=', 0)->update(array(
            'is_read' => 1
        ));

        return response()->json(array(
            'status' => 'success'
        ));
    });
});

$router->get('/account/confirm/{token}', ['uses' => 'Auth\AccountController@activateAccount', 'as' => 'account.get.confirm']);