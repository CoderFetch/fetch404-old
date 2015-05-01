<?php namespace App\Http\Middleware;

use Closure;

use Config;
use Schema;

use App\Setting;

class WriteConfig {

    /**
     * The Config implementation.
     *
     * @var Config
     */
    protected $config;

    /**
     * Create a new filter instance.
     *
     * @param Config $config
     * @return mixed
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
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
        if (Schema::hasTable('settings'))
        {
            $baseURL = Setting::where('name', '=', 'base_url')->first();

            Config::set('app.url', $baseURL != null ? $baseURL->value : route('home.show'));
        }

        Config::set('app.debug', true);

        return $next($request);
    }

}
