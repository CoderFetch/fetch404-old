<?php namespace App\Providers;

use App\Role;
use App\Ticket;
use App\User;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

use App\Setting;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//

		// Set up the view composers
		view()->composer('core.partials.layouts.master', function($view) {
			$site_title = Setting::where('name', '=', 'sitename')->first();
			$site_theme = Setting::where('name', '=', 'bootswatch_theme')->first();
			$navbar_style = Setting::where('name', '=', 'navbar_style')->first();

			$view->with('site_title', ($site_title != null ? e($site_title->value) : 'Fetch404'));
			$view->with('theme_id', ($site_theme != null ? e($site_theme->value) : '1'));
			$view->with('navbar_style', ($navbar_style != null ? e($navbar_style->value) : '0'));
		});

		view()->composer('core.admin.index', function($view) {
			$date = new Carbon;
			$date->subWeek();

			$users = User::where('created_at', '>', $date->toDateTimeString())->get();

			$view->with('latest_users', $users);

			$tickets = Ticket::where('created_at', '>', $date->toDateTimeString())->get();

			$view->with('latest_tickets', $tickets);

			$view->with('roles', Role::all());
		});
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
