<?php namespace App\Http\Controllers\Forum;

use App\Events\UserDislikedSomething;
use App\Events\UserLikedSomething;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Forum\Posts\PostDislikeRequest;
use App\Http\Requests\Forum\Posts\PostLikeRequest;
use Laracasts\Flash\Flash;

class LikesController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param PostLikeRequest $request
	 * @return Response
	 */
	public function store(PostLikeRequest $request)
	{
		//
		$post = $request->route()->getParameter('post');

		$user = $request->user();

		if ($post->user->id == $user->getId())
		{
			Flash::error('You can not like your own posts.');
			return redirect()->back();
		}

		if ($post->isLikedBy($user))
		{
			Flash::error('You have already liked this post.');
			return redirect()->back();
		}

		$post->like($user, $post->user);

		event(new UserLikedSomething($user, $post, $post->user));

		Flash::success('Liked post');

		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param PostDislikeRequest $request
	 * @return Response
	 */
	public function destroy(PostDislikeRequest $request)
	{
		//
		$post = $request->route()->getParameter('post');

		$user = $request->user();

		if ($post->user->id == $user->getId())
		{
			Flash::error('You can not dislike your own posts.');
			return redirect()->back();
		}

		if (!$post->isLikedBy($user))
		{
			Flash::error('You have not liked this post.');
			return redirect()->back();
		}

		$post->dislike($user);

		event(new UserDislikedSomething($user, $post, $post->user));

		Flash::success('Disliked post');

		return redirect()->back();
	}

}
