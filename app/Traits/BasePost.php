<?php namespace App\Traits;

use Carbon\Carbon;

trait BasePost {
    /*
     * Get the user associated with this post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /*
     * Get the topic associated with this post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo('App\Topic');
    }

    public function formattedCreatedAt()
    {
        $now = Carbon::now();
        $now->subDays(7);

        if ($this->created_at > $now->toDateTimeString())
        {
            return $this->created_at->diffForHumans();
        }
        else
        {
            return $this->created_at->format('M j, Y');
        }

        return $this->created_at->format('M j, Y');
    }

    /*
     * Get the page number for this post.
     *
     * @return integer
     */
    public function getPageNumberAttribute()
    {
        return ceil(($this->getArrayIndex() +1)/config('forumsettings.threads.postsPerPage', 5));
    }

    /*
     * Get the generated route for this post.
     *
     * @return string
     */
    public function getRouteAttribute()
    {
        $url = route('forum.get.show.thread', ['slug' => $this->topic->slug, 'id' => $this->topic->id]);

        if ($this->topic->postsPaginated->hasPages())
        {
            $url .= '?page=' . $this->pageNumber;
        }

        $url .= '#post-' . $this->id;

        return $url;
    }

    /*
     * Get the array index for this post (in the related thread's posts array)
     *
     * @return integer
     */
    public function getArrayIndex()
    {
        $posts = $this->topic->posts;

        foreach($posts as $i => $post)
        {
            if ($post->id == $this->id && $post->topic->id == $this->topic->id)
            {
                return $i;
            }
        }

        return 0; //first element
    }
}