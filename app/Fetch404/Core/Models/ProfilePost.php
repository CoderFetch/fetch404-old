<?php namespace Fetch404\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProfilePost extends Model {

	//
    protected $table = 'profile_posts';
    protected $fillable = array('to_user_id', 'from_user_id', 'body');

    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User', 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo('Fetch404\Core\Models\User', 'to_user_id');
    }

    public function formattedCreatedAt()
    {
        $now = Carbon::now();
        $now->subDays(7);

        if ($this->created_at > $now->toDateTimeString())
        {
            return $this->created_at->diffForHumans();
        }
        else
        {
            return $this->created_at->format('M j, Y');
        }

        return $this->created_at->format('M j, Y');
    }
}
