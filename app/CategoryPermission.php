<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPermission extends Model {
    //

    protected $table = 'category_permission';
    protected $fillable = [];

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
