<?php namespace App\Handlers\Events;

use App\Events\UserLikedSomething;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SendLikeNotification {

	/**
	 * Create the event handler.
	 *
	 * @return mixed
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param UserLikedSomething $event
	 * @return void
	 */
	public function handle(UserLikedSomething $event)
	{
		//
		$user = $event->getUser();
		$object = $event->subject();

		if ($event->getUserWhoCreatedObject())
		{
			$userWhoCreated = $event->getUserWhoCreatedObject();

			$userWhoCreated->notifications()->create(array(
				'subject_id' => $object->id,
				'subject_type' => get_class($object),
				'name' => 'user_liked_your_content',
				'user_id' => $userWhoCreated->getId(),
				'sender_id' => $user->getId(),
				'is_read' => 0
			));
		}
	}

}
