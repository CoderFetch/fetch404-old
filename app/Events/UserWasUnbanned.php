<?php namespace App\Events;

use App\Events\Event;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserWasUnbanned extends Event {

	use SerializesModels;

	public $user;
	public $responsibleUser;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param User $responsibleUser
	 * @type mixed
	 */
	public function __construct(User $user, User $responsibleUser)
	{
		//
		$this->user = $user;
		$this->responsibleUser = $responsibleUser;
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

	/**
	 * Get the user responsible for this action.
	 *
	 * @return User
	 */
	public function getResponsibleUser()
	{
		return $this->responsibleUser;
	}
}
