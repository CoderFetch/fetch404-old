<?php namespace App\Http\Requests\Admin\Forum;

use Illuminate\Foundation\Http\FormRequest;
use Zizaco\Entrust\EntrustFacade as Entrust;

class CreateChannelRequest extends FormRequest {

	protected $rules = [
		'name' => 'required|min:4|max:20|unique:channels'
	];

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Entrust::can('accessAdminPanel');
	}

	public function rules()
	{
		$rules = $this->rules;

		if ($this->has('weight'))
		{
			$rules['weight'] = 'numeric';
		}

		return $rules;
	}

	public function forbiddenResponse()
	{
		// Optionally, send a custom response on authorize failure
		// (default is to just redirect to initial page with errors)
		//
		// Can return a response, a view, a redirect, or whatever else
		return response()->make(view('core.errors.403'), 403);
	}

	public function messages()
	{
		return [
			'name.required' => 'A channel title is required.',
			'name.min' => 'Channel titles must be at least 4 characters long.',
			'name.max' => 'Channel titles can be up to 20 characters long.',
			'name.unique' => 'That name is in use. Try another.',
			'weight.numeric' => 'Please enter a valid number.'
		];
	}

}
