<?php namespace App\Providers;

use App\Events\UserWasBanned;
use App\Events\UserWasUnbanned;
use App\Handlers\Events\BanUser;
use App\Handlers\Events\SetMemberRole;
use App\Handlers\Events\UnbanUser;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\UserWasRegistered;

use App\Handlers\Events\SendConfirmationEmail;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
		UserWasRegistered::class => [
			SendConfirmationEmail::class,
			SetMemberRole::class
		],
		UserWasBanned::class => [
			BanUser::class
		],
		UserWasUnbanned::class => [
			UnbanUser::class
		]
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		//
	}

}
