<?php namespace Fetch404\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	//
    protected $table = 'notifications';
    protected $fillable = ['subject_id', 'subject_type', 'name', 'user_id', 'sender_id', 'is_read'];

    /**
     * Get the user that received this notification.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User');
    }

    /**
     * Get the user that the notification came from. (post author, etc)
     *
     * @return User
     */
    public function sender()
    {
        return $this->belongsTo('Fetch404\Core\Models\User', 'sender_id');
    }

    /**
     * Get the subject of the notification.
     *
     * @return mixed
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Is this notification "read"? (we assume that it is after 6 hours, or if the user clicked open the notification menu)
     *
     * @param $query
     * @param $user_id
     * @return mixed
     */
    public function scopeUnread($query, $user_id)
    {
        $date = new Carbon;

        $date->subHours(6);

        return $query->where(
            'user_id', '=', $user_id
        )->where(
            'is_read', '=', 0
        )->orWhere(
            'created_at', '>', $date->toDateTimeString()
        )->get();
    }
}
