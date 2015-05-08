<?php namespace App\Http\Requests\Admin\Forum;

use Illuminate\Foundation\Http\FormRequest;
use Zizaco\Entrust\EntrustFacade as Entrust;

class DeleteCategoryRequest extends FormRequest {

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
		];
	}

}
