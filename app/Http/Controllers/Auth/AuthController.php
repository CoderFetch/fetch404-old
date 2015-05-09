<?php namespace App\Http\Controllers\Auth;

// External libraries (well, sort of)
use App\Events\UserWasRegistered;
use App\Http\Controllers\Controller;

// Models
use App\Http\Requests\Auth\LoginJSONRequest;
use App\Http\Requests\Auth\RegisterRequest;

use App\Http\Requests\Auth\LoginRequest;
use Fetch404\Core\Models\User;
use Illuminate\Contracts\Auth\Guard;

use Laracasts\Flash\Flash;

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

	/**
	 * Attempt to register a user in the database
	 *
	 * @param RegisterRequest $request
	 * @return Response
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
