<?php namespace App\Http\Controllers\Admin;

// Custom controller
use App\CategoryPermission;
use App\ChannelPermission;
use App\Http\Controllers\AdminController;

// Facades
use App\Category;
use App\Channel;
use App\Http\Requests\Admin\Forum\CreateCategoryRequest;

use App\Http\Requests\Admin\Forum\EditCategoryRequest;
use App\Http\Requests\Admin\Forum\EditChannelRequest;
use App\Permission;
use Laracasts\Flash\Flash;

use App\Role;

use DB;

class AdminForumsController extends AdminController 
{
    private $category;

    private $channel;

    public function __construct(Category $category, Channel $channel)
    {
        parent::__construct();

        $this->category = $category;
        $this->channel = $channel;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = 'Forums';
        // Grab all the channels/categories
        $categories = Category::get();
        $channels = Channel::get();
        
        // Show the page
        return view('core.admin.forums.index', compact('categories', 'channels', 'title'));
    }

    public function showCreateCategory()
    {
        return view('core.admin.forums.create');
    }

    public function showEditCategory($category)
    {
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

        return view('core.admin.forums.edit', array(
            'category' => $category,
            'groups' => $groups,
            'permissions' => $queryObj->lists('role_id', 'role_id')
        ));
    }

    public function editCategory(EditCategoryRequest $request)
    {
        $category = $request->route()->getParameter('category');
        $groupIds = $request->input('allowed_groups');

        CategoryPermission::where('category_id', '=', $category->id)->where('permission_id', '=', 20)->delete();

        foreach($groupIds as $id)
        {
            $perm = CategoryPermission::firstOrCreate(array(
                'permission_id' => 20,
                'role_id' => $id,
                'category_id' => $category->id
            ));
        }

        Flash::success('Updated category!');

        return redirect(route('admin.forum.get.index'));
    }

    public function showEditChannel($channel)
    {
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

        return view('core.admin.forums.channel.edit', array(
            'channel' => $channel,
            'groups' => $groups,
            'permissions' => $queryObj->lists('role_id', 'role_id'),
            'groupIds' => ($queryObj->where('permission_id', '=', 21)->lists('role_id', 'role_id')),
            'createThreadIds' => ($queryObj->where('permission_id', '=', 1)->lists('role_id', 'role_id'))
        ));
    }

    public function editChannel(EditChannelRequest $request)
    {
        $channel = $request->route()->getParameter('channel');

        $groupIds = $request->input('allowed_groups');
        $createThreads = $request->input('create_threads');

        ChannelPermission::where('channel_id', '=', $channel->id)
            ->where('permission_id', '=', 21)
            ->orWhere('permission_id', '=', 1)
            ->delete();

        foreach($groupIds as $id)
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

        Flash::success('Updated channel!');

        return redirect(route('admin.forum.get.index'));
    }

    /*
     * Create a new category.
     *
     * @param CreateCategoryRequest $request
     */
    public function storeCategory(CreateCategoryRequest $request)
    {
        $name = $request->input('name');
        $description = $request->has('description') ? $request->input('description') : null;
        $weight = $request->has('weight') ? $request->input('weight') : 0;

        $category = $this->category->create(array(
           'name' => $name,
           'slug' => str_slug($name),
           'description' => $description,
           'weight' => $weight
        ));

        Flash::success('Created category "' . $category->name . '"!');

        return redirect(route('admin.forum.get.index'));
    }
}