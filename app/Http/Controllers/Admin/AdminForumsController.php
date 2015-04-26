<?php namespace App\Http\Controllers\Admin;

// Custom controller
use App\CategoryPermission;
use App\Http\Controllers\AdminController;

// Facades
use App\Category;
use App\Channel;
use App\Http\Requests\Admin\Forum\CreateCategoryRequest;

use App\Http\Requests\Admin\Forum\EditCategoryRequest;
use App\Http\Requests\Admin\Forum\EditChannelRequest;
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

        $allowedGroups = array(20 => array());

//        foreach($request->input('allowed_groups') as $groupId)
//        {
//            $allowedGroups[20][$groupId] = array(
//                'role_id' => $groupId,
//                'category_id' => $category->id,
//                'created_at' => date('U'),
//                'updated_at' => date('U')
//            );
//        }

        $allowedGroups[20][1] = array(
            'role_id' => 1,
            'category_id' => $category->id,
            'created_at' => date('U'),
            'updated_at' => date('U')
        );

        $allowedGroups[20][3] = array(
            'role_id' => 3,
            'category_id' => $category->id,
            'created_at' => date('U'),
            'updated_at' => date('U')
        );

        //dd($allowedGroups);

//        $category->categoryPermissions()->detach([20]);
//
////        $category->categoryPermissions()->sync([20 => $allowedGroups[20]]);
//
//        foreach($allowedGroups[20] as $group)
//        {
//            $category->categoryPermissions()->sync([20 => $group]);
//        }
        $category->categoryPermissions()->sync([]);

        Flash::success('Updated category!');

        return redirect(route('admin.forum.get.index'));
    }

    public function editChannel(EditChannelRequest $request)
    {
        $channel = $request->route()->getParameter('channel');

        foreach($request->input('allowed_groups') as $groupId)
        {
            $channel->channelPermissions()->sync([20 => ['role_id' => $groupId, 'channel_id' => $channel->id, 'created_at' => date('U'), 'updated_at' => date('U')]]);
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