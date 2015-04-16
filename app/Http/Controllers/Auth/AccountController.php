<?php namespace App\Http\Controllers\Auth;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
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
	 * @param Illuminate\Http\Request $request
	 * @return void
	 */
	public function updateSettings(Request $request)
	{
		$username = $request->input('username');
		$email = $request->input('email');
		
		$password = $request->input('password');
		$password_confirmation = $request->input('password_confirmation');
		
		$user = $request->user(); // Set $user to the request's current user.
		
		$success = false;
		
		if (!$user)
		{
			return redirect()->to('/');
		}
		
		if ($username != null && $username != '')
		{
			if ($user->name != $username) // Name change :)
			{
				if (User::where('name', '=', $username)->count() == 0)
				{
					$nameValidator = Validator::make(
						[
							'username' => $username
						],
						[
							'username' => 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users'
						],
						[
							'username.required' => 'A username is required.',
							'username.min' => 'Usernames must be at least 5 characters long.',
							'username.max' => 'Usernames can be up to 13 characters long.',
							'username.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
							'username.unique' => 'That username is taken. Try another!'
						]
					);
					if ($nameValidator->passes())
					{
						$nameChange = NameChange::create([
							'user_id' => $user->id,
							'old_name' => $user->name,
							'new_name' => $username
						]);
			
						$user->name = $username;
						$user->slug = str_slug($username, "-");
			
						if ($user->save()) 
						{
							$success = true;
						}
					}
					else
					{
						return redirect()->to('/account/settings')->withErrors($validator->messages());
					}
				}
			}
		}
		
		if ($email != null && $email != '')
		{
			if ($user->email != $email) // Email change
			{
				$emailValidator = Validator::make(
					[
						'email' => $email
					],
					[
						'email' => 'required|email|unique:users'
					],
					[
						'email.required' => 'A username is required.',
						'email.email' => 'Please enter a valid email.',
						'email.unique' => 'That email is already in use.'
					]
				);
				if ($emailValidator->passes())
				{
					$user->email = $email;
					$user->confirmed = 0;
		
					if ($user->save()) 
					{
						$confirmation = AccountConfirmation::create([
							'user_id' => $user->id,
							'expires_at' => (time() + 3600),
							'code' => str_random(30)
						]);
	
						Mail::send('core.emails.auth.confirm', ['user' => $user, 'confirmation' => $confirmation], function($message) use ($email)
						{
							$message->from('noreply@minerzone.net')->to($email)->subject('Confirm your MinerZone account');
						});
					
						$success = true;
					}
				}
			}
			else
			{
				return redirect()->to('/account/settings')->withErrors($emailValidator->messages())->withInput();
			}
		}		
		
		if ($password != null && $password != '') 
		{
			if (!Hash::check($password, $user->password))
			{
				$password_validator = Validator::make(
					[
						'password' => $password,
						'password_confirmation' => $password_confirmation
					],
					[
						'password' => 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/',
					],
					[
						'password.required' => 'A password is required.',
						'password.min' => 'Passwords must be at least 8 characters long.',
						'password.max' => 'Passwords can be up to 30 characters long.',
						'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
						'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #'
					]
				);
				
				if ($password_validator->passes())
				{
					$user->password = Hash::make($password);
					
					if ($user->save()) 
					{
						$success = true;
					}
				}
				else
				{
					return redirect()->to('/account/settings')->withErrors($password_validator->messages())->withInput();
				}
			}
		}
		
		if ($success)
		{
			Flash::info('Updated account');
		}
		
		return redirect()->to('/account/settings');
	}
	/**
	 * Create a new account controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
	}

}
