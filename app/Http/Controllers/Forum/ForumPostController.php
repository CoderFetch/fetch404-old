<?php namespace App\Http\Controllers\Forum;

use App\Http\Controllers\ForumController;
use App\Post;

class ForumPostController extends ForumController
{
    /*
     * The Post instance.
     * @type Post
     */
    protected $post;

    /*
     * Create a new forum user controller instance.
     *
     * @param Post $post
     * @type mixed
     */
    public function __construct(Post $post)
    {
        $this->post = $post;

        $this->middleware('auth');
        $this->middleware('confirmed');
        $this->middleware('csrf');
    }

    /*
     * Attempt to "like" a post.
     *
     * @return void
     */
    public function likePost()
    {

    }
}