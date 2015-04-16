<?php namespace App\Http\Controllers\Auth;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
use App\User;
use App\Role;
use App\AccountConfirmation;

use App\Http\Requests\Auth\LoginRequest;
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

class AuthController extends Controller {
	
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. 
	|
	*/
	
	/**
	 * Show the login page
	 *
	 * @return void
	 */
	public function showLogin()
	{
		return view('core.auth.login');
	}
	
	/**
	 * Show the signup page
	 *
	 * @return void
	 */
	public function showRegister()
	{
		return view('core.auth.register');
	}
	
	/**
	 * Attempt to log in a user
	 *
	 * @param LoginRequest $request
	 * @return void
	 */
	public function postLogin(LoginRequest $request)
	{
		Flash::success('Logged in');
		return redirect('/');
	}
	
	/**
	 * Attempt to register a user in the database
	 * 
	 * @param Illuminate\Http\Request $request
	 * @return void
	 */
	public function postRegister(Request $request)
	{
		$name = $request->input('name');
		$email = $request->input('email');
		
		$password = $request->input('password');
		$password_confirmation = $request->input('password_confirmation');
		
		$validator = Validator::make(
			[
				'name' => $name,
				'email' => $email,
				'password' => $password,
				'password_confirmation' => $password_confirmation
			],
			[
				'name' => 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users',
				'email' => 'required|unique:users|email',
				'password' => 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/',
			],
			[
				'name.required' => 'A username is required.',
				'name.min' => 'Usernames must be at least 5 characters long.',
				'name.max' => 'Usernames can be up to 13 characters long.',
				'name.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
				'name.unique' => 'That username is taken. Try another!',
				'email.required' => 'An email address is required.',
				'email.unique' => 'Another account is using this email. Contact support if you don\'t know anything about this.',
				'email.email' => 'Please enter a valid email. Without this, we are unable to provide email-based support.',
				'password.required' => 'A password is required.',
				'password.min' => 'Passwords must be at least 8 characters long.',
				'password.max' => 'Passwords can be up to 30 characters long.',
				'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
				'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #'
			]
		);
		
		if ($validator->passes())
		{
			$user = User::create([
				'name' => $name,
				'email' => $email,
				'password' => Hash::make($password),
				'confirmed' => 0,
				'slug' => str_slug($name, "-")
			]);
			
			$confirmation = AccountConfirmation::create([
				'user_id' => $user->id,
				'expires_at' => (time() + 3600),
				'code' => str_random(30)
			]);
			
			$memberRole = Role::where('name', '=', 'member')->first();
			
			if ($memberRole)
			{
				$user->attachRole($memberRole);
			}
			
			Mail::send('core.emails.auth.confirm', ['user' => $user, 'confirmation' => $confirmation], function($message) use ($email)
			{
				$message->from('noreply@minerzone.net')->to($email)->subject('Confirm your MinerZone account');
			});
			
			Flash::success('You have registered on our website! Please check your inbox for a confirmation email.');
			
			return redirect()
				->to('/');
		}
		else
		{
			return redirect()->to('/register')
				->withErrors($validator->messages())
				->withInput();
		}
	}
	
	public function getLogout()
	{
		if (Auth::check()) 
		{
			Auth::logout();
			Session::flush();
			
			return redirect()
				->to('/');
		} 
		else 
		{
			return redirect()
				->to('/');
		}
	}
	 

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return mixed
	 */
	public function __construct()
	{	
		$this->middleware('guest', ['except' => 'getLogout']);
	}

}
