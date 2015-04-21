<?php namespace App\Http\Requests\Admin;

# Illuminate stuff
use Illuminate\Foundation\Http\FormRequest;

# Facades
use Auth;
use Response;

use Zizaco\Entrust\EntrustFacade as Entrust;

class SaveGeneralSettingsRequest extends FormRequest
{
    protected $rules = [
        'bootstrap_style' => 'required',
        'navbar_theme' => 'required',
        'sitename' => 'required'
    ];

    public function rules()
    {
        $rules = $this->rules;

        if ($this->has('enable_recaptcha'))
        {
            $rules['recaptcha'] = 'required';
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
            'bootstrap_style.required' => 'A theme is required.',
            'navbar_theme.required' => 'Please choose whether the navigation should be inverted or not.',
            'recaptcha.required' => 'A reCAPTCHA key is required.',
            'sitename.required' => 'A website name is required.'
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