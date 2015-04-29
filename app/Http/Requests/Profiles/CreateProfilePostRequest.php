<?php namespace App\Http\Requests\Profiles;

use App\Http\Requests\Request;

use Illuminate\Support\Facades\Auth;

class CreateProfilePostRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::check() && Auth::user()->isConfirmed();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
			'body' => 'required|min:10|max:1500'
		];
	}

	/**
	 * Get the validation messages that apply to the request.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'body.required' => 'A message is required.',
			'body.min' => 'Messages must be at least 10 characters long.',
			'body.max' => 'Messages can be up to 1500 characters long.'
		];
	}

	/**
	 * What should we do if the current user is not authorized to do this?
	 *
	 * @return Response
	 */
	public function forbiddenResponse()
	{
		return response()->make(view('core.errors.403'), 403);
	}
}
