<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

use App\User;

class UserWasRegistered extends Event {

	use SerializesModels;

	public $user;

	/**
	 * Create a new event instance.
	 *
	 * @param $user
	 */
	public function __construct(User $user)
	{
		//
		$this->user = $user;
	}

	/**
	 * Get the user for this event.
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
}
