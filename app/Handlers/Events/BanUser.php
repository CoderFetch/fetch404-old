<?php namespace App\Handlers\Events;

use App\Events\UserWasBanned;
use Carbon\Carbon;

class BanUser {

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
	 * @param  UserWasBanned  $event
	 * @return void
	 */
	public function handle(UserWasBanned $event)
	{
		//
		$user = $event->getUser();
		$banTime = $event->getBannedUntil();
		$currentUser = $event->getResponsibleUser();

		$now = Carbon::now();

		$now->addDays(4);
		$now->addHours(2);
		$now->addMinutes(1);
		$now->addSeconds(4);

		$user->update(array(
			'is_banned' => 1,
			'banned_until' => $now->toDateTimeString()
		));

		$user->notifications()->create(array(
			'subject_id' => $currentUser->getId(),
			'subject_type' => get_class($currentUser),
			'name' => 'user_banned',
			'user_id' => $user->getId(),
			'sender_id' => $currentUser->getId(),
			'is_read' => 0
		));
	}

}
