<?php namespace App\Http\Requests\Forum\Threads;

use Illuminate\Foundation\Http\FormRequest;

use Zizaco\Entrust\EntrustFacade as Entrust;

class ThreadLockRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$topic = $this->route()->getParameter('topic');

		return Entrust::can('moderateThreads') && Entrust::can('lockThreads') && $topic->canView;
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
