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

class MessagesPostManagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	/**
     * Delete a conversation message.
     *
     * @return mixed
     */
    public function deletePost($conversation, $message)
    {
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
        
    }
}