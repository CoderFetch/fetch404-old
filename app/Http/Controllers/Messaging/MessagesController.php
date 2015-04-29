<?php namespace App\Http\Controllers\Messaging;

use App\Http\Requests\Messaging\ConversationCreateRequest;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    public function __construct()
    {
       	$this->middleware('auth');
		$this->middleware('confirmed');
    }
    
    /**
     * Show all of the message threads to the user
     *
     * @return mixed
     */
    public function index()
    {
        $currentUserId = Auth::user()->id;
        // All threads, ignore deleted/archived participants
        $threads = Thread::forUser($currentUserId)->paginate(3);
        
        $threadLinks = $threads->render();
        // All threads that user is participating in
        // $threads = Thread::forUser($currentUserId);
        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages($currentUserId);
       	return view('core.conversations.index', compact('threads', 'currentUserId', 'threadLinks'));
    }
    /**
     * Shows a message thread
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
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
        
        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();
        // don't show the current user in list
        $userId = Auth::user()->id;
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
        $thread->markAsRead($userId);
        
        return view('core.conversations.show', compact('thread', 'users'));
    }
    
    /**
     * Creates a new message thread
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $names = User::where('id', '!=', Auth::id())->lists('name', 'id');
        
        return view('core.conversations.create', compact('users', 'names'));
    }
    /**
     * Stores a new message thread
     *
     * @param ConversationCreateRequest $request
     * @return mixed
     */
    public function store(ConversationCreateRequest $request)
    {
    	$subject = $request->input('subject');
    	$message = $request->input('message');
    	$recipients = $request->input('recipients');
    	
    	$conversation = Auth::user()
    		->threads()
    		->create($request->only('subject'));
    	
    	$message = Auth::user()->messages()->create(
    		[
    			'thread_id' => $conversation->id,
    			'user_id' => Auth::user()->id,
    			'body' => $message
    		]
    	);
    	
    	$conversation->addParticipants($recipients);
    	
    	return redirect('conversations/' . $conversation->id);
    }
    /**
     * Adds a new message to a current thread
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
		$body = $request->input('message');
		
		$validator = Validator::make(
			[
				'message' => $body
			],
			[
				'message' => 'required|min:20|max:4500',
			],
			[
				'message.required' => 'A message is required.',
				'message.min' => 'Messages must be at least 20 characters long.',
				'message.max' => 'Messages can be up to 4500 characters long.'
			]
		);
		
		if ($validator->passes())
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
	
			$thread->activateAllParticipants();
			// Message
			Message::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::id(),
					'body'      => Input::get('message'),
				]
			);
			// Add replier as a participant
			$participant = Participant::firstOrCreate(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id
				]
			);
			
			$participant->last_read = new Carbon;
			$participant->save();
			
			foreach($thread->participants as $threadParticipant)
			{
				if ($threadParticipant->user_id != Auth::id())
				{
					$threadParticipant->last_read = null;
					$threadParticipant->save();
				}
			}
	
			return redirect('conversations/' . $id . ($thread->messagesPaginated->hasPages() ? '?page=' . $thread->lastPage : ''));
		}
		else
		{
			return redirect('conversations/' . $id)->withErrors($validator->messages())->withInput();
		}
    }

}