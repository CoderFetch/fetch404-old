<?php namespace App\Http\Controllers\Admin;

use App\Category;
use App\CategoryPermission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Forum\UpdateCategoryPermissionsRequest;
use App\Role;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class CategoryPermissionManagerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$categories = Category::all();

		return view('core.admin.forums.permission-editor.category.index', array(
			'categories' => $categories
		));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Category $category
	 * @return Response
	 */
	public function edit(Category $category)
	{
		//
		$groups = Role::lists('name', 'id');

		$accessCategoryIds = CategoryPermission::where('category_id', '=', $category->id)->where('permission_id', '=', 20)->lists('role_id', 'role_id');
		$createThreadIds = CategoryPermission::where('category_id', '=', $category->id)->where('permission_id', '=', 1)->lists('role_id', 'role_id');
		$replyIds = CategoryPermission::where('category_id', '=', $category->id)->where('permission_id', '=', 6)->lists('role_id', 'role_id');

		return view('core.admin.forums.permission-editor.category.edit', array(
			'category' => $category,
			'accessCategory' => $accessCategoryIds,
			'createThread' => $createThreadIds,
			'reply' => $replyIds,
			'groups' => $groups
		));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param UpdateCategoryPermissionsRequest $request
	 * @return Response
	 */
	public function update(UpdateCategoryPermissionsRequest $request)
	{
		//
		$category = $request->route()->getParameter('category');

		$accessCategory = $request->input('allowed_groups');
		$createThreads = $request->input('create_threads');
		$reply = $request->input('reply_to_threads');

		CategoryPermission::where('category_id', '=', $category->id)
			->where('permission_id', '=', 20)
			->orWhere('permission_id', '=', 1)
			->orWhere('permission_id', '=', 6)
			->delete();

		foreach($accessCategory as $id)
		{
			$perm = CategoryPermission::firstOrCreate(array(
				'permission_id' => 20,
				'role_id' => $id,
				'category_id' => $category->id
			));
		}

		foreach($createThreads as $id)
		{
			$create_threads = CategoryPermission::firstOrCreate(array(
				'permission_id' => 1,
				'role_id' => $id,
				'category_id' => $category->id
			));
		}

		foreach($reply as $id)
		{
			$perm = CategoryPermission::firstOrCreate(array(
				'permission_id' => 6,
				'role_id' => $id,
				'category_id' => $category->id
			));
		}

		Flash::success('Updated category permissions!');

		return redirect(route('admin.forum.get.permissions.category.edit', array(
			$category
		)));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
