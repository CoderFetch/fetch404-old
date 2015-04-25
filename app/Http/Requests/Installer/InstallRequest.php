<?php namespace App\Http\Requests\Installer;

use Illuminate\Foundation\Http\FormRequest;

class InstallRequest extends FormRequest
{
    protected $rules = [
        'mysqlHost' => 'required',
        'mysqlUser' => 'required',
        'mysqlPass' => 'required',
        'mysqlDB' => 'required',

        'username' => 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/',
        'email' => 'required|email',
        'password' => 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/',

        'outgoing_email' => 'required|email'
    ];

    public function rules()
    {
        $rules = $this->rules;

        if ($this->has('base_url'))
        {
            $rules['base_url'] = 'url';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'mysqlHost.required' => 'Please put in a database host.',
            'mysqlUser.required' => 'Please put in a database username.',
            'mysqlPass.required' => 'Please put in a database password.',
            'mysqlDB.required' => 'Please put in a database name.',

            'username.required' => 'A username is required.',
            'username.min' => 'Usernames must be at least 5 characters long.',
            'username.max' => 'Usernames can be up to 13 characters long.',
            'username.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email.',
            'password.required' => 'A password is required.',
            'password.min' => 'Passwords must be at least 8 characters long.',
            'password.max' => 'Passwords can be up to 30 characters long.',
            'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
            'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #',

            'outgoing_email.required' => 'An outgoing email address is required.',
            'outgoing_email.email' => 'Please enter a valid email.'
        ];
    }

    public function authorize()
    {
        // Whoever finds this first is able to install... common sense!
        // (even though a person who's not the owner shouldn't be installing...)
        return true;
    }

    public function forbiddenResponse()
    {
        return response()->make(view('core.installer.errors.forbidden'), 500);
    }
}