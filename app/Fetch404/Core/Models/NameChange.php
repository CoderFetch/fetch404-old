<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class NameChange extends Model {

	//
	
	protected $table = 'name_changes';
	
	protected $fillable = ['user_id', 'old_name', 'new_name'];
	
	/*
	 * Get the user associated with this name change.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('Fetch404\Core\Models\User');
	}
}
