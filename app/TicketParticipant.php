<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketParticipant extends Model {

	//
	protected $table = 'ticket_participants';

    protected $fillable = ['ticket_id', 'user_id', 'last_read'];
    /**
     * Ticket relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
