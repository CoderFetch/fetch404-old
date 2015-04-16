<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model {

	//
	protected $table = 'ticket_message';

	protected $fillable = ['ticket_id', 'user_id', 'content'];

	public function ticket()
	{
		return $this->belongsTo('App\Ticket');
	}
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}	
}
