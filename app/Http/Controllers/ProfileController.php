<?php namespace App\Http\Controllers;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
use App\User;
use App\AccountConfirmation;

// Illuminate stuff
use Illuminate\Http\Request;

// The Laracasts libraries
use Laracasts\Flash\Flash;

// The facades need to be included for some reason O_o
use Auth;
use Response;
use Redirect;
use Session;
use Validator;

class ProfileController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Profile controller
	|--------------------------------------------------------------------------
	|
	| This controller handles management of user profiles. Users can
	| change various parts of their profile (username, password, email, etc)
	| This also handles showing the profile view.
	|
	*/
	
	/**
	 * Attempt to show a user's profile
	 *
	 * @param integer $user_id
	 * @return void
	 */
	public function showProfile($slug, $id)
	{
		if (!$slug || !$id)
		{
			// We don't have a user slug, what should we do?
			if (Auth::check())
			{
				$user = Auth::user();

				$user->update(array(
					'last_active_desc' => 'Viewing ' . $user->name . '\'s profile'
				));

				return view('core.user.profile', [
					'user' => Auth::user()
				]);
			}
			else
			{
				return redirect()->to('/');
			}
			
			// Somehow none of the above conditions return true... 
			// so we just redirect to the home page if this happens.
			return redirect()->to('/');
		}
		else
		{
			$user = User::where(
				'slug',
				'=',
				$slug
			)->where(
				'id',
				'=',
				$id
			)->first(); // doing first() just in case there are somehow two users with the same ID...
			
			if ($user == null || !$user)
			{
				return view('core.errors.modelnotfound');
			}

			if ($user->isBanned())
			{
				return view('core.errors.profilenotavailable', array('user' => $user));
			}

//			if (Auth::check())
//			{
//				Auth::user()->update(array(
//					'last_active_desc' => 'Viewing ' . $user->name . '\'s profile'
//				));
//			}

			return view('core.user.profile', [
				'user' => $user
			]);
		}
	}

	/**
	 * Create a new profile controller instance.
	 *
	 * @return mixed
	 */
	public function __construct()
	{
		
	}

}
