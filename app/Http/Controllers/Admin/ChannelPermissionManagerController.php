<?php namespace App\Http\Controllers\Admin;

use App\Channel;
use App\ChannelPermission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use Illuminate\Http\Request;

class ChannelPermissionManagerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$channels = Channel::all();

		return view('core.admin.forums.permission-editor.channel.index', array(
			'channels' => $channels
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
	 * @param Channel $channel
	 * @return Response
	 */
	public function edit(Channel $channel)
	{
		//
		$groups = Role::lists('name', 'id');

		//$permissions = CategoryPermission::where('category_id', '=', $category->id)->get();

		$queryObj = ChannelPermission::select(array(
			'channel_permission.permission_id',
			'channel_permission.role_id',
			'channel_permission.channel_id'
		))->leftJoin('channels as ch', function($join)
		{
			$join->on('channel_permission.channel_id', '=', 'ch.id');
			//$join->on('category_forum_permission.role_id', '=', 1);
		})->with(
			'role',
			'channel',
			'permission'
		)->where('channel_id', '=', $channel->id);

		$accessChannelIds = $queryObj->where('permission_id', '=', 21)->lists('role_id', 'role_id');
		$createThreadIds = $queryObj->where('permission_id', '=', 1)->lists('role_id', 'role_id');

		return view('core.admin.forums.permission-editor.channel.edit', array(
			'channel' => $channel,
			'accessChannel' => $accessChannelIds,
			'createThread' => $createThreadIds,
			'groups' => $groups
		));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
