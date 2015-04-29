<?php namespace App\Http\Requests\Profiles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteProfilePostRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$post = $this->route()->getParameter('profile_post');

		return Auth::check() && Auth::user()->isConfirmed() && $post->user->id == Auth::id();
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
