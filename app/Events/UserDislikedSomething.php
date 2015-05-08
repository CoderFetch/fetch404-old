<?php namespace App\Events;

use Fetch404\Core\Models\User;
use Illuminate\Queue\SerializesModels;

class UserDislikedSomething extends Event {

	use SerializesModels;

	public $user;
	public $object;
	public $userWhoCreatedObject;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param $object
	 * @param $userWhoCreatedObject
	 * @return mixed
	 */
	public function __construct(User $user, $object, $userWhoCreatedObject)
	{
		//
		$this->user = $user;
		$this->object = $object;
		$this->userWhoCreatedObject = $userWhoCreatedObject;
	}

	/**
	 * Get the user who performed this action.
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Get the user who created the object related to this event.
	 *
	 * @return User
	 */
	public function getUserWhoCreatedObject()
	{
		return $this->userWhoCreatedObject;
	}

	/**
	 * Get the object related to this event.
	 *
	 * @return mixed
	 */
	public function subject()
	{
		return $this->object;
	}
}
