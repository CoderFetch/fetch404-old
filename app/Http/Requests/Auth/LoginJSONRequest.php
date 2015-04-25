<?php namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Laracasts\Flash\Flash;

class LoginJSONRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name_or_email' => 'required',
            'password' => 'required'
        ];

        $name_or_email = $this->input('name_or_email');

        if (filter_var($name_or_email, FILTER_VALIDATE_EMAIL))
        {
            $rules['name_or_email'] = 'required|email';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name_or_email.required' => 'Please enter a username or email address.',
            'name_or_email.email' => 'Please enter a valid email.',
            'password.required' => 'Please enter a password.'
        ];
    }

    public function forbiddenResponse()
    {
        // Optionally, send a custom response on authorize failure
        // (default is to just redirect to initial page with errors)
        //
        // Can return a response, a view, a redirect, or whatever else
        return response()->json(array(
           'status' => 'forbidden'
        ));
    }
}
