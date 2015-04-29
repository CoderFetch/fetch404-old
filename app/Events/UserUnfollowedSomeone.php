<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

use App\User;

class UserUnfollowedSomeone extends Event
{

	use SerializesModels;

	public $unfollowedUser;
	public $userWhoUnfollowed;

	/**
	 * Create a new event instance.
	 *
	 * @param User $unfollowedUser
	 * @param User $userWhoUnfollowed
	 * @type mixed
	 */
	public function __construct(User $unfollowedUser, User $userWhoUnfollowed)
	{
		//
		$this->unfollowedUser = $unfollowedUser;
		$this->userWhoUnfollowed = $userWhoUnfollowed;
	}

	/**
	 * Get the user for this event.
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->unfollowedUser;
	}

	/**
	 * Get the user responsible for this action.
	 *
	 * @return User
	 */
	public function getResponsibleUser()
	{
		return $this->userWhoUnfollowed;
	}
}