<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BasePost;

class Post extends Model {
	//
	use BasePost;

	protected $table = 'posts';
	protected $fillable = ['topic_id', 'user_id', 'content'];
}	
