<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {

	//
    protected $table = 'likes';
    protected $fillable = ['subject_id', 'subject_type', 'user_id', 'liked_user_id'];

    /**
     * Get the user that liked whatever this is.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User');
    }

    /**
     * Get the owner of the liked content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function likedUser()
    {
        return $this->belongsTo('Fetch404\Core\Models\User', 'liked_user_id');
    }

    /**
     * Get the subject of this "like".
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get the array index of this "like" (in the likes array for the subject)
     *
     * @return integer
     */
    public function arrayIndex()
    {
        $likes = $this->subject->likes;

        foreach($likes as $i => $l)
        {
            if ($l->id == $this->id) return $i;
        }
    }
}
