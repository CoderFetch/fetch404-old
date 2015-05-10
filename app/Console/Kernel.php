<?php namespace App\Console;

use Carbon\Carbon;
use Fetch404\Core\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Schema;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$banned = User::banned()->get();
		$all = User::all();

		$schedule->call(function() use ($banned)
		{
			foreach($banned as $u)
			{
				if ($u->banned_until != null)
				{
					if ($u->banned_until < Carbon::now()->toDateTimeString())
					{
						$u->update(array(
							'is_banned' => 0,
							'banned_until' => null
						));
					}
				}
			}
		})->when(function()
		{
			return Schema::hasTable('users') && Schema::hasTable('migrations') && User::banned()->count() > 0;
		})->cron('* * * * *');

		$schedule->call(function() use ($all)
		{
			$now = Carbon::now();
			$now->subMinutes(15); // A user is offline if they do nothing for 15 minutes

			foreach($all as $u)
			{
				if ($u->last_active != null && $u->last_active < $now->toDateTimeString())
				{
					$u->update(array(
						'is_online' => 0
					));
				}
			}
		})->when(function()
		{
			return Schema::hasTable('users') && Schema::hasTable('migrations') && User::all()->count() > 0;
		})->cron('* * * * *');
	}

}
