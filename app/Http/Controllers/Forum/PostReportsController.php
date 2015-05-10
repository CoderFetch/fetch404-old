<?php namespace App\Http\Controllers\Forum;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Forum\Posts\ReportPostRequest;

use Fetch404\Core\Models\Post;
use Laracasts\Flash\Flash;

class PostReportsController extends Controller {

	/**
	 * Show the page for reporting a post.
	 *
	 * @param Post $post
	 * @return Response
	 */
	public function show(Post $post)
	{
		if (!$post->topic->canView) abort(403);

		return view('core.forum.report-post', array(
			'post' => $post
		));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param ReportPostRequest $request
	 * @return Response
	 */
	public function store(ReportPostRequest $request)
	{
		//
		$post = $request->route()->getParameter('post');
		$user = $request->user();

		$post->report($user, $request->input('reason'), $post->user->id);

		Flash::success('Reported post');

		return redirect()->to($post->Route);
	}

}
