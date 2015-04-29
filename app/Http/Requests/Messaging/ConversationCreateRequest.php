<?php namespace App\Http\Requests\Messaging;

# Illuminate stuff
use Illuminate\Foundation\Http\FormRequest;

# Facades
use Auth;
use Response;

class ConversationCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
			'subject' => 'required|min:5|max:20|regex:/[A-Za-z0-9\-_!\.\s]/',
			'message' => 'required|min:20|max:4500',
			'recipients' => 'required'
        ];
    }

    public function authorize()
    {
        // Only allow logged in users
        // return \Auth::check();
        // Allows all users in
        return Auth::check() && Auth::user()->isConfirmed();
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
    
//     public function response()
//     {
//         // If you want to customize what happens on a failed validation,
//         // override this method.
//         // See what it does natively here: 
//         // https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Http/FormRequest.php
//     }
}