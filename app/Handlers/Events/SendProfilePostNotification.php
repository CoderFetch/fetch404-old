<?php namespace App\Handlers\Events;

use App\Events\UserWroteProfilePost;

use App\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SendProfilePostNotification {

	private $notification;

	/**
	 * Create the event handler.
	 *
	 * @param Notification $notification
	 * @type mixed
	 */
	public function __construct(Notification $notification)
	{
		//
		$this->notification = $notification;
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
		$responsibleUser = $event->getResponsibleUser();
		$user = $event->getUser();

		if ($responsibleUser->getId() != $user->getId())
		{
			$user->notifications()->create(array(
				'subject_id' => $responsibleUser->getId(),
				'subject_type' => get_class($responsibleUser),
				'name' => 'post_on_your_profile',
				'user_id' => $user->getId(),
				'sender_id' => $responsibleUser->getId(),
				'is_read' => 0
			));
		}
	}

}
