<?php namespace App\Http\Requests\Forum\Reports;

use App\Http\Requests\Request;

use Zizaco\Entrust\EntrustFacade as Entrust;

class CloseReportRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Entrust::can('accessAdminPanel') && Entrust::can('closeReports');
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
