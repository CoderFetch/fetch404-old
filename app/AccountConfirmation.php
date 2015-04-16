<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountConfirmation extends Model {

	//
	protected $table = 'account_confirmations';
	
	protected $fillable = ['id', 'user_id', 'code', 'expires_at'];
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function hasExpired()
	{
		return ($this->expires_at - time()) > (3600);
	}
}
