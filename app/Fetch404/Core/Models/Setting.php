<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

	//
    protected $fillable = ['name', 'value'];
    protected $table = 'settings';
}
