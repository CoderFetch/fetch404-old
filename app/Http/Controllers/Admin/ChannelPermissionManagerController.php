<?php namespace App\Http\Controllers\Admin;

use App\Channel;
use App\ChannelPermission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Forum\UpdateChannelPermissionsRequest;
use App\Role;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

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
	 * @param UpdateChannelPermissionsRequest $request
	 * @return Response
	 */
	public function update(UpdateChannelPermissionsRequest $request)
	{
		//
		$channel = $request->route()->getParameter('channel');

		$accessChannel = $request->input('allowed_groups');
		$createThreads = $request->input('create_threads');

		ChannelPermission::where('channel_id', '=', $channel->id)
			->where('permission_id', '=', 21)
			->orWhere('permission_id', '=', 1)
			->delete();

		foreach($accessChannel as $id)
		{
			$perm = ChannelPermission::firstOrCreate(array(
				'permission_id' => 21,
				'role_id' => $id,
				'channel_id' => $channel->id
			));
		}

		foreach($createThreads as $id)
		{
			$create_threads = ChannelPermission::firstOrCreate(array(
				'permission_id' => 1,
				'role_id' => $id,
				'channel_id' => $channel->id
			));
		}

		Flash::success('Updated channel permissions!');

		return redirect(route('admin.forum.get.permissions.channels.edit', array(
			$channel
		)));
	}

}
