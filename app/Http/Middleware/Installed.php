<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class Installed {

    /**
     * Create a new filter instance.
     *
     * @return mixed
     */
    public function __construct()
    {

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
        //dd($request->route()); debug #1
        if ($request->route()->getActionName() != 'App\Http\Controllers\InstallController@install')
        {
            // Run middleware UNLESS action name is 'InstallController@install'
            if (DB::connection()->getDatabaseName())
            {
                if (Schema::hasTable('migrations')) //Migrations table exists
                {
                    return $next($request);
                } else {
                    // Go to the install page, we haven't installed yet.
                    return view('core.installer.index');
                }
            } else {
                return view('core.installer.configuredb');
            }
        }

    }
}
