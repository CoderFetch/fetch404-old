<?php

# Account management routes
$router->group(['middleware' => ['auth', 'csrf']], function() use ($router)
{
    $router->get('/account/settings', ['as' => 'account.get.show.settings', function()
    {
        return view('core.user.settings.index');
    }]);

    $router->post('/account/settings', ['uses' => 'Auth\AccountController@updateSettings', 'as' => 'account.post.update.settings']);

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