<?php namespace App\Http\Controllers\Tickets;

use App\Http\Requests\TicketReplyRequest;
use App\User;
use App\Ticket;
use App\TicketMessage;
use App\TicketParticipant;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Laracasts\Flash\Flash;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\TicketCreateRequest;

class TicketsController extends Controller
{
    public function __construct()
    {
       	$this->middleware('auth');
        $this->middleware('confirmed');
    }
    
    /**
     * Show all of the current user's tickets
     *
     * @return mixed
     */
    public function index()
    {
        $currentUserId = Auth::user()->id;
        $tickets = Ticket::forUser($currentUserId)->paginate(3);
        
        $ticketLinks = $tickets->render();
        
       	return view('core.tickets.index', compact('tickets', 'currentUserId', 'ticketLinks'));
    }
    
    public function create()
    {
        return view('core.tickets.create');
    }

    public function show($id)
    {
        try
        {
            $ticket = Ticket::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            Flash::error('The requested ticket does not exist.');
            return redirect('tickets');
        }

        if (!$ticket->canView)
        {
            Flash::error('You do not have permission to view this ticket.');
            return redirect('tickets');
        }

        return view('core.tickets.show', [
            'ticket' => $ticket
        ]);
    }
    public function store(TicketCreateRequest $request)
    {
        $subject = $request->input('subject');
        $message = $request->input('message');

        $ticket = Ticket::create(
            array(
                'title' => $subject,
                'slug' => str_slug($subject, '-'),
                'user_id' => Auth::user()->id,
                'locked' => 0
            )
        );

        $participant = TicketParticipant::create(
            array(
                'ticket_id' => $ticket->id,
                'user_id' => Auth::user()->id,
                'last_read' => new Carbon
            )
        );

        $message = TicketMessage::create(
            array(
                'ticket_id' => $ticket->id,
                'user_id' => Auth::user()->id,
                'content' => $message
            )
        );

        return redirect('tickets/' . $ticket->id);
    }

    /**
     * Update a ticket
     * @param TicketReplyRequest $request
     */
    public function update(TicketReplyRequest $request)
    {

    }
}