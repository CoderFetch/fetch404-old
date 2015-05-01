<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {

	//
    protected $table = 'likes';
    protected $fillable = ['subject_id', 'subject_type', 'user_id'];

    /**
     * Get the user that liked whatever this is.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
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
}
