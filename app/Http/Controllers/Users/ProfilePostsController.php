<?php namespace App\Http\Controllers\Users;

use App\Events\ProfilePostWasDeleted;
use App\Events\UserWroteProfilePost;
use App\Http\Controllers\Controller;

use App\Http\Requests\Profiles\CreateProfilePostRequest;
use App\Http\Requests\Profiles\DeleteProfilePostRequest;
use Laracasts\Flash\Flash;

class ProfilePostsController extends Controller {

	/**
	 * Create a new instance of ProfilePostsController.
	 *
	 * @type mixed
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('confirmed');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateProfilePostRequest $request
	 * @return Response
	 */
	public function store(CreateProfilePostRequest $request)
	{
		//
		$user = $request->user();
		$userToPostOn = $request->route()->getParameter('user');
		$body = $request->input('body');

		if ($userToPostOn->isBanned())
		{
			Flash::error('This user has been banned.');
			return redirect()->route('home.show');
		}

		event(new UserWroteProfilePost($userToPostOn, $user, $body));

		Flash::success('Created profile post');

		return redirect()->back();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param DeleteProfilePostRequest $request
	 * @return Response
	 */
	public function destroy(DeleteProfilePostRequest $request)
	{
		//
		$post = $request->route()->getParameter('profile_post');

		event(new ProfilePostWasDeleted($post, $request->user()));

		Flash::success('Deleted post');

		return redirect()->back();
	}

}
