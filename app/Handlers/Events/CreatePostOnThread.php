<?php namespace App\Handlers\Events;

use App\Events\UserRepliedToThread;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class CreatePostOnThread {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserRepliedToThread  $event
	 * @return void
	 */
	public function handle(UserRepliedToThread $event)
	{
		//
	}

}
