<?php namespace App\Http\Controllers\Forum;

use App\Events\TopicWasLocked;
use App\Events\TopicWasUnlocked;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Forum\Threads\ThreadLockRequest;
use App\Http\Requests\Forum\Threads\ThreadUnlockRequest;
use Laracasts\Flash\Flash;

class ModerationController extends Controller {

	/**
	 * Lock a topic.
	 *
	 * @param ThreadLockRequest $request
	 * @return Response
	 */
	public function lock(ThreadLockRequest $request)
	{
		//
		$topic = $request->route()->getParameter('topic');
		$user = $request->user();

		if ($topic->locked == 1)
		{
			Flash::error('This topic is already locked.');
			return redirect()->back();
		}

		event(new TopicWasLocked($topic, $user));

		Flash::success('Locked topic');

		return redirect()->back();
	}

	/**
	 * Unlock a topic.
	 *
	 * @param ThreadUnlockRequest $request
	 * @return Response
	 */
	public function unlock(ThreadUnlockRequest $request)
	{
		//
		$topic = $request->route()->getParameter('topic');
		$user = $request->user();

		if ($topic->locked == 0)
		{
			Flash::error('This topic has not been locked.');
			return redirect()->back();
		}

		event(new TopicWasUnlocked($topic, $user));

		Flash::success('Unlocked topic');

		return redirect()->back();
	}
}
