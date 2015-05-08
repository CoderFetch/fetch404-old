<?php namespace App\Http\Controllers\Messaging;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Validator;
use View;

use App\Http\Controllers\Controller;

class MessagesPostManagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('confirmed');
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