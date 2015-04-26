<?php namespace App\Handlers\Events;

use App\Events\UserWasBanned;

class BanUser {

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
	 * @param  UserWasBanned  $event
	 * @return void
	 */
	public function handle(UserWasBanned $event)
	{
		//
		$user = $event->getUser();
	}

}
