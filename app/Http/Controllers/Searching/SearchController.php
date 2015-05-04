<?php namespace App\Http\Controllers\Searching;

use App\Http\Controllers\Controller;
use App\Http\Requests\Searching\SearchRequest;
use App\Report;
use App\Topic;
use App\User;
use App\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade as Entrust;

use Cmgmyr\Messenger\Models\Thread as Conversation;

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
     * The Report model instance
     * @var Report
     */
    protected $report;

    /*
     * The Conversation model instance
     * @var Conversation
     */
    protected $conversation;

    /*
     * Create a new search controller instance.
     * @type mixed
     */
    public function __construct(User $user, Post $post, Topic $topic, Report $report, Conversation $conversation)
    {
        $this->user = $user;
        $this->post = $post;
        $this->topic = $topic;
        $this->report = $report;
        $this->conversation = $conversation;
    }

    /**
     * Show the search page.
     *
     * @return void
     */
    public function showIndex()
    {
        return view('core.search.search');
    }

    /**
     * Search in the database for certain items
     *
     * @param SearchRequest $request
     * @return mixed
     */
    public function search(SearchRequest $request)
    {
        $searchQuery = $request->input('query');

        $resultsArray = [];

        // Search in the topics table
        $topics = $this->topic->where('title', 'like', '%' . $searchQuery . '%')->get();
        foreach($topics as $topicResult)
        {
            $resultsArray[] = $topicResult;
        }

        // Search in the users table
        $users = $this->user->where('name', 'like', '%' . $searchQuery . '%')->get();
        foreach($users as $userResult)
        {
            $resultsArray[] = $userResult;
        }

        // Search in the posts table
        $posts = $this->post->where('content', 'like', '%' . $searchQuery . '%')->get();
        foreach($posts as $postResult)
        {
            $resultsArray[] = $postResult;
        }

        // Search for reports
        $reports = $this->report->where('reason', 'like', '%' . $searchQuery . '%')->get();
        foreach($reports as $reportResult)
        {
            $resultsArray[] = $reportResult;
        }

        // Search for conversations
        $conversations = $this->conversation->where('subject', 'like', '%' . $searchQuery . '%')->get();
        foreach($conversations as $conv)
        {
            $resultsArray[] = $conv;
        }

        $results = Collection::make($resultsArray);

        $results = $results->filter(function($item)
        {
            if ($item instanceof User)
            {
                return !$item->isBanned() and $item->isConfirmed();
            }

            if ($item instanceof Topic)
            {
                return $item->canView;
            }

            if ($item instanceof Post)
            {
                return $item->topic->canView;
            }

            if ($item instanceof Report)
            {
                return Entrust::can('viewReports');
            }

            if ($item instanceof Conversation)
            {
                return (
                    Auth::check()
                    &&
                    in_array(Auth::id(), $item->participantsUserIds())
                );
            }
        });

        $results = $results->sortBy(function($item)
        {
            if ($item instanceof Conversation)
            {
                return 'conversation_' . mb_strtolower($item->subject);
            }

            if ($item instanceof User)
            {
                return 'user_' . mb_strtolower($item->name);
            }

            if ($item instanceof Topic)
            {
                return sprintf('topic_%-12s%s', mb_strtolower($item->title), $item->posts()->count());
            }

            if ($item instanceof Post)
            {
                if (($item->updated_at == null || $item->updated_at->toDateTimeString() <= $item->created_at->toDateTimeString()))
                {
                    return sprintf('post_%s%-12s', mb_strtolower($item->content), $item->created_at);
                }

                return sprintf('post_%s%-12s', mb_strtolower($item->content), $item->updated_at);
            }

            if ($item instanceof Report)
            {
                return sprintf('report_%-12s', mb_strtolower($item->reason));
            }

        });

        return view('core.search.search', compact('results', 'searchQuery'));
    }
}