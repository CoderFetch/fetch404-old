<?php namespace App\Handlers\Events;

use App\Events\UserFollowedSomeone;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SendFollowerNotification {

	/**
	 * Create the event handler.
	 *
	 * @return mixed
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserFollowedSomeone  $event
	 * @return void
	 */
	public function handle(UserFollowedSomeone $event)
	{
		//
	}

}
