<?php namespace App\Http\Controllers\Forum;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
use App\Http\Requests\Forum\Threads\ThreadCreateRequest;
use App\Http\Requests\Forum\Threads\ThreadReplyRequest;
use App\User;
use App\Topic;
use App\Post;
use App\Channel;
use App\Category;

// Illuminate stuff
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ForumController extends Controller {

	private $topic;
	private $post;

	/**
	 * Attempt to create a thread
	 *
	 * @param ThreadCreateRequest $request
	 * @return Response
	 */
	public function postCreateThread(ThreadCreateRequest $request)
	{
		$channel = $request->route()->getParameter('channel');
		$title = $request->input('title');
		$body = $request->input('body');

		$thread = $this->topic->create(array(
			'title' => $title,
			'slug' => str_slug($title, '-'),
			'user_id' => Auth::id(),
			'channel_id' => $channel->id,
			'locked' => 0,
			'pinned' => 0
		));

		$post = $this->post->create(array(
			'topic_id' => $thread->id,
			'user_id' => Auth::id(),
			'content' => $body
		));

		return redirect($thread->Route);
	}

	/**
	 * Attempt to quick-reply to a thread
	 *
	 * @param ThreadReplyRequest $request
	 * @return void
	 */
	public function postQuickReplyToThread(ThreadReplyRequest $request)
	{
		$topic = $request->route()->getParameter('topic');
		$body = $request->input('body');

		$post = Post::create([
			'topic_id' => $topic->id,
			'user_id' => Auth::id(),
			'content' => $body
		]);

		$post->topic->touch();

		return redirect($post->Route);
		//return redirect('/forum/topic/' . $thread->slug . '.' . $thread->id . ($thread->postsPaginated->hasPages() ? '?page=' . $thread->postsPaginated->lastPage() : ''));
	}

	/**
	 * Attempt to reply to a thread
	 *
	 * @param ThreadReplyRequest $request
	 * @return void
	 */	
	public function postReplyToThread(ThreadReplyRequest $request)
	{
		$topic = $request->route()->getParameter('topic');
		$body = $request->input('body');

		$post = Post::create([
			'topic_id' => $topic->id,
			'user_id' => Auth::id(),
			'content' => $body
		]);

		$post->topic->touch();

		return redirect($post->Route);
	}

	/**
	 * Create a new forum page controller instance.
	 *
	 * @param Topic $topic
	 * @param Post $post
	 */
	public function __construct(Topic $topic, Post $post)
	{
		$this->middleware('auth');
		$this->middleware('confirmed');
		$this->middleware('csrf');
		$this->topic = $topic;
		$this->post = $post;
	}

}
