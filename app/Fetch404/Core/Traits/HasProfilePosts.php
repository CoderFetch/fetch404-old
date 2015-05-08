<?php namespace Fetch404\Core\Traits;

trait HasProfilePosts {

    /**
     * Get the list of profile posts on this user's profile.
     *
     * @return mixed
     */
    public function profilePosts()
    {
        return $this->hasMany('Fetch404\Core\Models\ProfilePost', 'to_user_id')->latest('created_at');
    }

    /**
     * Get the list of profile posts that this user created.
     *
     * @return mixed
     */
    public function ownProfilePosts()
    {
        return $this->hasMany('Fetch404\Core\Models\ProfilePost', 'from_user_id');
    }

}