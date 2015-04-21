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
	# Route Models
	$router->model('role', 'App\Role');
	$router->model('user', 'App\User');
	$router->model('conversation', 'Cmgmyr\Messenger\Models\Thread');
	$router->model('news', 'App\News');
	$router->model('tag', 'App\Tag');

	$router->bind('conversation', function($value)
	{
		return Cmgmyr\Messenger\Models\Thread::findOrFail($value);
	});

	# Start routes
	$router->get('/', ['uses' => 'HomeController@index', 'as' => 'home.show']);

	# Authentication routes
	$router->group(['middleware' => 'guest'], function() use ($router)
	{
		$router->get('/login', ['uses' => 'Auth\AuthController@showLogin', 'as' => 'auth.get.login']);
		$router->get('/register', ['uses' => 'Auth\AuthController@showRegister', 'as' => 'auth.get.register']);
	});

//$router->controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController',
//]);
	$router->group(['prefix' => 'auth'], function() use ($router)
	{
		$router->post('/login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'auth.post.login']);
		$router->post('/register', ['uses' => 'Auth\AuthController@postRegister', 'as' => 'auth.post.register']);
		$router->get('/logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'auth.get.logout']);
	});

	# JSON routes
	$router->get('/users/json', function()
	{
		return App\User::all();
	});

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

	# Account management routes
	$router->group(['middleware' => 'auth'], function() use ($router)
	{
		$router->get('/account/settings', ['as' => 'account.get.show.settings', function()
		{
			return view('core.user.settings');
		}]);

		$router->post('/account/settings', ['uses' => 'Auth\AccountController@updateSettings', 'as' => 'account.post.update.settings']);
	});

	$router->get('/account/confirm/{token}', ['uses' => 'Auth\AccountController@activateAccount', 'as' => 'account.get.confirm']);

	# User routes
	$router->get('/profile/{slug}.{id}', ['uses' => 'ProfileController@showProfile', 'as' => 'profile.get.show']);

	# Pages
	$router->get('/play', ['uses' => 'MCPlayController@index', 'as' => 'play.get.show']);

	# Private messaging
	$router->group(['prefix' => 'conversations', 'middleware' => 'auth'], function () use ($router)
	{
		$router->get('/', ['as' => 'conversations', 'uses' => 'Messaging\MessagesController@index']);
		$router->get('create', ['as' => 'conversations.create', 'uses' => 'Messaging\MessagesController@create']);
		$router->post('/', ['as' => 'conversations.store', 'uses' => 'Messaging\MessagesController@store']);
		$router->get('{id}', ['as' => 'conversations.show', 'uses' => 'Messaging\MessagesController@show']);
		$router->put('{id}', ['as' => 'conversations.update', 'uses' => 'Messaging\MessagesController@update']);

		$router->get('/{id}/delete', ['as' => 'conversations.delete', 'uses' => 'Messaging\MessagesManagingController@deleteConversation']);

		$router->group(['prefix' => '{conversation}/users'], function($conversation) use ($router)
		{
			$router->get('json', 'Messaging\MessagesManagingController@toJSON');
			$router->get('{user}/kick', ['as' => 'conversations.users.kick', 'uses' => 'Messaging\MessagesUserManagingController@kickUser']);
		});
	});

	# Ticket management
	$router->group(['prefix' => 'tickets', 'middleware' => 'auth'], function() use ($router)
	{
		$router->get('/', ['as' => 'tickets', 'uses' => 'Tickets\TicketsController@index']);
		$router->post('/', ['as' => 'tickets.store', 'uses' => 'Tickets\TicketsController@store']);

		$router->get('create', ['as' => 'tickets.create', 'uses' => 'Tickets\TicketsController@create']);

		$router->get('{ticket}', ['as' => 'tickets.show', 'uses' => 'Tickets\TicketsController@show']);
		$router->put('{ticket}', ['as' => 'tickets.update', 'uses' => 'Tickets\TicketsController@update']);
	});

	# Search
	$router->group(['prefix' => 'search'], function() use ($router)
	{
		$router->get('/', ['as' => 'search', 'uses' => 'Searching\SearchController@showIndex']);

		$router->post('/', ['as' => 'search.send', 'uses' => 'Searching\SearchController@search']);
	});

	# News management
	$router->group(['prefix' => 'news'], function() use ($router)
	{
		$router->get('/', ['as' => 'news.index', 'uses' => 'News\NewsController@showIndex']);

		$router->get('/create', ['as' => 'news.get.create', 'uses' => 'News\NewsController@showCreate']);
		$router->post('/create', ['as' => 'news.post.create', 'uses' => 'News\NewsController@store']);

		$router->get('/{news}', ['as' => 'news.show', 'uses' => 'News\NewsController@showPost']);

		Entrust::routeNeedsPermission('news/create', 'create_news_posts');
	});
});

$router->get('/install', ['as' => 'install.get', 'uses' => 'InstallController@show']);
$router->post('/install', ['as' => 'install.post', 'uses' => 'InstallController@install']);

$router->get('/install/errors/db', ['as' => 'install.dberror', 'uses' => 'InstallController@showDBError']);
$router->get('/install/errors/pdo', ['as' => 'install.pdoexception', 'uses' => 'InstallController@showPDOException']);
