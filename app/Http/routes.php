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

$router->group(['middleware' => ['installed', 'write_config', 'bancheck', 'update_last_activity'], 'prefix' => config('app.url_prefix', '')], function() use ($router)
{

	# Start routes
	$router->get('/', ['uses' => 'HomeController@index', 'as' => 'home.show']);

	# Authentication routes
	$router->group(['middleware' => 'guest'], function() use ($router)
	{
		$router->get('/login', ['uses' => 'Auth\AuthController@showLogin', 'as' => 'auth.get.login']);
		$router->get('/register', ['uses' => 'Auth\AuthController@showRegister', 'as' => 'auth.get.register']);
	});

	$router->group(['prefix' => 'auth'], function() use ($router)
	{
		$router->post('/login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'auth.post.login']);
		$router->post('/register', ['uses' => 'Auth\AuthController@postRegister', 'as' => 'auth.post.register']);
		$router->get('/logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'auth.get.logout']);
	});

	# Member list
	$router->get('/members', [
		'uses' => 'UsersController@showMembers',
		'as' => 'members.get.index'
	]);

	# User routes
	$router->get('@{slug}.{id}', ['uses' => 'ProfileController@showProfile', 'as' => 'profile.get.show']);
});

$router->get('/install', ['as' => 'install.get', 'uses' => 'InstallController@show']);
$router->post('/install', ['as' => 'install.post', 'uses' => 'InstallController@install']);

$router->get('/install/errors/db', ['as' => 'install.dberror', 'uses' => 'InstallController@showDBError']);
$router->get('/install/errors/pdo', ['as' => 'install.pdoexception', 'uses' => 'InstallController@showPDOException']);
