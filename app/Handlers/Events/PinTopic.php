<?php namespace App\Handlers\Events;

use App\Events\TopicWasPinned;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class PinTopic {

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
	 * @param  TopicWasPinned  $event
	 * @return void
	 */
	public function handle(TopicWasPinned $event)
	{
		//
		$topic = $event->getTopic();
		$currentUser = $event->getResponsibleUser();
		$user = $topic->getUser();

		$topic->update(array(
			'pinned' => 1
		));

		$topic->getUser()->notifications()->create(array(
			'subject_id' => $currentUser->getId(),
			'subject_type' => get_class($currentUser),
			'name' => 'topic_pinned',
			'user_id' => $user->getId(),
			'sender_id' => $currentUser->getId(),
			'is_read' => 0
		));
	}

}
