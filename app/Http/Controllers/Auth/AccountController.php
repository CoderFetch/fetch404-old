<?php namespace App\Http\Controllers\Auth;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
use App\Http\Requests\Account\AccountSettingsUpdateRequest;
use App\User;
use App\AccountConfirmation;
use App\NameChange;

// Illuminate stuff
use Illuminate\Http\Request;

// The Laracasts libraries
use Laracasts\Flash\Flash;

// The facades need to be included for some reason O_o
use Auth;
use Hash;
use Mail;
use Response;
use Redirect;
use Session;
use Validator;

class AccountController extends Controller {

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
		$oldEmail = $user->email;

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

			Mail::send('core.emails.auth.reconfirm', ['user' => $user, 'confirmation' => $confirmation, 'siteName' => $siteName], function($message) use ($email, $outgoingEmail, $siteName)
			{
				$message->from($outgoingEmail->value)->to($email)->subject('Please re-confirm your email');
			});
		}

		return redirect()->to('/account/settings');
	}

	/**
	 * Create a new account controller instance.
	 *
	 * @return mixed
	 */
	public function __construct()
	{
		$this->middleware('auth', ['except' => 'activateAccount']);
		$this->middleware('confirmed', ['except' => 'activateAccount']);
	}

}
