<?php namespace App\Http\Controllers\Auth;

// External libraries (well, sort of)
use App\Events\UserWasRegistered;
use App\Http\Controllers\Controller;

// Models
use App\Http\Requests\Auth\LoginJSONRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\User;
use App\Role;
use App\AccountConfirmation;

use App\Http\Requests\Auth\LoginRequest;
// Illuminate stuff
use Illuminate\Contracts\Auth\Guard;
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

use App\Setting;

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

	protected $auth;

	protected $user;

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

	public function postJSONLogin(LoginJSONRequest $request)
	{
		$username = $request->input('name_or_email');
		$password = $request->input('password');

		$db_field = (filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name');
		$user = User::where(
			$db_field,
			'=',
			$username
		)->first();

		if (!$user || $user == null)
		{
			return response()->json(array('message' => 'User not found'));
		}

		if (!Hash::check($password, $user->password))
		{
			return response()->json(array('message' => 'Invalid password'));
		}

		if ($this->auth->attempt(array($db_field => $username, 'password' => $password), $request->has('remember')))
		{
			return response()->json(array('status' => 'success'));
		}
	}

	/**
	 * Attempt to register a user in the database
	 *
	 * @param RegisterRequest $request
	 */
	public function postRegister(RegisterRequest $request)
	{
		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');

		$user = $this->user->create(array(
			'name' => $name,
			'email' => $email,
			'password' => Hash::make($password),
			'confirmed' => 0,
			'slug' => str_slug($name, "-")
		));

		event(new UserWasRegistered($user));

		if ($this->auth->attempt(array('email' => $email, 'password' => $password), 1))
		{
			Flash::success('You have registered on our site! Please check your inbox for a confirmation email.');
			return redirect()->to('/');
		}
		else
		{
			Flash::success('You have registered on our site! Please check your inbox for a confirmation email.');
			return redirect()->to('/');
		}

//		$validator = Validator::make(
//			[
//				'name' => $name,
//				'email' => $email,
//				'password' => $password,
//				'password_confirmation' => $password_confirmation
//			],
//			[
//				'name' => 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users',
//				'email' => 'required|unique:users|email',
//				'password' => 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/',
//			],
//			[
//				'name.required' => 'A username is required.',
//				'name.min' => 'Usernames must be at least 5 characters long.',
//				'name.max' => 'Usernames can be up to 13 characters long.',
//				'name.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
//				'name.unique' => 'That username is taken. Try another!',
//				'email.required' => 'An email address is required.',
//				'email.unique' => 'Another account is using this email. Contact support if you don\'t know anything about this.',
//				'email.email' => 'Please enter a valid email. Without this, we are unable to provide email-based support.',
//				'password.required' => 'A password is required.',
//				'password.min' => 'Passwords must be at least 8 characters long.',
//				'password.max' => 'Passwords can be up to 30 characters long.',
//				'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
//				'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #'
//			]
//		);
//
//		if ($validator->passes())
//		{
//			$user = $this->user->create(array(
//				'name' => $name,
//				'email' => $email,
//				'password' => Hash::make($password),
//				'confirmed' => 0,
//				'slug' => str_slug($name, "-")
//			));
//
//			event(new UserWasRegistered($user));
//
//			Flash::success('You have registered on our website! Please check your inbox for a confirmation email.');
//
//			return redirect()
//				->to('/');
//		}
//		else
//		{
//			return redirect()->to('/register')
//				->withErrors($validator->messages())
//				->withInput();
//		}
	}
	
	public function getLogout()
	{
		if ($this->auth->check())
		{
			$this->auth->user()->update(array(
				'is_online' => 0
			));

			$this->auth->logout();
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
	 * @param Guard $auth
	 * @param User $user
	 */
	public function __construct(Guard $auth, User $user)
	{
		$this->auth = $auth;
		$this->user = $user;
		$this->middleware('guest', ['except' => 'getLogout']);
	}

}
