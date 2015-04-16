<?php namespace App\Http\Controllers\Admin;

// Custom controller
use App\Http\Controllers\AdminController;

// Facades
use App\Category;
use App\Channel;

class AdminForumsController extends AdminController 
{   
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        // Title
        $title = 'Forums';
        // Grab all the channels/categories
        $categories = Category::get();
        $channels = Channel::get();
        
        // Show the page
        return view('core.admin.forums.index', compact('categories', 'channels', 'title'));
    }

}