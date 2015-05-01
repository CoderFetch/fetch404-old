<?php namespace App\Traits;

use App\User;

trait LikeableTrait {

    /**
     * Get all the "likes" for this post.
     *
     * @return mixed
     */
    public function likes()
    {
        return $this->hasMany(
            'App\Like', 'subject_id'
        )->where(
            'subject_type', '=', get_class($this)
        );
    }

    /**
     * Check to see if this object is liked by a user.
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedBy(User $user)
    {
        return in_array(
            $user->getId(),
            $this->likes()->lists('user_id')
        );
    }

    /**
     * Attempt to "like" this object.
     *
     * @param User $user
     * @return void
     */
    public function like(User $user)
    {
        if (is_null($user)) return;
        if ($this->isLikedBy($user)) return;

        return $this->likes()->create(array(
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'user_id' => $user->getId()
        ));
    }

    /**
     * Attempt to "dislike" this object.
     *
     * @param User $user
     * @return void
     */
    public function dislike(User $user)
    {
        if (is_null($user)) return;
        if (!$this->isLikedBy($user)) return;

        return $this->likes()
             ->where('user_id', '=', $user->getId())
             ->delete();
    }
}