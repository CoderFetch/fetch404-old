<?php namespace App\Http\Requests\Auth;

use Fetch404\Core\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {

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
			'name' => 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users',
			'email' => 'required|unique:users|email',
			'password' => 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/'
		];

		$enableCaptcha = Setting::where('name', '=', 'recaptcha')->first();

		if ($enableCaptcha->value == 'true')
		{
			$rules['g-recaptcha-response'] = 'recaptcha';
		}

		return $rules;
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
			'email.unique' => 'Another account is using this email.',
			'email.email' => 'Please enter a valid email.',
			'password.required' => 'A password is required.',
			'password.min' => 'Passwords must be at least 8 characters long.',
			'password.max' => 'Passwords can be up to 30 characters long.',
			'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
			'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #',
			'g-recaptcha-response.recaptcha' => 'Please complete the captcha.'
		];
	}
}
