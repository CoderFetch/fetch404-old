<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

use App\User;

class UserFollowedSomeone extends Event {

	use SerializesModels;

	public $followedUser;
	public $userWhoFollowed;

	/**
	 * Create a new event instance.
	 *
	 * @param User $followedUser
	 * @param User $userWhoFollowed
	 * @type mixed
	 */
	public function __construct(User $followedUser, User $userWhoFollowed)
	{
		//
		$this->followedUser = $followedUser;
		$this->userWhoFollowed = $userWhoFollowed;
	}

	/**
	 * Get the user for this event.
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->followedUser;
	}

	/**
	 * Get the user responsible for this action.
	 *
	 * @return User
	 */
	public function getResponsibleUser()
	{
		return $this->userWhoFollowed;
	}
}
