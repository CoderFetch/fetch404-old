<?php namespace App\Traits;

trait HasProfilePosts {

    /**
     * Get the list of profile posts on this user's profile.
     *
     * @return mixed
     */
    public function profilePosts()
    {
        return $this->hasMany('App\ProfilePost', 'to_user_id')->latest('created_at');
    }

    /**
     * Get the list of profile posts that this user created.
     *
     * @return mixed
     */
    public function ownProfilePosts()
    {
        return $this->hasMany('App\ProfilePost', 'from_user_id');
    }

}