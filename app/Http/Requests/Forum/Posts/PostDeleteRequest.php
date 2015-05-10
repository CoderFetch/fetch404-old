<?php namespace App\Http\Requests\Forum\Posts;

use Illuminate\Foundation\Http\FormRequest;

class PostDeleteRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$post = $this->route()->getParameter('post');

		return $post->canDelete;
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
