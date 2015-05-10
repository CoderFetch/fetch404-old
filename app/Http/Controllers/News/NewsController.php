<?php namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewsCreateRequest;

use Fetch404\Core\Models\News;
use Fetch404\Core\Models\Tag;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    private $news;
    private $tag;

    /*
     * Create a new news controller instance.
     *
     * @param News $news
     * @type mixed
     */
    public function __construct(News $news, Tag $tag)
    {
        $this->middleware('auth', ['except' => ['showPost']]);
        $this->news = $news;
        $this->tag = $tag;
    }

    /*
     * Show the main news index.
     *
     * @return void
     */
    public function showIndex()
    {
        return view('core.news.index', ['news' => $this->news->all()]);
    }

    /*
     * Show the create news post page.
     *
     * @return void
     */
    public function showCreate()
    {
        return view('core.news.create', ['tags' => $this->tag->lists('name', 'id')]);
    }

    /*
     * Show a certain news post.
     *
     * @return void
     */
    public function showPost(News $news)
    {
        return view('core.news.show', ['news' => $news]);
    }

    /*
     * Attempt to create a new news post.
     *
     * @param NewsCreateRequest $request
     */
    public function store(NewsCreateRequest $request)
    {
        $user = Auth::user();
        $news = $user->news()->create($request->except('tags'));

        $news->tags()->sync(($request->has('tags') ? $request->input('tags') : []));

        return redirect(route('news.show', ['news' => $news->id]));
    }
}