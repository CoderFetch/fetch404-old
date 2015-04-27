<?php namespace App\Http\Requests\Admin\Forum;

# Illuminate stuff
use Illuminate\Foundation\Http\FormRequest;

# Facades
use Auth;
use Response;

use Zizaco\Entrust\EntrustFacade as Entrust;

class UnbanUserRequest extends FormRequest
{
    protected $rules = [];

    public function rules()
    {
        return [];
    }

    public function authorize()
    {
        return Entrust::can('accessAdminPanel') && Entrust::can('unbanUsers');
    }

    public function forbiddenResponse()
    {
        // Optionally, send a custom response on authorize failure 
        // (default is to just redirect to initial page with errors)
        // 
        // Can return a response, a view, a redirect, or whatever else
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