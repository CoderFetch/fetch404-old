<?php namespace App\Http\Controllers;

use App\News;
use App\Tag;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/*
	 * The Tag instance
	 *
	 * @type App\Tag
	 */
	private $tag;

	/*
	 * The News instance
	 *
	 * @type App\News
	 */
	private $news;

	/**
	 * Create a new controller instance.
	 *
	 * @param News $news
	 * @param Tag $tag
	 *
	 * @return mixed
	 */
	public function __construct(News $news, Tag $tag)
	{
		$this->news = $news;
		$this->tag = $tag;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('core.index', ['news' => $this->news->orderBy('created_at', 'desc')]);
	}

}
