<?php namespace App\Http\Requests\Forum\Reports;

use App\Http\Requests\Request;

use Zizaco\Entrust\EntrustFacade as Entrust;

class CommentOnReportRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Entrust::can('accessAdminPanel') && Entrust::can('commentOnReports');
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
			'comment' => 'required|min:10|max:255'
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
			'comment.required' => 'Please enter a comment.',
			'comment.min' => 'Comments must be at least 10 characters long.',
			'comment.max' => 'Comments can be up to 255 characters long.'
		];
	}

}
