<?php namespace App;

use App\Traits\HasProfilePosts;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Cmgmyr\Messenger\Traits\Messagable;
use App\Traits\BaseUser;
use App\Traits\FollowableTrait as Followable;

use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;
use URL;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract 
{
	use Authenticatable, CanResetPassword, EntrustUserTrait, Messagable, SoftDeletes, BaseUser, Followable, HasProfilePosts;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'slug', 'confirmed', 'is_banned', 'banned_until', 'last_active_desc', 'last_active'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
	
	protected $dates = ['deleted_at', 'last_active'];

}
