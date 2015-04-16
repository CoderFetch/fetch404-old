<?php namespace App\Http\Requests;

# Illuminate stuff
use Illuminate\Foundation\Http\FormRequest;

# Facades
use Auth;
use Response;

class TicketReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'subject' => 'required|min:5|max:30|regex:/[A-Za-z0-9\-_!\.\s]/',
            'message' => 'required|min:20|max:10000'
        ];
    }

    public function authorize()
    {
        $ticket = $this->route('ticket');

        return $ticket != null && Auth::check() && Auth::user()->isConfirmed() && ($ticket->canView == true);
    }

    public function forbiddenResponse()
    {
        // Optionally, send a custom response on authorize failure
        // (default is to just redirect to initial page with errors)
        //
        // Can return a response, a view, a redirect, or whatever else
        // return Response::make('Permission denied foo!', 403);
        return response()->make(view('core.errors.403'), 403);
    }
}