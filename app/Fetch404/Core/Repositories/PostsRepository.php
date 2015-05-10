<?php namespace Fetch404\Core\Repositories;

use Fetch404\Core\Models\Channel;
use Fetch404\Core\Models\Post;
use Fetch404\Core\Models\Topic;

class PostsRepository extends BaseRepository {

    public function __construct(Post $model)
    {
        $this->model = $model;
        $this->itemsPerPage = 10;
    }

    public function getForChannel(Channel $channel)
    {
        return $this->model
            ->whereIn('topic_id', $channel->topics()->lists('id'))
            ->get();
    }

    public function getForThread(Topic $thread)
    {
        return $this->model
            ->where('topic_id', '=', $thread->id)
            ->get();
    }
}