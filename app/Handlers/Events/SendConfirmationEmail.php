<?php namespace App\Handlers\Events;

use App\AccountConfirmation;
use App\Role;
use App\Setting;

use App\Events\UserWasRegistered;

use Illuminate\Mail\Mailer;

class SendConfirmationEmail {

	private $confirmation;
	private $role;
	private $setting;

	private $mail;

	/**
	 * Create the event handler.
	 *
	 * @param Mailer $mail
	 * @param AccountConfirmation $confirmation
	 * @param Role $role
	 * @param Setting $setting
	 */
	public function __construct(Mailer $mail, AccountConfirmation $confirmation, Role $role, Setting $setting)
	{
		//
		$this->mail = $mail;
		$this->confirmation = $confirmation;
		$this->role = $role;
		$this->setting = $setting;
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

		if ($user->getAccountConfirmation() == null)
		{
			$confirmation = $this->confirmation->create(array(
				'user_id' => $user->getId(),
				'expires_at' => (time() + 3600),
				'code' => str_random(30)
			));

			$outgoingEmail = $this->setting->where('name', '=', 'outgoing_email')->first();
			$siteName = $this->setting->where('name', '=', 'sitename')->first();

			$this->mail->send('core.emails.auth.confirm', ['user' => $user, 'confirmation' => $confirmation, 'siteName' => $siteName->value], function($message) use ($user, $outgoingEmail, $siteName)
			{
				$message->from($outgoingEmail->value)->to($user->getEmail())->subject('Confirm your ' . $siteName->value . ' account');
			});
		}

	}

}
