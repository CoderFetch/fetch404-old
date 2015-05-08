<?php namespace App\Events;

use App\Events\Event;

use Fetch404\Core\Models\User;
use Illuminate\Queue\SerializesModels;

class UserWasBanned extends Event {

	use SerializesModels;

	public $user;
	public $bannedUntil;
	public $responsibleUser;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param User $responsibleUser
	 * @param $bannedUntil
	 */
	public function __construct(User $user, User $responsibleUser, $bannedUntil)
	{
		//
		$this->user = $user;
		$this->bannedUntil = $bannedUntil;
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
	 * Get the date when the ban expires.
	 *
	 * @return string
	 */
	public function getBannedUntil()
	{
		return $this->bannedUntil;
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
