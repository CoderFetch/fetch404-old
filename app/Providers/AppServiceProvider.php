<?php namespace App\Providers;

use Cmgmyr\Messenger\Models\Thread;
use Fetch404\Core\Models\ProfilePost;
use Fetch404\Core\Models\Report;
use Fetch404\Core\Models\Role;
use Fetch404\Core\Models\Setting;
use Fetch404\Core\Models\Topic;
use Fetch404\Core\Models\User;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

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
			$recaptcha_enabled = Setting::where('name', '=', 'recaptcha')->first();

			$view->with('recaptcha_enabled', ($recaptcha_enabled != null ? $recaptcha_enabled->value : '0'));
			$view->with('site_title', ($site_title != null ? e($site_title->value) : 'Fetch404'));
			$view->with('theme_id', ($site_theme != null ? e($site_theme->value) : '1'));
			$view->with('navbar_style', ($navbar_style != null ? e($navbar_style->value) : '0'));

			if (Auth::check())
			{
				$user = Auth::user();
				$view->with('user', $user);

				$notifications = $user->notifications;

				$notifications = $notifications->sortByDesc(function($item)
				{
					return $item->created_at;
				});

				$notifications = $notifications->filter(function($item)
				{
					return (
						time() - strtotime($item->created_at) < (60 * 60 * (24 * 3))
					);
				});

				$view->with('notifications', $notifications->take(5));

				$messages = Thread::forUserWithNewMessages($user->id)->get();

				$messages = $messages->sortByDesc(function($item) {
					return $item->created_at;
				});

				$messages = $messages->filter(function($item) use ($user)
				{
					return (
						time() - strtotime($item->created_at) < (60 * 60 * (24 * 3))
					) && $item->isUnread($user->id);
				});

				$view->with('messages', $messages);

				if ($user->can('viewReports'))
				{
					$reports = Report::all();

					$reports = $reports->sortByDesc(function($item)
					{
						return $item->updated_at;
					});

					$reports = $reports->filter(function($item)
					{
						return !$item->isClosed();
					});

					$view->with('reports', $reports);
				}
			}
		});

		view()->composer('core.admin.layouts.default', function($view) {
			$site_title = Setting::where('name', '=', 'sitename')->first();
			$site_theme = Setting::where('name', '=', 'bootswatch_theme')->first();
			$navbar_style = Setting::where('name', '=', 'navbar_style')->first();

			$view->with('site_title', ($site_title != null ? e($site_title->value) : 'Fetch404'));
			$view->with('theme_id', ($site_theme != null ? e($site_theme->value) : '1'));
			$view->with('navbar_style', ($navbar_style != null ? e($navbar_style->value) : '0'));

			$user = Auth::user();

			$view->with('user', $user);

			if ($user->can('viewReports'))
			{
				$reports = Report::all();

				$reports = $reports->sortByDesc(function($item)
				{
					return $item->updated_at;
				});

				$reports = $reports->filter(function($item) use ($user)
				{
					return !$item->isClosed();
				});

				$view->with('reports', $reports);
			}
		});

		view()->composer('core.admin.general', function($view) {
			$site_title = Setting::where('name', '=', 'sitename')->first();
			$site_theme = Setting::where('name', '=', 'bootswatch_theme')->first();
			$navbar_style = Setting::where('name', '=', 'navbar_style')->first();
			$recaptcha_enabled = Setting::where('name', '=', 'recaptcha')->first();
			$recaptcha_key = Setting::where('name', '=', 'recaptcha_key')->first();

			$view->with('site_title', ($site_title != null ? e($site_title->value) : 'Fetch404'));
			$view->with('theme_id', ($site_theme != null ? e($site_theme->value) : '1'));
			$view->with('navbar_style', ($navbar_style != null ? e($navbar_style->value) : '0'));
			$view->with('recaptcha_enabled', ($recaptcha_enabled != null ? ($recaptcha_enabled->value == 'true' ? 'true' : 'false') : 'false'));
			$view->with('recaptcha_key', ($recaptcha_key != null ? e($recaptcha_key->value) : ''));
		});

		view()->composer('core.admin.index', function($view) {
			$date = new Carbon;
			$date->subWeek();

			$users = User::where('created_at', '>', $date->toDateTimeString())->get();

			$view->with('latest_users', $users);

			$view->with('roles', Role::all());
		});

		view()->composer('core.admin.partials.sidebar', function($view) {
			$user = Auth::user();

			if ($user->can('viewReports'))
			{
				$reports = Report::all();

				$reports = $reports->sortByDesc(function($item)
				{
					return $item->updated_at;
				});

				$reports = $reports->filter(function($item) use ($user)
				{
					return !$item->isClosed();
				});

				$view->with('reports', $reports);
			}
		});

		view()->composer('core.auth.register', function($view) {
			$recaptcha_enabled = Setting::where('name', '=', 'recaptcha')->first();
			$recaptcha_key = Setting::where('name', '=', 'recaptcha_key')->first();

			$view->with('recaptcha_enabled', ($recaptcha_enabled != null ? ($recaptcha_enabled->value == 'true' ? 'true' : 'false') : 'false'));
			$view->with('recaptcha_key', ($recaptcha_key != null ? e($recaptcha_key->value) : ''));
		});

		view()->composer('core.forum.partials.latest-threads', function($view) {
			$threads = Topic::all()->take(5);

			$threads = $threads->filter(function($item) {
				return ($item != null && $item->channel != null && $item->channel->category != null) && $item->channel->category->canView(Auth::user()) && $item->channel->canView(Auth::user());
			});

			$threads = $threads->sortByDesc(function($item) {
				return $item->getLatestPost()->created_at;
			});

			$view->with('threads', $threads);
		});

		view()->composer('core.forum.partials.online-users', function($view) {
			$online = User::where('is_online', '=', 1)->orderBy('name', 'asc')->get();

			$view->with('users', $online);
		});

		view()->composer('core.forum.partials.stats', function($view) {
			$users = User::all();
			$latestUser = User::latest('created_at')->first();

			$view->with('users', $users);
			$view->with('latestUser', $latestUser);
		});

		view()->composer('core.forum.partials.latest-statuses', function($view) {
			$statuses = ProfilePost::latest('created_at')->take(5);

			$statuses = $statuses->filter(function(ProfilePost $item) {
				return !$item->toUser->isBanned();
			});

			$statuses = $statuses->sortByDesc(function($item) {
				return $item->getLatestPost()->created_at;
			});

			$view->with('statuses', $statuses);
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
