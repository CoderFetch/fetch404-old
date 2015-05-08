<?php namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;

use Auth;
use Fetch404\Core\Models\Category;
use Fetch404\Core\Models\Channel;
use Fetch404\Core\Models\Topic;

class ForumPageController extends Controller {

	private $category;
	private $channel;

	/*
	|--------------------------------------------------------------------------
	| Forum page controller
	|--------------------------------------------------------------------------
	|
	| All this does is show the forum-related views.
	| There really isn't much of anything here.
	|
	*/
	
	/**
	 * Attempt to show the forum index page
	 *
	 * @return void
	 */
	public function showIndex()
	{
		$categories = $this->category->with('channels')->get();

		$categories = $categories->filter(function($item)
		{
			return $item->can(20, Auth::user());
		});

		foreach($categories as $category)
		{
			$category->channels = $category->channels->filter(function($item)
			{
				return $item->can(21, Auth::user());
			});
		}

		if (Auth::check())
		{
			Auth::user()->update(array(
				'last_active_desc' => 'Viewing forum index'
			));
		}

		return view('core.forum.index', [
			'categories' => $categories
		]);
	}

	/**
	 * Attempt to show a certain forum
	 *
	 * @param Category $category
	 * @return Response
	 */
	public function showForum(Category $category)
	{
		if (!$category->canView(Auth::user()))
		{
			abort(403);
		}

		if (Auth::check())
		{
			Auth::user()->update(array(
				'last_active_desc' => 'Viewing category "' . $category->name . '"'
			));
		}

		$category->channels = $category->channels->filter(function($item)
		{
			return $item->can(21, Auth::user());
		});

		return view('core.forum.category', [
			'category' => $category
		]);
	}

	/**
	 * Attempt to show a certain channel
	 *
	 * @param Channel $channel
	 * @return Response
	 */
	public function showChannel(Channel $channel)
	{
		if (!$channel->canView(Auth::user()))
		{
			abort(403);
		}

		if (Auth::check())
		{
			Auth::user()->update(array(
				'last_active_desc' => 'Viewing channel "' . $channel->name . '"'
			));
		}

		return view('core.forum.channel', [
			'channel' => $channel
		]);
	}

	/**
	 * Attempt to show a certain thread
	 *
	 * @param Channel $channel
	 * @param Topic $topic
	 * @return Response
	 */
	public function showThread(Channel $channel, Topic $topic)
	{
		if (!$channel->category->canView(Auth::user())) abort(403);
		if (!$channel->canView(Auth::user())) abort(403);
		if (!$topic->canView) abort(403);

		if (Auth::check())
		{
			if (!$topic->old && !is_null(Auth::user()))
			{
				$topic->markAsRead(Auth::id());
			}

			Auth::user()->update(array(
				'last_active_desc' => 'Viewing thread "' . $topic->title . '"'
			));
		}

		return view('core.forum.thread', [
			'thread' => $topic
		]);
	}

	/**
	 * Show the "create thread" page
	 *
	 * @param Channel $channel
	 * @return void
	 */
	public function showCreateThread(Channel $channel)
	{
		if (!$channel->canView(Auth::user())) abort(403);
		if (!$channel->can(1, Auth::user())) abort(403);

		return view('core.forum.create-thread', [
			'channel' => $channel
		]);
	}

	/**
	 * Show the "full reply" page
	 *
	 * @param Channel $channel
	 * @param Topic $topic
	 * @return Response
	 */
	public function showReplyToThread(Channel $channel, Topic $topic)
	{
		if (!$topic->channel->can(6, Auth::user())) abort(403);
		if (!$topic->canView) abort(403);
		if (!$topic->canReply) abort(403);

		return view('core.forum.thread-reply', [
			'thread' => $topic
		]);
	}

	/*
	 * Show the "top posters" page.
	 *
	 * @return void
	 */
	public function showTopPosters()
	{
		$topPosters = new Collection(User::all());

		$topPosters = $topPosters->sortByDesc(function($item)
		{
			return $item->posts()->count();
		});

		$topPosters = $topPosters->filter(function($topPoster)
		{
			if ($topPoster->posts()->count() > 0 && $topPoster->isConfirmed())
			{
				// User has more than 0 posts and has confirmed their account
				return true;
			}
		});

		$totalPosts = $topPosters->sum(function($item)
		{
			return $item->posts->filter(function($item)
			{
				return $item->topic != null && $item->topic->canView;
			})->count();
		});

		return view('core.forum.top-posters', [
			'top_posters' => $topPosters->take(10),
			'totalPosts' => $totalPosts
		]);
	}

	/**
	 * Create a new forum page controller instance.
	 *
	 * @param Channel $channel
	 * @param Category $category
	 * @type mixed
	 */
	public function __construct(Channel $channel, Category $category)
	{
		$this->channel = $channel;
		$this->category = $category;
	}

}
