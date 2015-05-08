<?php namespace App\Http\Middleware;

use Closure;
use Fetch404\Core\Models\Setting;
use Session;

use Illuminate\Contracts\Auth\Guard;

class BanCheck {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * The Session implementation.
	 *
	 * @var Session
	 */
	protected $session;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @param Session $session
	 * @return mixed
	 */
	public function __construct(Guard $auth, Session $session)
	{
		$this->auth = $auth;
		$this->session = $session;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!$this->auth->check())
		{
			return $next($request);
		}

		$user = $request->user();
		$site_title = Setting::where('name', '=', 'sitename')->first();

		$routeName = $request->route()->getName();

		if ($user->isBanned() && $routeName != 'auth.get.logout')
		{
			return response(
				view('core.errors.banned',
					array(
						'user' => $user, 'site_title' => ($site_title ? $site_title->value : 'N/A'), 'banned_until' => $user->banned_until
					)
				),
				500
			);
		}

		else
		{
			return $next($request);
		}
	}

}
