<?php namespace App\Http\Controllers\Admin;

// Custom controller
use App\Http\Controllers\AdminController;

// Facades
use App\Category;
use App\Channel;
use App\Http\Requests\Admin\Forum\CreateCategoryRequest;

use Laracasts\Flash\Flash;

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

        Flash::success('Created category!');

        return redirect(route('admin.forum.get.index'));
    }
}