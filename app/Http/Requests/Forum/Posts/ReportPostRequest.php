<?php namespace App\Http\Requests\Forum\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReportPostRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$post = $this->route()->getParameter('post');

		return Auth::check() && Auth::user()->isConfirmed() && $post->topic->canView;
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
			'reason' => 'required|min:5|max:255'
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
			'reason.required' => 'Please specify a reason for reporting this item.',
			'reason.min' => 'Report reasons must be at least 5 characters.',
			'reason.max' => 'Report reasons can be up to 255 characters.'
		];
	}
}
