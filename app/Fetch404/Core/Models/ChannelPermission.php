<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelPermission extends Model {
    //

    protected $table = 'channel_permission';
    protected $fillable = ['permission_id', 'channel_id', 'role_id'];

    public function channel()
    {
        return $this->belongsTo('Fetch404\Core\Models\Channel');
    }

    public function role()
    {
        return $this->belongsTo('Fetch404\Core\Models\Role');
    }

    public function permission()
    {
        return $this->belongsTo('Fetch404\Core\Models\Permission');
    }
}	
