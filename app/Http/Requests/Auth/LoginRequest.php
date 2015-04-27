<?php namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Laracasts\Flash\Flash;

class LoginRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$name_or_email = $this->input('name_or_email');
		$password = $this->input('password');

		$db_field = (filter_var($name_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name');

		$user = User::where(
			$db_field,
			'=',
			$name_or_email
		)->first();

		if ($user == null || !$user)
		{
			Flash::error('User not found');
			return false;
		}

		if (!Hash::check($password, $user->password))
		{
			Flash::error('Invalid password');
			return false;
		}

		if (!$user->isBanned())
		{
			if (Auth::attempt([$db_field => $name_or_email, 'password' => $password], $this->has('remember')))
			{
				return true;
			}
			else
			{
				Flash::error('You could not be logged in due to an unknown error.');
				return false;
			}
		}
		else
		{
			Flash::error('You have been banned.');
			return false;
		}

		return false;
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
		return redirect('login');
	}
}
