<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ChannelPermission extends Model {
    //

    protected $table = 'channel_permission';
    protected $fillable = [];

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function permission()
    {
        return $this->belongsTo('App\Permission');
    }
}	
