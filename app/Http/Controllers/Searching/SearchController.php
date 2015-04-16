<?php namespace app\Http\Controllers\Searching;

use App\Http\Controllers\Controller;
use App\Http\Requests\Searching\SearchRequest;
use App\Topic;
use App\User;
use App\Post;

class SearchController extends Controller
{
    /*
     * The User model instance
     * @var User
     */
    protected $user;

    /*
     * The Post model instance
     * @var Post
     */
    protected $post;

    /*
     * The Topic model instance
     * @var Post
     */
    protected $topic;

    /*
     * Create a new search controller index.
     * @type mixed
     */
    public function __construct(User $user, Post $post, Topic $topic)
    {
        $this->user = $user;
        $this->post = $post;
        $this->topic = $topic;
    }

    /*
     * Show the search page.
     *
     */
    public function showIndex()
    {
        return view('core.search.search');
    }

    /*
     * Search in the database for certain items
     * @param SearchRequest $request
     * @return mixed
     */
    public function search(SearchRequest $request)
    {
        $searchQuery = $request->input('query');

        $results = [
            'users' => [],
            'posts' => [],
            'topics' => []
        ];

        // Search in the topics table
        $topics = $this->topic->where('title', 'like', '%' . $searchQuery . '%')->get();
        foreach($topics as $topicResult)
        {
            $results['topics'][] = $topicResult;
        }

        // Search in the users table
        $users = $this->user->where('name', 'like', '%' . $searchQuery . '%')->get();
        foreach($users as $userResult)
        {
            $results['users'][] = $userResult;
        }

        // Search in the posts table
        $posts = $this->post->where('content', 'like', '%' . $searchQuery . '%')->get();
        foreach($posts as $postResult)
        {
            $results['posts'][] = $postResult;
        }

        $resultCount = 0;
        foreach($results as $key => $array)
        {
            $resultCount += sizeof($array);
        }

        return view('core.search.search', compact('results', 'searchQuery', 'resultCount'));
    }
}