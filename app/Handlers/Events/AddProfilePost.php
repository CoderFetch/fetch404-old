<?php namespace App\Handlers\Events;

use App\Events\UserWroteProfilePost;

use Fetch404\Core\Models\ProfilePost;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class AddProfilePost {

	private $profilePost;

	/**
	 * Create the event handler.
	 *
	 * @param ProfilePost $profilePost
	 * @type mixed
	 */
	public function __construct(ProfilePost $profilePost)
	{
		//
		$this->profilePost = $profilePost;
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserWroteProfilePost  $event
	 * @return void
	 */
	public function handle(UserWroteProfilePost $event)
	{
		//
		$user = $event->getUser();
		$responsibleUser = $event->getResponsibleUser();
		$body = $event->getBody();

		$this->profilePost->create(array(
			'from_user_id' => $responsibleUser->getId(),
			'to_user_id' => $user->getId(),
			'body' => $body
		));
	}

}
