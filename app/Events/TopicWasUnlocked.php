<?php namespace App\Events;

use App\Events\Event;

use App\Topic;
use App\User;
use Illuminate\Queue\SerializesModels;

class TopicWasUnlocked extends Event {

	use SerializesModels;

	public $topic;
	public $responsibleUser;

	/**
	 * Create a new event instance.
	 *
	 * @param Topic $topic
	 * @param User $responsibleUser
	 * @type mixed
	 */
	public function __construct(Topic $topic, User $responsibleUser)
	{
		//
		$this->topic = $topic;
		$this->responsibleUser = $responsibleUser;
	}

	/**
	 * Get the topic for this event.
	 *
	 * @return Topic
	 */
	public function getTopic()
	{
		return $this->topic;
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
