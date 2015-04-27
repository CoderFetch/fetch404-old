<?php namespace App\Console;

use App\User;
use Carbon\Carbon;
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
//		$schedule->command('inspire')
//				 ->hourly();
//		if (Schema::has('migrations'))
//		{
//			$users = User::banned()->all();
//
//			$schedule->call(function() use ($users)
//			{
//				foreach($users as $u)
//				{
//					if ($u->banned_until != null)
//					{
//						if ($u->banned_until < Carbon::now()->toDateTimeString())
//						{
//							$u->update(array(
//								'is_banned' => 0,
//								'banned_until' => null
//							));
//						}
//					}
//				}
//			})->everyFiveMinutes();
//		}
	}

}
