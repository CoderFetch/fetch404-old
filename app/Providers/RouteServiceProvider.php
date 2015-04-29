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
		$router->model('role', 'App\Role');
		$router->model('user', 'App\User');
		$router->model('conversation', 'Cmgmyr\Messenger\Models\Thread');
		$router->model('news', 'App\News');
		$router->model('tag', 'App\Tag');
		$router->model('profile_post', 'App\ProfilePost');

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
			require app_path('Http/Routes/tickets.php');
			require app_path('Http/Routes/users.php');
		});
	}

}
