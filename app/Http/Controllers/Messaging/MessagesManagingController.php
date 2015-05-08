<?php namespace App\Http\Controllers\Messaging;

use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Validator;

use App\Http\Controllers\Controller;

class MessagesManagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('confirmed');
	}

    /**
     * Delete a conversation
     *
     * @param $id
     * @return mixed
     */
    public function deleteConversation($id)
    {
        try 
        {
            $thread = Thread::findOrFail($id);
            $thread->getParticipantFromUser(Auth::id());
        } 
        catch (ModelNotFoundException $e) 
        {
            Flash::error('The requested conversation does not exist.');
            return redirect('conversations');
        }
        
        if (!$thread->canManage)
        {
            Flash::error('You do not have permission to do this.');
            return redirect('conversations');        	
        }
        
        $thread->messages()->delete();
        $thread->participants()->delete();
        $thread->delete();
        
        Flash::success('Conversation deleted.');
        
        return redirect('conversations');
    }

    /**
     * Leave a conversation
     *
     * @param $id
     * @return void
     */
    public function leaveConversation($id)
    {
        try
        {
            $thread = Thread::findOrFail($id);
            $thread->getParticipantFromUser(Auth::id());
        }
        catch (ModelNotFoundException $e)
        {
            Flash::error('The requested conversation does not exist.');
            return redirect('conversations');
        }

        $participant = $thread->getParticipantFromUser(Auth::id());

        $user = $participant->user;

        $thread->messages()->where('user_id', '=', $user->id)->delete();

        $participant->delete();

        if ($thread->participants()->count() == 0)
        {
            $thread->delete();
        }

        Flash::success('You have left the conversation.');

        return redirect('conversations');
    }

    public function toJSON(Thread $thread)
    {
    	return response()->json($thread->participants);
    }
    
}