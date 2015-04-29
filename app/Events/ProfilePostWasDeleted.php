<?php namespace App\Events;

use App\Events\Event;

use App\ProfilePost;
use App\User;

use Illuminate\Queue\SerializesModels;

class ProfilePostWasDeleted extends Event {

	use SerializesModels;

	public $profilePost;
	public $responsibleUser;

	/**
	 * Create a new event instance.
	 *
	 * @param ProfilePost $profilePost
	 * @param User $responsibleUser
	 */
	public function __construct(ProfilePost $profilePost, User $responsibleUser)
	{
		//
		$this->profilePost = $profilePost;
		$this->responsibleUser = $responsibleUser;
	}

	/**
	 * Get the post for this event.
	 *
	 * @return ProfilePost
	 */
	public function getProfilePost()
	{
		return $this->profilePost;
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
