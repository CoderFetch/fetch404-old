<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use URL;

class Category extends Model {

	//
	protected $table = 'categories';
	protected $fillable = ['name', 'description', 'weight', 'slug'];
	
	public function channels()
	{
		return $this->hasMany('App\Channel');
	}
	
	public function topics()
	{
		return $this->hasManyThrough('App\Topic', 'App\Channel');
	}
	
	public function getPosts()
	{
		$posts = array();
		
		foreach($this->topics as $topic)
		{
			foreach($topic->posts as $post)
			{
				$posts[] = $post;
			}	
		}
		
		return $posts;
	}
	
	public function getRouteAttribute()
	{
		return route('forum.get.show.forum', ['slug' => $this->slug]);
	}
}
