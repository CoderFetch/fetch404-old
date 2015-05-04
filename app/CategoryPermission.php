<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPermission extends Model {
    //

    protected $table = 'category_permission';
    protected $fillable = ['permission_id', 'category_id', 'role_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
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
