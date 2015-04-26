<?php namespace App\Handlers\Events;

use App\Events\UserWasRegistered;

use App\Role;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SetMemberRole {

	private $role;

	/**
	 * Create the event handler.
	 *
	 * @param Role $role
	 */
	public function __construct(Role $role)
	{
		//
		$this->role = $role;
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

		$defaultRole = $this->role->where('is_default', '=', 1)->first();

		if ($defaultRole)
		{
			$user->attachRole($defaultRole);
		}
	}

}
