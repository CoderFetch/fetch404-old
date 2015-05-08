<?php namespace App\Http\Controllers\Forum;

use App\Http\Controllers\ForumController;
use Fetch404\Core\Models\Post;
use Illuminate\Contracts\Auth\Guard;

class ForumUserController extends ForumController
{
    /*
     * The Guard instance.
     * @type Guard
     */
    protected $guard;

    /*
     * Create a new forum user controller instance.
     *
     * @type mixed
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
        $this->middleware('auth');
        $this->middleware('confirmed');
    }

    /*
     * Show the current user's posts.
     *
     * @return void
     */
    public function showMyPosts()
    {
        $user = $this->guard->user();

        $posts = Post::where(
            'user_id',
            '=',
            $user->id
        )->orderBy(
            'created_at',
            'desc'
        )->get();

        return view('core.user.me.posts', [
            'posts' => $posts
        ]);
    }
}