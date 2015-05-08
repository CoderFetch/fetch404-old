<?php namespace App\Events;

use App\Events\Event;

use Fetch404\Core\Models\User;
use Illuminate\Queue\SerializesModels;

class UserWroteProfilePost extends Event {

	use SerializesModels;

	public $user;
	public $responsibleUser;
	public $postBody;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param User $responsibleUser
	 * @param string $postBody
	 * @type mixed
	 */
	public function __construct(User $user, User $responsibleUser, $postBody)
	{
		//
		$this->user = $user;
		$this->responsibleUser = $responsibleUser;
		$this->postBody = $postBody;
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

	/**
	 * Get the body of the profile post.
	 *
	 * @return string
	 */
	public function getBody()
	{
		return $this->postBody;
	}
}
