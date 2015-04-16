<?php namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
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

}
