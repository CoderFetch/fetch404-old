<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

use App\Channel;
use App\Http\Requests\Installer\InstallRequest;
use App\Post;
use App\Topic;
use App\User;

use Cmgmyr\Messenger\Models\Thread as Conversation;
use Cmgmyr\Messenger\Models\Message as ConversationMessage;

class InstallController extends Controller
{
    private $user;

    private $channel;

    private $topic;

    private $post;

    private $conversation;

    private $conversationMessage;

    /**
     * Initializer.
     *
     * @param User $user
     * @param Channel $channel
     * @param Topic $topic
     * @param Post $post
     * @param Conversation $conversation
     * @param ConversationMessage $conversationMessage
     */
    public function __construct(
        User $user, Channel $channel,
        Topic $topic, Post $post,
        Conversation $conversation,
        ConversationMessage $conversationMessage
    )
    {
        $this->user = $user;
        $this->channel = $channel;
        $this->topic = $topic;
        $this->post = $post;
        $this->conversation = $conversation;
        $this->conversationMessage = $conversationMessage;
    }

    public function show()
    {
        return view('core.installer.index');
    }

    public function install(InstallRequest $request)
    {
        $site_settings = $request->only('forumTitle', 'forumDesc');
        $admin_settings = $request->only('username', 'email', 'password');

        dd($site_settings);

        // Surround everything with try/catch, in case something weird happens
        try
        {

        }
        catch (\Exception $ex)
        {
            if ($ex instanceof \PDOException)
            {
                return view('core.installer.errors.pdoexception');
            }
        }
    }
}