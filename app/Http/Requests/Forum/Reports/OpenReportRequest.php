<?php namespace App\Http\Requests\Forum\Reports;

use App\Http\Requests\Request;

use Zizaco\Entrust\EntrustFacade as Entrust;

class OpenReportRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Entrust::can('accessAdminPanel') && Entrust::can('openReports');
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
