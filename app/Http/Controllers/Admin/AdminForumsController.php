<?php namespace App\Http\Controllers\Admin;

// Custom controller
use App\ChannelPermission;
use App\Http\Controllers\AdminController;

// Facades
use App\Http\Requests\Admin\Forum\CreateCategoryRequest;

use App\Http\Requests\Admin\Forum\CreateChannelRequest;
use App\Http\Requests\Admin\Forum\DeleteCategoryRequest;
use App\Http\Requests\Admin\Forum\DeleteChannelRequest;
use App\Http\Requests\Admin\Forum\EditCategoryRequest;
use App\Http\Requests\Admin\Forum\EditChannelRequest;
use Fetch404\Core\Models\Category;
use Fetch404\Core\Models\CategoryPermission;
use Fetch404\Core\Models\Channel;
use Fetch404\Core\Models\Like;
use Fetch404\Core\Models\Role;
use Laracasts\Flash\Flash;

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
        $categories = Category::with('channels')->get();
//        $channels = Channel::get();
        
        // Show the page
        return view('core.admin.forums.index', compact('categories', 'title'));
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
            'category' => $category
        ));
    }

    public function editCategory(EditCategoryRequest $request)
    {
        $name = $request->input('name');
        $weight = $request->input('weight', 1);
        $desc = $request->input('description');
        $category = $request->route()->getParameter('category');

        $category->update(array(
            'name' => $name,
            'weight' => $weight,
            'description' => $desc
        ));

        Flash::success('Updated category!');

        return redirect(route('admin.forum.get.index'));
    }

    public function showEditChannel($channel)
    {
        $groups = Role::lists('name', 'id');

        return view('core.admin.forums.channel.edit', array(
            'channel' => $channel
        ));
    }

    public function editChannel(EditChannelRequest $request)
    {
        $channel = $request->route()->getParameter('channel');

        $name = $request->input('name');
        $weight = $request->input('weight', 1);
        $desc = $request->input('description');

        $channel->update(array(
            'name' => $name,
            'weight' => $weight,
            'description' => $desc
        ));

        Flash::success('Updated channel!');

        return redirect(route('admin.forum.get.index'));
    }

    /**
     * Show the page for creating a new channel.
     *
     * @param Category $category
     * @return Response
     */
    public function showCreateChannel(Category $category)
    {
        return view('core.admin.forums.channel.create', array('category' => $category));
    }

    /**
     * Create a new channel.
     *
     * @param CreateChannelRequest $request
     * @return Response
     */
    public function createChannel(CreateChannelRequest $request)
    {
        $name = $request->input('name');
        $weight = ($request->has('weight') ? $request->input('weight') : 1);
        $description = $request->input('description');
        $category = $request->route()->getParameter('category');

        $channel = $this->channel->create(array(
            'name' => $name,
            'weight' => $weight,
            'description' => $description,
            'category_id' => $category->id,
            'slug' => str_slug($name)
        ));

        Flash::success('Created channel!');
        return redirect()->route('admin.forum.get.index');
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

    /**
     * Delete a category.
     *
     * @param DeleteCategoryRequest $request
     * @return Response
     */
    public function deleteCategory(DeleteCategoryRequest $request)
    {
        $category = $request->route()->getParameter('category');

        $postIds = [];

        foreach($category->channels as $channel)
        {
            foreach($channel->topics as $topic)
            {
                foreach($topic->posts as $post) $postIds[] = $post->id;

                $topic->deleteReaders();
                $topic->posts()->delete();
                $topic->delete();
            }

            $channel->delete();
        }

        Like::whereIn('subject_id', $postIds)->where('subject_type', '=', 'App\Post')->delete();

        $category->delete();

        Flash::success('Deleted category');
        return redirect()->route('admin.forum.get.index');
    }

    /**
     * Delete a channel.
     *
     * @param DeleteChannelRequest $request
     * @return Response
     */
    public function deleteChannel(DeleteChannelRequest $request)
    {
        $channel = $request->route()->getParameter('channel');

        foreach($channel->topics as $topic)
        {
            foreach($topic->posts as $post)
            {
                $post->likes()->delete();
                $post->reports()->delete();
            }
        }

        foreach($channel->topics as $topic)
        {
            $topic->deleteReaders();
            $topic->posts()->delete();
            $topic->delete();
        }

        $channel->delete();

        Flash::success('Deleted channel');
        return redirect()->route('admin.forum.get.index');
    }
}