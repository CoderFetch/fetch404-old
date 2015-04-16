<?php namespace App\Http\Controllers\Messaging;

use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesManagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	/**
     * Delete a conversation
     *
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
    
    public function toJSON(Thread $thread)
    {
    	return response()->json($thread->participants);
    }
    
}