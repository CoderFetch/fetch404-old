<?php namespace App\Http\Controllers\Forum;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Forum\Reports\CloseReportRequest;
use App\Http\Requests\Forum\Reports\CommentOnReportRequest;
use App\Http\Requests\Forum\Reports\OpenReportRequest;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class ReportsController extends Controller {

	/**
	 * Close a report.
	 *
	 * @param CloseReportRequest $request
	 * @return Response
	 */
	public function close(CloseReportRequest $request)
	{
		//
		$report = $request->route()->getParameter('report');

		if ($report->isClosed())
		{
			Flash::error('This report is already closed.');

			return redirect()->back();
		}

		$report->close();

		Flash::success('Closed report');

		return redirect()->back();
	}

	/**
	 * Re-open a report.
	 *
	 * @param OpenReportRequest $request
	 * @return Response
	 */
	public function open(OpenReportRequest $request)
	{
		$report = $request->route()->getParameter('report');

		if (!$report->isClosed())
		{
			Flash::error('This report is already open.');

			return redirect()->back();
		}

		$report->open();

		Flash::success('Opened report');

		return redirect()->back();
	}

	/**
	 * Comment on a report.
	 *
	 * @param CommentOnReportRequest $request
	 * @return Response
	 */
	public function comment(CommentOnReportRequest $request)
	{
		$report = $request->route()->getParameter('report');

		$report->comments()->create(array(
			'report_id' => $report->id,
			'user_id' => $request->user()->id,
			'body' => $request->input('comment')
		));

		Flash::success('Submitted comment');

		return redirect()->back();
	}
}
