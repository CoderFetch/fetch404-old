<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\Account\AccountPrivacySettingsUpdateRequest;
use App\Http\Requests\Account\AccountSettingsUpdateRequest;

use App\Http\Requests\Account\ProfileSettingsUpdateRequest;
use Fetch404\Core\Models\AccountConfirmation;
use Illuminate\Mail\Mailer;
use Laracasts\Flash\Flash;

use Auth;
use Hash;
use Mail;
use Response;
use Redirect;
use Session;
use Validator;

class AccountController extends Controller {

	private $mail;

	/*
	|--------------------------------------------------------------------------
	| Account controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the management of a user's account (confirmations, etc)
	| There really isn't much of anything here.
	|
	*/
	
	/**
	 * Attempt to activate an account
	 *
	 * @param string $confirmation_code
	 * @return void
	 */
	public function activateAccount($confirmation_code)
	{
		if (!$confirmation_code)
		{
			return redirect()->to('/');
		}
		
		$confirmation = AccountConfirmation::where(
			'code',
			'=',
			$confirmation_code
		)->first();
		
		if ($confirmation == null)
		{
			Flash::error('Invalid confirmation code.');
			return redirect()->to('/');
		}
		
		$user = $confirmation->user;
		
		if ($user == null)
		{
			Flash::error('The user associated with this confirmation code either does not exist or has been deleted.');
			return redirect()->to('/');			
		}
		
		if ($confirmation->hasExpired())
		{
			Flash::error('That confirmation code has expired.');
			return redirect()->to('/');					
		}
		
		if ($user->isConfirmed())
		{
			Flash::error('The user associated with this confirmation code has already confirmed their account.');
			return redirect()->to('/');
		}
		
		$user->confirmed = 1;
		
		if ($user->save())
		{
			Flash::success('Your account has been activated. You now have access to all the features that confirmed members get.');
			
			$confirmation->delete();
			
			return redirect()->to('/');
		}
		else
		{
			Flash::error('Your account could not be activated.');
			return redirect()->to('/');
		}
	}

	/**
	 * Update an account's settings
	 *
	 * @param AccountSettingsUpdateRequest $request
	 */
	public function updateSettings(AccountSettingsUpdateRequest $request)
	{
		$username = $request->input('name');
		$email = $request->input('email');
		
		$password = $request->input('password');
		
		$user = $request->user(); // Set $user to the request's current user.

		$oldName = $user->name;

		if (!$user)
		{
			return redirect()->to('/');
		}

		if ($username != $user->name && $username != '')
		{
			$user->update(array(
				'name' => $username
			));

			try {
				$user->nameChanges()->create(array(
					'user_id' => $user->id,
					'old_name' => $oldName,
					'new_name' => $username
				));
			}
			catch(\PDOException $ex)
			{

			}
		}

		if ($email != $user->email && $email != '')
		{
			$user->update(array(
				'email' => $email
			));

			$confirmation = AccountConfirmation::create(array(
				'user_id' => $user->id,
				'expires_at' => (time() + 3600),
				'code' => str_random(30)
			));

			$outgoingEmail = Setting::where('name', '=', 'outgoing_email')->first();
			$siteName = Setting::where('name', '=', 'sitename')->first();

			$this->mail->send('core.emails.auth.reconfirm', ['user' => $user, 'confirmation' => $confirmation, 'siteName' => $siteName], function($message) use ($email, $outgoingEmail, $siteName)
			{
				$message->from($outgoingEmail->value)->to($email)->subject('Please re-confirm your email');
			});
		}

		return redirect()->to('/account/settings');
	}

	/**
	 * Update privacy settings
	 *
	 * @param AccountPrivacySettingsUpdateRequest $request
	 * @return Response
	 */
	public function updatePrivacy(AccountPrivacySettingsUpdateRequest $request)
	{
		$user = $request->user();
		$showOnlineStatus = $request->has('show_when_im_online');
		$allowIndexing = $request->has('allow_bots_to_index_me');

		$user->setSetting("show_when_im_online", $showOnlineStatus);
		$user->setSetting("allow_bots_to_index_me", $allowIndexing);

		Flash::success('Updated privacy settings!');

		return redirect()->back();
	}

	/**
	 * Update a user's profile settings
	 *
	 * @param ProfileSettingsUpdateRequest $request
	 * @return Response
	 */
	public function updateProfile(ProfileSettingsUpdateRequest $request)
	{
		$user = $request->user();
		$signature = $request->input('signature', null);

		$user->setSetting("post_signature", $signature);

		Flash::success('Updated profile settings!');

		return redirect()->back();
	}

	/**
	 * Create a new account controller instance.
	 *
	 * @param Mailer $mail
	 */
	public function __construct(Mailer $mail)
	{
		$this->middleware('auth', ['except' => 'activateAccount']);
		$this->middleware('confirmed', ['except' => 'activateAccount']);
		$this->mail = $mail;
	}

}
