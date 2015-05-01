<?php namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

use Illuminate\Contracts\Auth\Guard;

class UpdateLastActivity {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return mixed
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
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

		$user->update(array(
			'last_active' => Carbon::now()->toDateTimeString(),
			'is_online' => 1
		));

		return $next($request);
	}

}
