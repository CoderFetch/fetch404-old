<?php namespace App\Handlers\Events;

use App\Events\UserWasRegistered;

use Fetch404\Core\Models\AccountConfirmation;
use Fetch404\Core\Models\Role;
use Fetch404\Core\Models\Setting;
use Fetch404\Core\Repositories\SettingsRepository;

use Illuminate\Mail\Mailer;

class SendConfirmationEmail {

	private $confirmation;
	private $role;
	private $settings;

	private $mail;

	/**
	 * Create the event handler.
	 *
	 * @param Mailer $mail
	 * @param AccountConfirmation $confirmation
	 * @param Role $role
	 * @param SettingsRepository $settingsRepository
	 */
	public function __construct(Mailer $mail, AccountConfirmation $confirmation, Role $role, SettingsRepository $settingsRepository)
	{
		//
		$this->mail = $mail;
		$this->confirmation = $confirmation;
		$this->role = $role;
		$this->settings = $settingsRepository;
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
			$code = str_random(30);

			while ($this->confirmation->where('code', '=', $code) != null)
			{
				$code = str_random(30);
			}

			$confirmation = $this->confirmation->create(array(
				'user_id' => $user->getId(),
				'expires_at' => (time() + 3600),
				'code' => $code
			));

			$outgoingEmail = $this->settings->getByName('outgoing_email', 'admin@mysite.com');
			$siteName = $this->settings->getByName('sitename', 'A Fetch404 Site');

			$this->mail->send('core.emails.auth.confirm', ['user' => $user, 'confirmation' => $confirmation, 'siteName' => $siteName->value], function($message) use ($user, $outgoingEmail, $siteName)
			{
				$message->from($outgoingEmail->value)->to($user->getEmail())->subject('Confirm your ' . $siteName->value . ' account');
			});
		}

	}

}
