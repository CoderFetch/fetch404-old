<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPermission extends Model {
    //

    protected $table = 'category_permission';
    protected $fillable = ['permission_id', 'category_id', 'role_id'];

    public function category()
    {
        return $this->belongsTo('Fetch404\Core\Models\Category');
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
