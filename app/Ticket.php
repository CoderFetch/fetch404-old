<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TicketParticipant;

use Illuminate\Support\Facades\Auth;

class Ticket extends Model {

	//
	protected $table = 'tickets';

    protected $fillable = ['title', 'slug', 'user_id', 'locked'];

	public function messages()
	{
		return $this->hasMany('App\TicketMessage');
	}	
	
	public function participants()
	{
		return $this->hasMany('App\TicketParticipant');
	}
	
    /**
     * Returns tickets that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeForUser($query, $userId)
    {
        return $query->join('ticket_participants', 'ticket_participants.id', '=', 'ticket_participants.ticket_id')
            ->where('ticket_participants.user_id', $userId)
            ->where('ticket_participants.deleted_at', null)
            ->select('tickets.*')
            ->latest('updated_at');
    }
    
    /**
     * See if the current thread is unread by the user
     *
     * @param integer $userId
     * @return bool
     */
    public function isUnread($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            if ($this->updated_at > $participant->last_read) {
                return true;
            }
        } catch (ModelNotFoundException $e) {
            // do nothing
        }

        return false;
    }

    /**
     * Finds the participant record from a user id
     *
     * @param $userId
     * @return mixed
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getParticipantFromUser($userId)
    {
        return $this->participants()->where('user_id', $userId)->firstOrFail();
    }

    /**
     * Adds users to this ticket
     *
     * @param array $participants list of all participants
     * @return void
     */
    public function addParticipants(array $participants)
    {
        if (count($participants)) {
            foreach ($participants as $user_id) {
                TicketParticipant::firstOrCreate([
                    'user_id' => $user_id,
                    'ticket_id' => $this->id
                ]);
            }
        }
    }

    /**
     * Can the current user view this ticket?
     *
     * @return void
     */
    public function getCanViewAttribute()
    {
        if (!Auth::check()) return false;

        $participant = TicketParticipant::where(
            'user_id',
            '=',
            Auth::id()
        )->where(
            'ticket_id',
            '=',
            $this->id
        )->first();

        return Auth::check() && ($participant != null || Auth::user()->can('view_all_tickets'));
    }
}
