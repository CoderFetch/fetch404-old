<?php namespace App\Http\Requests\Admin\Forum;

use Illuminate\Foundation\Http\FormRequest;
use Zizaco\Entrust\EntrustFacade as Entrust;

class UpdateChannelPermissionsRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Entrust::can('accessAdminPanel');
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
			'allowed_groups' => 'required',
			'create_threads' => 'required',
			'reply_to_threads' => 'required'
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
			'allowed_groups.required' => 'You must allow at least 1 group to view this channel.',
			'create_threads.required' => 'You must allow at least 1 group to create threads in this channel.',
			'reply_to_threads.required' => 'You must allow at least 1 group to post in this category.'
		];
	}
}
