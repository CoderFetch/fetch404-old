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

		//$permissions = CategoryPermission::where('category_id', '=', $category->id)->get();

		$queryObj = CategoryPermission::select(array(
			'category_permission.permission_id',
			'category_permission.role_id',
			'category_permission.category_id'
		))->leftJoin('categories as c', function($join)
		{
			$join->on('category_permission.category_id', '=', 'c.id');
			//$join->on('category_forum_permission.role_id', '=', 1);
		})->with(
			'role',
			'category',
			'permission'
		)->where('category_id', '=', $category->id);

		$accessCategoryIds = $queryObj->where('permission_id', '=', 20)->lists('role_id', 'role_id');
		$createThreadIds = $queryObj->where('permission_id', '=', 1)->lists('role_id', 'role_id');

		return view('core.admin.forums.permission-editor.category.edit', array(
			'category' => $category,
			'accessCategory' => $accessCategoryIds,
			'createThread' => $createThreadIds,
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

		CategoryPermission::where('category_id', '=', $category->id)
			->where('permission_id', '=', 20)
			->orWhere('permission_id', '=', 1)
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
