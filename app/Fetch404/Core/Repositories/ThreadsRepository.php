<?php namespace Fetch404\Core\Repositories;

use Fetch404\Core\Models\Topic;

class ThreadsRepository extends BaseRepository {

    public function __construct(Topic $model)
    {
        $this->model = $model;
        $this->itemsPerPage = 15;
    }

    public function getRecent($where = array())
    {
        return $this->model
            ->with('channel', 'posts')
            ->recent()
            ->where($where)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function getNewForUser($userID = 0, $where = array())
    {
        $threads = $this->getRecent($where);

        // If we have a user ID, filter the threads appropriately
        if ($userID)
        {
            $threads = $threads->filter(function($thread)
            {
                return $thread->userReadStatus;
            });
        }

        // Filter the threads according to the user's permissions
        $threads = $threads->filter(function($thread)
        {
            return $thread->canView;
        });

        return $threads;
    }
}