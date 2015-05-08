<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

use Fetch404\Core\Traits\CanBeReported;
use Fetch404\Core\Traits\LikeableTrait;
use Fetch404\Core\Traits\BasePost;

class Post extends Model {
	//
	use BasePost, LikeableTrait, CanBeReported;

	protected $table = 'posts';
	protected $fillable = ['topic_id', 'user_id', 'content'];
}	
