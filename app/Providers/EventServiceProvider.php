<?php namespace App\Providers;

use App\Events\ProfilePostWasDeleted;
use App\Events\TopicWasLocked;
use App\Events\TopicWasPinned;
use App\Events\UserFollowedSomeone;
use App\Events\UserUnfollowedSomeone;
use App\Events\UserWasBanned;
use App\Events\UserWasUnbanned;
use App\Events\UserWroteProfilePost;
use App\Handlers\Events\AddProfilePost;
use App\Handlers\Events\AddUserFollower;
use App\Handlers\Events\BanUser;
use App\Handlers\Events\DeleteProfilePost;
use App\Handlers\Events\LockTopic;
use App\Handlers\Events\PinTopic;
use App\Handlers\Events\RemoveUserFollower;
use App\Handlers\Events\SendProfilePostNotification;
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
		],
		TopicWasLocked::class => [
			LockTopic::class
		],
		TopicWasPinned::class => [
			PinTopic::class
		],
		UserFollowedSomeone::class => [
			AddUserFollower::class
		],
		UserUnfollowedSomeone::class => [
			RemoveUserFollower::class
		],
		UserWroteProfilePost::class => [
			AddProfilePost::class,
			SendProfilePostNotification::class
		],
		ProfilePostWasDeleted::class => [
			DeleteProfilePost::class
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
