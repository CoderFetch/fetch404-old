<?php namespace App\Handlers\Events;

use App\Events\UserWasRegistered;

class SendConfirmationEmail {

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
	 * @param  UserWasRegistered  $event
	 * @return void
	 */
	public function handle(UserWasRegistered $event)
	{
		//
		$user = $event->getUser();
		dd($user);
	}

}
