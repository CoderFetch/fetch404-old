<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model {

    //
    protected $table = 'user_settings';
    protected $fillable = ['user_id', 'name', 'value'];

    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User');
    }
}
