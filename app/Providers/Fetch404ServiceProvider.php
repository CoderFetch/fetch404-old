<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Fetch404ServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		if ($this->app->environment() == 'local')
		{
			$this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
		}

		if (config('app.debug') == true)
		{
			$this->app->register('Barryvdh\Debugbar\ServiceProvider');
		}

		$this->app->register('Laracasts\Flash\FlashServiceProvider');
		$this->app->register('Illuminate\Html\HtmlServiceProvider');
		$this->app->register('Cmgmyr\Messenger\MessengerServiceProvider');
		$this->app->register('Bllim\Datatables\DatatablesServiceProvider');
	}

}
