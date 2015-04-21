<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use URL;

class Channel extends Model {

	//
	protected $table = 'channels';
	protected $fillable = ['name', 'description', 'weight', 'category_id', 'slug'];
	
	public function category()
	{
		return $this->belongsTo('App\Category');
	}
	
	public function topics()
	{
		return $this->hasMany('App\Topic', 'channel_id')->with('user', 'channel', 'posts');
	}
	
	public function posts()
	{
		return $this->hasManyThrough('App\Post', 'App\Topic');
	}
		
	public function getRouteAttribute()
	{
		return URL::to('/forum/channel/' . $this->slug);
	}
	
	public function getCanCreateThreadAttribute()
	{
		return Auth::check();
	}
}
