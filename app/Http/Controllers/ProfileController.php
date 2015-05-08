<?php namespace App\Http\Controllers;

use Auth;
use Fetch404\Core\Models\User;
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
	 * @param $slug
	 * @param $id
	 * @return Response
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
