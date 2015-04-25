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

class ForumController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Forum controller
	|--------------------------------------------------------------------------
	|
	| This handles the fun stuff (creating threads, etc)
	| Do not modify this unless you know what you're doing!
	| ...because lots of people will get upset if their threads don't show up.
	|
	*/
	
	/**
	 * Attempt to create a thread
	 *
	 * @param string $slug
	 * @param Illuminate\Http\Request $request
	 * @return void
	 */
	public function postCreateThread($slug, Request $request)
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
			$title = $request->input('title');
			$body = $request->input('body');
			
			$validator = Validator::make(
				[
					'title' => $title,
					'body' => $body
				],
				[
					'title' => 'required|min:5|max:20|regex:/[A-Za-z0-9\-_!\.\s]/|unique:topics,title,NULL,id,channel_id,' . $channel->id,
					'body' => 'required|min:20|max:4500'
				],
				[
					'title.required' => 'A title is required.',
					'title.min' => 'Thread titles must be at least 5 characters long.',
					'title.max' => 'Thread titles can be up to 20 characters long.',
					'title.unique' => 'A thread with this title already exists in this channel.',
					'title.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z (uppercase or lower), 0 through 9, - (dash), _ (underscore), !, . (period), and a space.',
					'body.required' => 'A message is required.',
					'body.min' => 'Messages must be at least 20 characters long.',
					'body.max' => 'Messages can be up to 4500 characters long.'
				]
			);
			
			if ($validator->passes())
			{
				$thread = Topic::create([
					'title' => $title,
					'slug' => str_slug($title, '-'),
					'user_id' => Auth::id(),
					'channel_id' => $channel->id,
					'locked' => 0,
					'pinned' => 0
				]);
				
				$post = Post::create([
					'topic_id' => $thread->id,
					'user_id' => Auth::id(),
					'content' => $body
				]);
				
				return redirect('/forum/topic/' . $thread->slug . '.' . $thread->id);
			}
			else
			{
				return redirect()->to('/forum/channel/' . $channel->slug . '/create-thread')->withErrors($validator->messages())->withInput();
			}
		}
		else
		{
			return redirect()->to('/forum');
		}
	}

	/**
	 * Attempt to quick-reply to a thread
	 *
	 * @param string $slug
	 * @param Illuminate\Http\Request $request
	 * @return void
	 */	
	public function postQuickReplyToThread($slug, $id, Request $request)
	{	
		if (!$slug || !$id)
		{
			return redirect()->to('/forum');
		}
		
		$thread = Topic::where(
			'slug',
			'=',
			$slug
		)->orWhere(
			'id',
			'=',
			$id
		)->first();
		
		if ($thread)
		{
			$body = $request->input('body');
			
			$validator = Validator::make(
				[
					'body' => $body
				],
				[
					'body' => 'required|min:20|max:4500'
				],
				[
					'body.required' => 'A message is required.',
					'body.min' => 'Messages must be at least 20 characters long.',
					'body.max' => 'Messages can be up to 4500 characters long.'
				]
			);
			
			if ($validator->passes())
			{
				$post = Post::create([
					'topic_id' => $thread->id,
					'user_id' => Auth::id(),
					'content' => $body
				]);
				
				return redirect('/forum/topic/' . $thread->slug . '.' . $thread->id . ($thread->postsPaginated->hasPages() ? '?page=' . $thread->postsPaginated->lastPage() : ''));
			}
			else
			{
				return redirect()->to('/forum/topic/' . $thread->slug . '.' . $thread->id)->withErrors($validator->messages())->withInput();
			}			
		}		
		else
		{
			return redirect()->to('/forum');
		}
	}

	/**
	 * Attempt to reply to a thread
	 *
	 * @param string $slug
	 * @param Illuminate\Http\Request $request
	 * @return void
	 */	
	public function postReplyToThread($slug, $id, Request $request)
	{	
		if (!$slug || !$id)
		{
			return redirect()->to('/forum');
		}
		
		$thread = Topic::where(
			'slug',
			'=',
			$slug
		)->orWhere(
			'id',
			'=',
			$id
		)->first();
		
		if ($thread)
		{
			$body = $request->input('body');
			
			$validator = Validator::make(
				[
					'body' => $body
				],
				[
					'body' => 'required|min:20|max:4500'
				],
				[
					'body.required' => 'A message is required.',
					'body.min' => 'Messages must be at least 20 characters long.',
					'body.max' => 'Messages can be up to 4500 characters long.'
				]
			);
			
			if ($validator->passes())
			{
				$post = Post::create([
					'topic_id' => $thread->id,
					'user_id' => Auth::id(),
					'content' => $body
				]);

				if ($post->topic->user->id != Auth::id())
				{
					$post->topic->user->notifications()->create(array(
						'subject_id' => $post->id,
						'subject_type' => get_class($post),
						'name' => 'thread_replied',
						'user_id' => $post->topic->user->id,
						'sender_id' => $post->user->id
					));
				}
				
				return redirect('/forum/topic/' . $thread->slug . '.' . $thread->id . ($thread->postsPaginated->hasPages() ? '?page=' . $thread->postsPaginated->lastPage() : ''));
			}
			else
			{
				return redirect()->to($thread->showReplyRoute)->withErrors($validator->messages())->withInput();
			}			
		}		
		else
		{
			return redirect()->to('/forum');
		}
	}
		 
	/**
	 * Create a new forum page controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

}
