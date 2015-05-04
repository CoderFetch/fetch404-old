<?php namespace App;

use App\Traits\CanBeReported;
use App\Traits\LikeableTrait;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BasePost;

class Post extends Model {
	//
	use BasePost, LikeableTrait, CanBeReported;

	protected $table = 'posts';
	protected $fillable = ['topic_id', 'user_id', 'content'];
}	
