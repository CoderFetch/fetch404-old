<?php namespace App\Http\Requests\Forum\Threads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ThreadCreateRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$channel = $this->route()->getParameter('channel');

		return Auth::check() && Auth::user()->isConfirmed() && $channel->canView(Auth::user());
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$channel = $this->route()->getParameter('channel');

		return [
			//
			'title' => 'required|min:5|max:20|regex:/[A-Za-z0-9\-_!\.\s]/|unique:topics,title,NULL,id,channel_id,' . $channel->id,
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
			'title.required' => 'A title is required.',
			'title.min' => 'Thread titles must be at least 5 characters long.',
			'title.max' => 'Thread titles can be up to 20 characters long.',
			'title.unique' => 'A thread with this title already exists in this channel.',
			'title.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z (uppercase or lower), 0 through 9, - (dash), _ (underscore), !, . (period), and a space.',
			'body.required' => 'A message is required.',
			'body.min' => 'Messages must be at least 20 characters long.',
			'body.max' => 'Messages can be up to 4500 characters long.'
		];
	}
}
