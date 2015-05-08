<?php namespace App\Events;

use App\Events\Event;

use Fetch404\Core\Models\User;
use Illuminate\Queue\SerializesModels;

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
