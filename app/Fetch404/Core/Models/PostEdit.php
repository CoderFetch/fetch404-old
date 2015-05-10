<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class PostEdit extends Model {

	//
    protected $table = 'post_edits';
    protected $fillable = ['post_id', 'user_id', 'old_content', 'new_content'];

    /**
     * Get the user associated with this edit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User');
    }

    /**
     * Get the post associated with this edit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('Fetch404\Core\Models\Post');
    }
}
