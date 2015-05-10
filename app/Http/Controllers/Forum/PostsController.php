<?php namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;

use App\Http\Requests\Forum\Posts\PostDeleteRequest;
use App\Http\Requests\Forum\Posts\PostEditRequest;
use Fetch404\Core\Models\Post;
use Laracasts\Flash\Flash;

class PostsController extends Controller {

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Post $post
	 * @return Response
	 */
	public function edit(Post $post)
	{
		//
		if (!$post->topic->canView) abort(403);
		if (!$post->canEdit) abort(403);

		return view('core.forum.edit-post', array('post' => $post));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param PostEditRequest $request
	 * @return Response
	 */
	public function update(PostEditRequest $request)
	{
		//
		$post = $request->route()->getParameter('post');
		$body = $request->input('body');
		$user = $request->user();
		$oldContent = $post->content;

		$post->update(array('content' => $body));

		$post->postEdits()->create(array(
			'user_id' => $user->id,
			'post_id' => $post->id,
			'old_content' => $oldContent,
			'new_content' => $body
		));

		Flash::success('Edited post');

		return redirect($post->Route);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param PostDeleteRequest $request
	 * @return Response
	 */
	public function destroy(PostDeleteRequest $request)
	{
		//
		$post = $request->route()->getParameter('post');

		$thread = $post->topic;

		$post->reports()->delete();
		$post->likes()->delete();
		$post->postEdits()->delete();
		$post->notifications()->delete();
		$post->delete();

		if ($thread->posts()->count() == 0)
		{
			$thread->deleteReaders();
			$thread->delete();
		}

		Flash::success('Deleted post');

		return redirect((!$thread->exists() ? route('forum.get.index') : $thread->Route));
	}

}
