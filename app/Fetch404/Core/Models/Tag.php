<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	//
    protected $table = 'tags';
    protected $fillable = ['name'];

    public function news()
    {
        return $this->belongsTo('Fetch404\Core\Models\News');
    }
}
