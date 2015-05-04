<?php namespace App\Http\Requests\Admin\Forum;

# Illuminate stuff
use Illuminate\Foundation\Http\FormRequest;

# Facades
use Auth;
use Response;

use Zizaco\Entrust\EntrustFacade as Entrust;

class EditChannelRequest extends FormRequest
{
    protected $rules = [
        'name' => 'required|min:5|max:20'
    ];

    public function rules()
    {
        $rules = $this->rules;

        if ($this->input('name') != $this->route()->getParameter('channel')->name)
        {
            $rules['name'] = 'required|min:5|max:20|unique:channels';
        }

        if ($this->has('weight'))
        {
            $rules['weight'] = 'numeric';
        }

        return $rules;
    }

    public function authorize()
    {
        return Entrust::can('accessAdminPanel');
    }

    public function forbiddenResponse()
    {
        // Optionally, send a custom response on authorize failure 
        // (default is to just redirect to initial page with errors)
        // 
        // Can return a response, a view, a redirect, or whatever else
        return response()->make(view('core.errors.403'), 403);
    }

    public function messages()
    {
        return [
            'name.required' => 'A channel title is required.',
            'name.min' => 'Channel titles must be at least 5 characters long.',
            'name.max' => 'Channel titles can be up to 20 characters long.',
            'name.unique' => 'That name is in use. Try another.',
            'weight.numeric' => 'Please enter a valid number.'
        ];
    }

//     public function response()
//     {
//         // If you want to customize what happens on a failed validation,
//         // override this method.
//         // See what it does natively here: 
//         // https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Http/FormRequest.php
//     }
}