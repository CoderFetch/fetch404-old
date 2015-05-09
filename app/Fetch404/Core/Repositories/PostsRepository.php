<?php namespace Fetch404\Core\Repositories;

use Fetch404\Core\Models\Post;
use Fetch404\Core\Models\Topic;

class PostsRepository extends BaseRepository {

    public function __construct(Post $model)
    {
        $this->model = $model;
        $this->itemsPerPage = 10;
    }

    public function getForThread(Topic $thread)
    {
        return $this->model
            ->where('topic_id', '=', $thread->id)
            ->get();
    }
}