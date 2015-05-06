<?php namespace App\Traits;

use App\User;
use Illuminate\Support\Facades\Auth;

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
     * @param User $ownerOfContent
     * @return void
     */
    public function like(User $user, User $ownerOfContent)
    {
        if (is_null($user)) return;
        if (is_null($ownerOfContent)) return;
        if ($this->isLikedBy($user)) return;

        return $this->likes()->create(array(
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'user_id' => $user->getId(),
            'liked_user_id' => $ownerOfContent->getId()
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

    /**
     * Generate a list of profile links, representing the users who liked this object.
     *
     * @return string
     */
    public function getLikeNames()
    {
        $names = array();

        if (Auth::check())
        {
            $this->likes = $this->likes->sortBy(function($item)
            {
                if ($item->user->id == Auth::id())
                {
                    return -1;
                }

                return $item->arrayIndex();
            });

            foreach($this->likes as $like)
            {
                if ($like->user->id == Auth::id()) $names[] = "You";
                if ($like->user->id != Auth::id())
                {
                    $names[] = link_to_route('profile.get.show', $like->user->name, [$like->user->slug, $like->user->id]);
                }
            }
        }
        else
        {
            foreach($this->likes as $like)
            {
                $names[] = link_to_route('profile.get.show', $like->user->name, [$like->user->slug, $like->user->id]);
            }
        }

        // If there's more than one name, construct the list so that it has the word "and" in it.
        if (count($names) > 1) {
            // If there are more than 3 names, chop off everything after the first 3 and replace them with a
            // "x others" link.
            if (count($names) > 3) {
                $otherNames = array_splice($names, 3);
                $lastName = "<a href='#' data-toggle='modal' data-target='#likes-" . ($this->likes()->first()->subject->id) . "'>".sprintf("%s others", count($otherNames))."</a>";
            } else {
                $lastName = array_pop($names);
            }
            $members = sprintf("%s like this.", sprintf("%s and %s", implode(", ", $names), $lastName));
        }
        // If there's only one name, we don't need to do anything grammatically fancy.
        elseif (count($names)) {
            $members = sprintf("%s like%s this.", $names[0], ($names[0] == "You" ? "" : "s"));
        }
        else {
            $members = "";
        }
        return $members;
    }

    /**
     * Generate a set of Bootstrap "list-group-item"s to show in a likes list.
     *
     * @param boolean $addUL
     * @return string
     */
    public function genLikesModalHTML($addUL = false)
    {
        $html = '';

        if ($addUL == true) $html .= '<ul class="list-group">';

        if (Auth::check())
        {
            $this->likes = $this->likes->sortBy(function($item)
            {
                if ($item->user->id == Auth::id())
                {
                    return -1;
                }

                return $item->arrayIndex();
            });

            foreach($this->likes as $like)
            {
                $html .= '<li class="list-group-item"><img src="' . $like->user->getAvatarURL(20) . '" height="20" width="15" />&nbsp;' .
                    link_to_route(
                        'profile.get.show', $like->user->name, [$like->user->slug, $like->user->id]
                    ) .
                    '</li>';
            }
        }
        else
        {
            foreach($this->likes as $like)
            {
                $html .= '<li class="list-group-item"><img src="' . $like->user->getAvatarURL(15) . '" height="15" width="10" />' .
                    link_to_route(
                        'profile.get.show', $like->user->name, [$like->user->slug, $like->user->id]
                    ) .
                    '</li>';
            }
        }

        if ($addUL == true) $html .= '</ul>';

        return $html;
    }
}