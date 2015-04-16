<?php namespace App\Http\Requests\Searching;

# Illuminate stuff
use Illuminate\Foundation\Http\FormRequest;

# Facades
use Auth;
use Response;

class SearchRequest extends FormRequest
{
    public function rules()
    {
        return [
            'query' => 'required'
        ];
    }

    public function authorize()
    {
        // Allow all users to perform this action. Searching doesn't have to do with authentication.
        return true;
    }

//    public function forbiddenResponse()
//    {
//        // Optionally, send a custom response on authorize failure
//        // (default is to just redirect to initial page with errors)
//        //
//        // Can return a response, a view, a redirect, or whatever else
//        // return Response::make('Permission denied foo!', 403);
//        return response()->make(view('core.errors.403'), 403);
//    }
}