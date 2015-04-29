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
use View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesUserManagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('confirmed');
	}
	
	/**
     * Kick a user from a conversation.
     *
     * @return mixed
     */
    public function kickUser($conversation, $user)
    {
        if (!$user)
        {
            Flash::error('User not found');
            return redirect('conversations');        	
        }
        
        try 
        {
            $conversation->getParticipantFromUser(Auth::id());
        } 
        catch (ModelNotFoundException $e) 
        {
            Flash::error('The requested conversation does not exist.');
            return redirect('conversations');
        }
        
        if (!$conversation->canManage)
        {
            Flash::error('You do not have permission to do this.');
            return redirect('conversations');        	
        }
        
    	$participant = Participant::where(
    		'thread_id',
    		'=',
    		$conversation->id
    	)->where(
    		'user_id',
    		'=',
    		$user->id
    	)->first();
    	
    	if (!$participant)
    	{
    		Flash::error('User not found');
    		return redirect('conversations/' . $conversation->id);
    	}
    	
    	$thread = $participant->thread;
    	
    	if ($user->name == Auth::user()->name)
    	{
    		Flash::error('You can\'t kick yourself!');
    		return redirect('conversations/' . $conversation->id);
    	}	
    	
    	$firstParticipant = $conversation->participants()->first();
    	
    	if ($user->name == $firstParticipant->name)
    	{
    		Flash::error('You can\'t kick the person who started the conversation.');
    		return redirect('conversations/' . $conversation->id);
    	}
        
        $conversation->messages()->where('user_id', '=', $user->id)->delete();
        
        $participant->delete();
        
        Flash::success('Kicked user "' . $user->name . '" from the conversation');
        
        if ($conversation->participants()->count() > 0)
        {
        	return redirect('conversations/' . $conversation->id);
        }
        else
        {
        	$conversation->messages()->delete();
        	$conversation->participants()->delete();
        	
        	$conversation->delete();
        	
        	return redirect('conversations/');
        }
        
        return redirect('conversations/');
    }
}