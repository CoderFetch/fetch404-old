<?php namespace App\Handlers\Events;

use App\Events\UserFollowedSomeone;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class AddUserFollower {

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
		$user = $event->getUser();
		$userWhoFollowed = $event->getResponsibleUser();

		$userWhoFollowed->followedUsers()->attach($user->getId());

		$user->notifications()->create(array(
			'subject_id' => $userWhoFollowed->getId(),
			'subject_type' => get_class($userWhoFollowed),
			'name' => 'user_followed_you',
			'user_id' => $user->getId(),
			'sender_id' => $userWhoFollowed->getId(),
			'is_read' => 0
		));
	}

}
