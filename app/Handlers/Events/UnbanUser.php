<?php namespace App\Handlers\Events;

use App\Events\UserWasUnbanned;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class UnbanUser {

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
	 * @param  UserWasUnbanned  $event
	 * @return void
	 */
	public function handle(UserWasUnbanned $event)
	{
		//
		$user = $event->getUser();
		$currentUser = $event->getResponsibleUser();

		$user->update(array(
			'is_banned' => 0,
			'banned_until' => null
		));

		$user->notifications()->create(array(
			'subject_id' => $currentUser->getId(),
			'subject_type' => get_class($currentUser),
			'name' => 'user_unbanned',
			'user_id' => $user->getId(),
			'sender_id' => $currentUser->getId(),
			'is_read' => 0
		));
	}

}
