<?php namespace App\Handlers\Events;

use App\Events\UserUnfollowedSomeone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class RemoveUserFollower {

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
	 * @param  UserUnfollowedSomeone  $event
	 * @return void
	 */
	public function handle(UserUnfollowedSomeone $event)
	{
		//
		$user = $event->getUser();
		$userWhoUnfollowed = $event->getResponsibleUser();

		$userWhoUnfollowed->followedUsers()->detach($user->getId());

		$user->notifications()->create(array(
			'subject_id' => $userWhoUnfollowed->getId(),
			'subject_type' => get_class($userWhoUnfollowed),
			'name' => 'user_unfollowed_you',
			'user_id' => $user->getId(),
			'sender_id' => $userWhoUnfollowed->getId(),
			'is_read' => 0
		));
	}

}
