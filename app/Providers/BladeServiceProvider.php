<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /* @datetime($var) */
        \Blade::extend(function($view, $compiler)
        {
            $pattern = $compiler->createOpenMatcher('datetime');

            return preg_replace($pattern, '$1<?php echo $2->format(\'m/d/Y H:i\')); ?>', $view);
        });

        /* @eval($var++) */
        \Blade::extend(function($view)
        {
            return preg_replace('/\@eval\((.+)\)/', '<?php ${1}; ?>', $view);
        });
    }

    public function register()
    {
        //
    }
}