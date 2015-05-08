<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Cmgmyr\Messenger\Models\Thread;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

		//
		# Route Models
		$router->model('role', 'Fetch404\Core\Models\Role');
		$router->model('user', 'Fetch404\Core\Models\User');
		$router->model('news', 'Fetch404\Core\Models\News');
		$router->model('tag', 'Fetch404\Core\Models\Tag');
		$router->model('profile_post', 'Fetch404\Core\Models\ProfilePost');
		$router->model('post', 'Fetch404\Core\Models\Post');
		$router->model('topic', 'Fetch404\Core\Models\Topic');
		$router->model('channel', 'Fetch404\Core\Models\Channel');
		$router->model('category', 'Fetch404\Core\Models\Category');

		$router->model('conversation', 'Cmgmyr\Messenger\Models\Thread');

		$router->bind('conversation', function($value)
		{
			return Thread::findOrFail($value);
		});
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
			require app_path('Http/Routes/admin.php');
			require app_path('Http/Routes/forum/forum.php');
			require app_path('Http/Routes/account.php');
			require app_path('Http/Routes/conversations.php');
			require app_path('Http/Routes/news.php');
			require app_path('Http/Routes/search.php');
			require app_path('Http/Routes/users.php');
			require app_path('Http/Routes/forum/reports.php');
		});
	}

}
