<?php namespace Fetch404\Core\Traits;

use Fetch404\Core\Models\User;

trait FollowableTrait {

    /**
     * Get the list of users that the current user follows.
     *
     * @return mixed
     */
    public function followedUsers()
    {
        return $this->belongsToMany(static::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    /**
     * Get the list of users who follow the current user.
     *
     * @return mixed
     */
    public function followers()
    {
        return $this->belongsToMany(static::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    /**
     * Return an array of user IDs that the current user is following.
     *
     * @return array
     */
    public function followingIds()
    {
        return $this->followedUsers()->lists('followed_id');
    }

    /**
     * Determine if current user follows another user.
     *
     * @param User $otherUser
     * @return bool
     */
    public function isFollowedBy(User $otherUser)
    {
        $idsWhoOtherUserFollows = $otherUser->followedUsers()->lists('followed_id');
        return in_array($this->id, $idsWhoOtherUserFollows);
    }

    /**
     * Is the current user following another user?
     * Basically the inverse of isFollowedBy.
     *
     * @param User $otherUser
     * @return bool
     */
    public function isFollowing(User $otherUser)
    {
        $idsWhoIFollow = $this->followedUsers()->lists('followed_id');
        return in_array($otherUser->id, $idsWhoIFollow);
    }
}