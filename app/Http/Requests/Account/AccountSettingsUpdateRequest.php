<?php namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingsUpdateRequest extends FormRequest
{
    protected $rules = [

    ];

    public function rules()
    {
        $rules = $this->rules;

        if ($this->has('name') && $this->input('name') != Auth::user()->name)
        {
            $rules['name'] = 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users';
        }

        if ($this->has('password') && !Hash::check($this->input('password'), Auth::user()->password))
        {
            $rules['password'] = 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/';
        }

        if ($this->has('email') && $this->input('email') != Auth::user()->email)
        {
            $rules['email'] = 'required|unique:users|email';
        }

        return $rules;
    }

    public function authorize()
    {
        return Auth::check() && Auth::user()->isConfirmed();
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
            'name.required' => 'A username is required.',
            'name.min' => 'Usernames must be at least 5 characters long.',
            'name.max' => 'Usernames can be up to 13 characters long.',
            'name.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
            'name.unique' => 'That username is taken. Try another!',
            'email.required' => 'An email address is required.',
            'email.unique' => 'Another account is using this email. Contact support if you don\'t know anything about this.',
            'email.email' => 'Please enter a valid email. Without this, we are unable to provide email-based support.',
            'password.required' => 'A password is required.',
            'password.min' => 'Passwords must be at least 8 characters long.',
            'password.max' => 'Passwords can be up to 30 characters long.',
            'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
            'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #'
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