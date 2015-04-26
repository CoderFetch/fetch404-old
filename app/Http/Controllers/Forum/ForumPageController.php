<?php namespace App\Http\Controllers\Forum;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
use App\User;
use App\Topic;
use App\Post;
use App\Channel;
use App\Category;

// Illuminate stuff
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

class ForumPageController extends Controller {

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
		$categories = Category::with('channels')->get();

		$categories = $categories->filter(function($item)
		{
			return $item->can(21, Auth::user());
		});
		
		return view('core.forum.index', [
			'categories' => $categories
		]);
	}
	
	/**
	 * Attempt to show a certain forum
	 *
	 * @param string $slug
	 * @return void
	 */
	public function showForum($slug)
	{
		if (!$slug)
		{
			return redirect()->to('/forum');
		}
		
		$category = Category::where(
			'slug',
			'=',
			$slug
		)->with('channels')->first();
		
		if ($category)
		{
			if (!$category->canView(Auth::user()))
			{
				abort(403);
			}

			return view('core.forum.category', [
				'category' => $category
			]);
		}
		else
		{
			return redirect()->to('/forum');
		}
	}
	
	/**
	 * Attempt to show a certain forum (by finding the entry with a certain ID)
	 *
	 * @param integer $id
	 * @return void
	 */
	public function showForumById($id)
	{
		if (!$id)
		{
			return redirect()->to('/forum');
		}
		
		$category = Category::where(
			'id',
			'=',
			$id
		)->with('channels')->first();
		
		if ($category)
		{
			$topPosters = new Collection($category->posts());
			
			$topPosters->sortByDesc(function($item)
			{
				return $item->author->posts()->count();
			});
		
			return view('core.forum.category', [
				'category' => $category,
				'top_posters' => $topPosters
			]);
		}
		else
		{
			return redirect()->to('/forum');
		}
	}

	/**
	 * Attempt to show a certain channel
	 *
	 * @param $slug
	 * @return void
	 */
	public function showChannel($slug)
	{
		if (!$slug)
		{
			return redirect()->to('/forum');
		}
		
		$channel = Channel::where(
			'slug',
			'=',
			$slug
		)->with('topics', 'category')->first();
		
		if ($channel)
		{
			if (!$channel->canView(Auth::user()))
			{
				abort(403);
			}

			return view('core.forum.channel', [
				'channel' => $channel
			]);
		}
		else
		{
			return redirect()->to('/forum');
		}
	}

	/**
	 * Attempt to show a certain thread
	 *
	 * @param string $slug
	 * @param $id
	 * @return void
	 */
	public function showThread($slug, $id)
	{
		if (!$slug)
		{
			return redirect()->to('/forum');
		}
		
		$thread = Topic::where(
			'slug',
			'=',
			$slug
		)->where(
			'id',
			'=',
			$id
		)->with('channel', 'user', 'posts')->first();
		
		if ($thread)
		{
			return view('core.forum.thread', [
				'thread' => $thread
			]);
		}
		else
		{
			return redirect()->to('/forum');
		}
	}

	/**
	 * Show the "create thread" page
	 *
	 * @param string $slug
	 * @return void
	 */
	public function showCreateThread($slug)
	{
		if (!$slug)
		{
			return redirect()->to('/forum');
		}
		
		$channel = Channel::where(
			'slug',
			'=',
			$slug
		)->first();
		
		if ($channel)
		{
			return view('core.forum.create-thread', [
				'channel' => $channel
			]);
		}
		else
		{
			return redirect()->to('/forum');
		}
	}
	
	/**
	 * Show the "full reply" page
	 *
	 * @param string $slug
	 * @param int $id
	 * @return void
	 */	 
	public function showReplyToThread($slug, $id)
	{
		if (!$slug || !$id)
		{
			return redirect()->to('/forum');
		}
		
		$thread = Topic::where(
			'slug',
			'=',
			$slug
		)->where(
			'id',
			'=',
			$id
		)->first();
		
		if ($thread)
		{
			return view('core.forum.thread-reply', [
				'thread' => $thread
			]);
		}
		else
		{
			return redirect()->to('/forum');
		}
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
			return $item->posts()->count();
		});

		return view('core.forum.top-posters', [
			'top_posters' => $topPosters->take(10),
			'totalPosts' => $totalPosts
		]);
	}
	/**
	 * Create a new forum page controller instance.
	 *
	 * @return mixed
	 */
	public function __construct()
	{
		
	}

}
