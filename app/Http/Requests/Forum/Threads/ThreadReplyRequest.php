<?php namespace App\Http\Requests\Forum\Threads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ThreadReplyRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$topic = $this->route()->getParameter('topic');

		return Auth::check() && Auth::user()->isConfirmed() && $topic->canView;
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
			'body' => 'required|min:20|max:4500'
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
			//
			'body.required' => 'A message is required.',
			'body.min' => 'Messages must be at least 20 characters long.',
			'body.max' => 'Messages can be up to 4500 characters long.'
		];
	}
}
